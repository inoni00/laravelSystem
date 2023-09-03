<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest as RequestsProductRequest;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('company'); // Eager loading

    // 検索キーワードがあれば商品名で絞り込み
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('product_name', 'like', "%$search%");
        }

    // メーカーが選択されていればメーカーで絞り込み
        if ($request->has('company')) {
            $company = $request->input('company');
            $query->where('company_id', $company);
        }

        $products = $query->paginate(10);

        return view('products.index', compact('products'));
    }


    public function create()
    {
        $companies = Company::pluck('company_name', 'id');

        return view('products.create', compact('companies'));
    }

    public function store(RequestsProductRequest $request)
    {
        DB::beginTransaction();

        try{
            
            
            $data = $request->all();
            
            

            
        
            // 登録処理呼び出し
            $model = new Product();
            $model->storeProduct($request);
            if($request->hasFile('img_path')){
                $image = $request->file('img_path');
                $name = $image->getClientOriginalName();
                $image->move('storage/',$name);
                $data['img_path'] = $name;
            }else{
                $data['img_path'] = null;
            }
            DB::commit();

            return redirect()->route('products.show',['product' => $model->id])
            ->with('success', '商品が登録されました。');
        } catch (\Exception $e) {
            DB::rollback();
            return back();

        }
    
        
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $companies = Company::pluck('company_name', 'id');

        return view('products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();

        try{

            $imagePath = $product->img_path;

            if ($request->hasFile('img_path')) {
                $image = $request->file('img_path');
                Storage::delete($imagePath);
                $imagePath = $image->store('public/images');
            }

            $product->updateProduct($request);
            
            DB::commit();

            return redirect()->route('products.show', ['product' => $product])
                ->with('success', '商品情報が更新されました。');

            }catch(\Exception $e) {
                DB::rollback();
                return back();
    
        }

    }

    public function destroy(Product $product)
    {

        DB::beginTransaction();

            try{

                if ($product->img_path) {
                        Storage::delete($product->img_path);
                }
                    
                    $product->delete();

                    DB::commit();
                
                    return redirect()->route('products.index')
                        ->with('success', '商品が削除されました。');
                }catch(\Exception $e){
                    DB::rollback();
                        return back();
            }
            
    }
    
}
