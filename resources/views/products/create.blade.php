@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>商品新規登録</h1>
        @if ($errors->any())
                <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
            <label for="company_id">メーカー名</label>
                <select name="company_id" id="company_id" class="form-control">
                    <option value="">メーカーを選択</option>
                    @foreach ($companies as $companyId => $companyName)
                        <option value="{{ $companyId }}">{{ $companyName }}</option>
                    @endforeach

                </select>
                @error('company_id') 
                    <span class="text-danger">{{ $message }}</span> 
                @enderror
            </div>

            <!-- 隠しフィールド company_id 用の input を追加 -->
            <input type="hidden" name="company_id_hidden" id="company_id_hidden" value="{{ old('company_id') ?? '' }}">

            <div class="form-group">
                <label for="product_name">商品名</label>
                <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}">
                @error('product_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">価格</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ old('price') }}">
                @error('price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock">在庫数</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}">
                @error('stock')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="comment">コメント</label>
                <textarea name="comment" id="comment" class="form-control">{{ old('comment') }}</textarea>
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

                

            <button type="submit" class="btn btn-primary"onclick="return confirm('本当に登録しますか？')">登録</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </form>
    </div>
    
    

    
@endsection
