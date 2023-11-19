<!-- resources/views/products/search_results.blade.php -->

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>メーカー名</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>
                 @if ($product->img_path)
                    <img src="{{ asset($product->img_path) }}"  style="max-width: 100px;">
                @else
                    画像なし
                @endif
            </td>
            <td>{{ $product->product_name}}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company->company_name }}</td>
            <td>
            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">詳細</a>
                            
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links('pagination::default') }}
