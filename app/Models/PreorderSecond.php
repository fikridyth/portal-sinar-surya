<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreorderSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $table = 'preorders';
    protected $guarded = ['id'];
}
