@extends('layouts.app')


        

@section('content')


    <div class="container">
        <h1>商品一覧画面</h1>

        <div class="mb-3">
            <form method="GET" id="search-form" class="form-inline" action="{{ route('products.index') }}" >
                @csrf

                <div class="form-group mr-2">
                <lavel for="search">商品名:</lavel>
                    <input type="text" name="search" class="form-control" placeholder="商品名" id="searchInput">
                </div>
                
                <div class="form-group mr-2">
                    <lavel for="company">メーカー名:</lavel>
                    <select name="company" class="form-control" id="companySelect">
                        <option value="">メーカー名</option>
                        @foreach (\App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mr-2">
                    <label for="minPrice">最低価格:</label>
                    <input type="number" name="minPrice" class="form-control" placeholder="最低価格" id="minPrice">
                </div>

                <div class="form-group mr-2">
                    <label for="maxPrice">最高価格:</label>
                    <input type="number" name="maxPrice" class="form-control" placeholder="最高価格" id="maxPrice">
                </div>

                <div class="form-group mr-2">
                    <label for="minStock">最低在庫数:</label>
                    <input type="number" name="minStock" class="form-control" placeholder="最低在庫数" id="minStock">
                </div>

                <div class="form-group mr-2">
                    <label for="maxStock">最高在庫数:</label>
                    <input type="number" name="maxStock" class="form-control" placeholder="最高在庫数" id="maxStock">
                </div>
                
                <!-- <div class="form-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="商品名" id="searchInput">
                </div> -->

            
                <button type="submit" id="searchButton" class="btn btn-primary">検索</button>
            </form>
        </div>

        <div id="searchResults"></div>

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div> 
        @endif

        <table  id="fav-table" class="tablesorter">
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
                            
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"id="delete-product">
                                @csrf
                                @method('DELETE')
                                
                                <button type="button" class="btn btn-sm btn-danger delete-product"  data-product-id="{{ $product->id}}">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $products->links('pagination::default') }}

    </div>

        

        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>        -->
          <script>


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
            $(function() {

                $(".tablesorter").tablesorter({
                    theme: 'bootstrap', // テーマをBootstrapに設定
                    widgets: ['zebra', 'filter'], // 任意のウィジェットを設定
                    header:{
                        6: { sorter: false }
                    },
                    sortList:[0,1],
                });


                $('.delete-product').on('click', function () {
                    var deleteConfirm = confirm('削除してよろしいでしょうか？');

                    if(deleteConfirm == true){
                        var clickEle = $(this)
                        var productId = clickEle.attr('data-product-id');
                        

                        // 非同期で削除リクエストを送信
                        $.ajax({
                            url: '/products/' + productId,
                            type: 'POST',
                            data:{
                                'id':productId,
                                '_method': 'DELETE'
                                },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function (response) {
                                if (response.status === 'success') {
                                    // 削除成功時、該当行を非表示にする
                                    clickEle.parents('tr').remove();
                                    alert(response.message); 
                                } else {
                                    alert('Failed to delete product');
                                }
                            },
                            error: function () {
                                alert('エラー');
                            }
                        });
                }});

                // 検索フォームの送信をキャッチ
                $('#searchButton').on('click',function(e) {
                    
                    e.preventDefault();
                    // loadProducts();

                    
                    
                    // 検索キーワードを取得
                    // function loadProducts(){
                        var searchQuery = $('#searchInput').val();
                        var companyQuery = $('#companySelect').val();
                        var minPrice = $('#minPrice').val();
                        var maxPrice = $('#maxPrice').val();
                        var minStock = $('#minStock').val();
                        var maxStock = $('#maxStock').val();
                    

                    // 非同期リクエストを送信
                        $.ajax({
                            url: '/products', // 商品一覧を返すルートに合わせて変更
                            type: 'GET',
                            data: {
                                search: searchQuery,
                                company: companyQuery,
                                minPrice: minPrice,
                                maxPrice: maxPrice,
                                minStock: minStock,
                                maxStock: maxStock
                            },
                            success: function(response){
                                var tableData = $(response).find('.table').html();
                                $('#searchResults').html(tableData);                        },
                            error: function(error){
                                console.error('AJAXエラー:', error);
                            }
                        });
                    // }
            });
            
        })
        </script>
        
    
@endsection
