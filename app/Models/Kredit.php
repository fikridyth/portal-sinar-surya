<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kredit extends Model
{
    use HasFactory;
    
    protected $table = 'kredits';
    protected $guarded = ['id'];

    public function langganan() {
        return $this->belongsTo(Langganan::class, 'id_langganan', 'id');
    }
}
