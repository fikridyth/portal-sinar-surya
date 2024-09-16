<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiroHeader extends Model
{
    use HasFactory;
    
    protected $table = 'giro_headers';
    protected $guarded = ['id'];
}
