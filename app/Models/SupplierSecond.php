<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierSecond extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_second';
    protected $table = 'suppliers';
    protected $guarded = ['id'];
}
