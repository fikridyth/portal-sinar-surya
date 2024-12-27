<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSementara extends Model
{
    use HasFactory;

    protected $table = 'harga_sementaras';
    protected $guarded = ['id'];

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
