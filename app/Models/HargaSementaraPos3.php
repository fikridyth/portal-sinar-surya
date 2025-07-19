<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSementaraPos3 extends Model
{
    use HasFactory;

    protected $connection = 'mysql_pos_3';
    protected $table = 'harga_sementaras';
    protected $guarded = ['id'];
}
