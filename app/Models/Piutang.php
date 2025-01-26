<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;
    
    protected $table = 'piutangs';
    protected $guarded = ['id'];

    public function langganan() {
        return $this->belongsTo(Langganan::class, 'id_langganan', 'id');
    }
}
