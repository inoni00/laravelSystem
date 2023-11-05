@extends('layouts.app')


        

@section('content')


    <div class="container">
        <h1>商品一覧画面</h1>

        <div class="mb-3">
            <form method="GET" id="search-form" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="検索キーワード">
                </div>
                <div class="form-group mr-2">
                    <select name="company" class="form-control">
                        <option value="">メーカー名</option>
                        @foreach (\App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" id="search-button" class="btn btn-primary">検索</button>
            </form>
        </div>

       

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div> 
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th> 
                        <div>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">新規登録</a>
                        </div>
                </th>
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

        {{ $products->links() }}

        <div id="products-list"></div> <!-- 検索結果を表示するための要素 -->

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $(function() {
                // 検索フォームの送信をキャッチ
                $('#search-form').button(function(e) {
                    e.preventDefault();

                    // 検索キーワードを取得
                    var search = $('#search').val();

                    // 非同期リクエストを送信
                    $.ajax({
                        url: '/products', // 商品一覧を返すルートに合わせて変更
                        type: 'GET',
                        data: { search: search },
                        success: function(response) {
                            // 商品一覧を更新
                            $('#product-list').html(response);
                        }
                    });
                });
            });
        </script>
        
    </div>
@endsection
