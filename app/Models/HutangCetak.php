<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HutangCetak extends Model
{
    use HasFactory;
    
    protected $table = 'hutang_cetaks';
    protected $guarded = ['id'];
}
