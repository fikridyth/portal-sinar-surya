<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = ['id'];

    public function unit() {
        return $this->belongsTo(Unit::class, 'id_unit', 'id');
    }

    public function departemen() {
        return $this->belongsTo(Departemen::class, 'id_departemen', 'id');
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }
}
