<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            
                'company_id' => 'required', // メーカー名をバリデーションに追加
                'product_name' => 'required',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'comment' => 'nullable',
                'img_path' => 'nullable|image|max:2048',
            ];
        
    }

    public function messages() {
        return [
            'company_id.required' =>'メーカー名を選択してください。',
            'product_name.required' => ':商品名は必須項目です。',
            'price.required' => ':価格は必須項目です。',
            'price.min' => ':価格は:0以上で入力してください。',
            'stock.required' => ':在庫は必須項目です。',
            'stock.min' => ':在庫は:0以上で入力してください。',
            'comment.max' => ':コメントは:140字以内で入力してください。',
        ];
    }
}
