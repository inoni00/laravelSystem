<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('company')->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $companies = Company::pluck('company_name', 'id');

        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required', // メーカー名をバリデーションに追加
            'product_name' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');
            $imagePath = $image->store('public/images');
        }
    
        // メーカー名をデータベースに保存するか、既存のメーカー名を取得する
        $company = Company::firstOrCreate(['company_name' => $request->input('company_name')]);
    
        $product = Product::create([
            'company_id' => $company->id, // メーカーIDを保存
            'product_name' => $request->input('product_name'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'comment' => $request->input('comment'),
            'img_path' => $imagePath,
        ]);
    
        return redirect()->route('products.show', $product)
            ->with('success', '商品が登録されました。');
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
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'product_name' => 'required',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);

        $imagePath = $product->img_path;

        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');
            Storage::delete($imagePath);
            $imagePath = $image->store('public/images');
        }

        $product->update([
            'company_id' => $request->input('company_id'),
            'product_name' => $request->input('product_name'),
            'price' => $request->input('price'),
            'stock' => $request->input('stock'),
            'comment' => $request->input('comment'),
            'img_path' => $imagePath,
        ]);

        return redirect()->route('products.show', $product)
            ->with('success', '商品情報が更新されました。');
    }

    public function destroy(Product $product)
    {
        if ($product->img_path) {
            Storage::delete($product->img_path);
        }
        
        $product->delete();
    
        return redirect()->route('products.index')
            ->with('success', '商品が削除されました。');
    }
    
}
