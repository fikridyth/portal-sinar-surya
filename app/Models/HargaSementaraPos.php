<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSementaraPos extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_third';
    protected $table = 'harga_sementaras';
    protected $guarded = ['id'];
}
