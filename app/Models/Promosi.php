<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promosi extends Model
{
    use HasFactory;
    
    protected $table = 'promosis';
    protected $guarded = ['id'];

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
