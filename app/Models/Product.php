<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function storeProduct($data) {
        // 登録処理
        DB::table('Products')->insert([
            'company_id' => $data->company_id,
            'product_name' => $data->product_name,
            'price' => $data->price,
            'stock' => $data->stock,
            'comment' => $data->comment,
            'img_path' => $data->img_path,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ]);
    }

    /**
     * Summary of updateProduct
     * @param mixed $data
     * @return void
     */
    // Product.php

    public function updateProduct($data,$imagePath)
    {
        // 商品情報を更新
        $this->company_id = $data->company_id;
        $this->product_name = $data->product_name;
        $this->price = $data->price;
        $this->stock = $data->stock;
        $this->comment = $data->comment;
        $this->img_path = $imagePath;

        $this->save(); // モデルを保存
    }

}

 
