<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemens';
    protected $guarded = ['id'];

    public function unit() {
        return $this->belongsTo(Unit::class, 'id_unit', 'id');
    }
}
