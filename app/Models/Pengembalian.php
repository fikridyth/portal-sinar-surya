<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    
    protected $table = 'pengembalians';
    protected $guarded = ['id'];

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['periode'] ?? false, function ($query, $periode) {
            $arrPeriode = explode(' - ', $periode);
            $query->whereBetween('date', $arrPeriode);
        });
    }
}
