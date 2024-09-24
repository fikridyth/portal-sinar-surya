<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    
    protected $table = 'banks';
    protected $guarded = ['id'];

    public function giro() {
        return $this->hasMany(GiroHeader::class, 'id_bank', 'id');
    }
}
