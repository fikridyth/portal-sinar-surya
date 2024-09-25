<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiroDetail extends Model
{
    use HasFactory;
    
    protected $table = 'giro_details';
    protected $guarded = ['id'];

    public function bank() {
        return $this->belongsTo(Bank::class, 'id_bank', 'id');
    }
}
