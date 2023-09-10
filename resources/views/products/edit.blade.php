@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>商品編集</h1>
        
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div> 
        @endif
        
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="company_id">メーカー名</label>
                <select name="company_id" id="company_id" class="form-control">
                    <option value="">選択してください</option>
                    @foreach ($companies as $id => $company_name)
                        <option value="{{ $id }}" @if ($id == old('company_id', $product->company_id)) selected @endif>{{ $company_name }}</option>
                    @endforeach
                </select>
                @error('company_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="product_name">商品名</label>
                <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}">
                @error('product_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">価格</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}">
                @error('price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock">在庫数</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
                @error('stock')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="comment">コメント</label>
                <textarea name="comment" id="comment" class="form-control">{{ old('comment', $product->comment) }}</textarea>
                @error('comment')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="img_path">商品画像</label>
                <input type="file" name="img_path" id="img_path" class="form-control-file">
                @error('img_path')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            @if ($product->img_path)
                <div class="form-group">
                    <label>現在の商品画像</label>
                    <img src="{{ asset($product->img_path) }}" alt="現在の商品画像" style="max-width: 200px;">
                </div>
            @endif

            <button type="submit" class="btn btn-primary">更新</button>
            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">戻る</a>
        </form>
    </div>
@endsection
