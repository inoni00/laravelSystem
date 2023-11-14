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
        $products = $query->paginate(10);
    
        // 通常のリクエストの場合、商品一覧ページを表示
        return view('products.index', compact('products'));
    }


// ProductController.php
    // public function search(Request $request)
    // {
    //     // 検索ロジックを実行
    //     // $results = ...;
    //     $query = Product::with('company'); // Eager loading

    // // 検索キーワードがあれば商品名で絞り込み
    //     if ($request->has('search')) {
    //         $search = $request->input('search');
    //         $query->where('product_name', 'like', "%$search%");
    //     }

    // // メーカーが選択されていればメーカーで絞り込み
    //     if ($request->has('company')) {
    //         $company = $request->input('company');
    //         $query->where('company_id', $company);
    //     }

    //     $products = $query->paginate(10);

        
    //     if ($request->ajax()) {
    //         // 非同期リクエストの場合、検索結果のビューを返す
    //         return view('products.search_results');
    //     }

        
    // }

    // app/Http/Controllers/ProductController.php

public function search(Request $request)
{
    $query = Product::with('company');

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('product_name', 'like', "%$search%");
    }

    if ($request->has('company')) {
        $company = $request->input('company');
        $query->where('company_id', $company);
    }

    $products = $query->paginate(10);

    return view('products.search_results', compact('products'))->render();
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
            $product = new Product();
            
            $imagePath = null; 
            if ($request->hasFile('img_path')) { 
                $image = $request->file('img_path'); 
                $filename = $image->getClientOriginalName(); 
                //まず、だけを単品で行い、
                $image->storeAs('public/images',$filename);
                 
                //続いて、
                $imagePath ='storage/images/'.$filename;
                //のようにDBに保存する値を設定する。 
                 
            }else{ 
                $imagePath = ""; 
            } 

            $product = Product::create([
                 'company_id' => $request->input('company_id'),
                 'product_name' => $request->input('product_name'),
                 'price' => $request->input('price'), 
                 'stock' => $request->input('stock'), 
                 'comment' => $request->input('comment'),
                    //if文内で$imagePathの設定をしたので、ここは$imagePathだけ渡す 
                 'img_path' => $imagePath, 
                ]); 
                    
            DB::commit();

            return redirect()->route('products.create',['product' => $product->id])
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

            // $imagePath = $product->img_path;
            // if ($request->hasFile('img_path')) {
            //     $image = $request->file('img_path');
            //     Storage::delete($imagePath);
            //     $filename = $image->getClientOriginalName();
            //     $imagePath = $image->storeAs('public/images',$filename);
            // }

            $imagePath = $product->img_path; 
            if ($request->hasFile('img_path')) {
                $image = $request->file('img_path'); 
                //Storage::deleteはpublic/images/ファイル名と指定をするべきだが、 
                //storage/images/ファイル名でDBには保存されてしまっているので、 
                //substrやmb_substrを使用したりして加工してあげる必要がある。
                 Storage::delete(substr($imagePath,0,80)); 
                 $filename = $image->getClientOriginalName(); 
                 //まず、だけを単品で行い、
                 $image->storeAs('public/images',$filename);
                 //続いて、のようにDBに保存する値を設定する。
                 $imagePath ='storage/images/'.$filename;
                  
                  
                }

            $product->updateProduct($request,$imagePath);
            
            DB::commit();

            return redirect()->route('products.edit', ['product' => $product])
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
