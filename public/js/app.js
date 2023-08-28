import '/js/bootstrap';
// resources/js/app.js

// 必要なライブラリやコンポーネントのインポート

const axios = require('axios');

// ここにJavaScriptコードを記述

// メーカー名が入力された時にAjaxで対応するメーカーIDを取得してcompany_idに設定
document.getElementById('company_name').addEventListener('change', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route(',getCompanyId,') }}', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var companyId = JSON.parse(xhr.responseText).company_id;
            document.getElementById('company_id').value = companyId;
        }
    };
    xhr.send(JSON.stringify({ company_name: this.value, _token: '{{ csrf_token() }}' }));
});
