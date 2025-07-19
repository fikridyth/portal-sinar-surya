<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPos1 extends Model
{
    use HasFactory;

    protected $connection = 'mysql_pos_1';
    protected $table = 'products';
    protected $guarded = ['id'];
}
