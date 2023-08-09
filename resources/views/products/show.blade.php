@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>商品詳細</h1>

        <table class="table">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ $product->id }}</td>
                </tr>
                <tr>
                    <th>商品名</th>
                    <td>{{ $product->product_name }}</td>
                </tr>
                <tr>
                    <th>メーカー名</th>
                    <td>{{ $product->company->company_name }}</td>
                </tr>
                <tr>
                    <th>価格</th>
                    <td>{{ $product->price }}</td>
                </tr>
                <tr>
                    <th>在庫数</th>
                    <td>{{ $product->stock }}</td>
                </tr>
                <tr>
                    <th>コメント</th>
                    <td>{{ $product->comment }}</td>
                </tr>
                <tr>
                    <th>商品画像</th>
                    <td>
                        @if ($product->img_path)
                            <img src="{{ Storage::url($product->img_path) }}" alt="商品画像" style="max-width: 200px;">
                        @else
                            画像なし
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <div>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">編集</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </div>
    </div>
@endsection
