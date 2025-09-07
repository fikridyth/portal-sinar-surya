<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promosi extends Model
{
    use HasFactory;
    
    protected $table = 'promosis';
    protected $guarded = ['id'];

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['periode'] ?? false, function ($query, $periode) {
            $arrPeriode = explode(' - ', $periode);
            $startDate = date('Y-m-d 00:00:00', strtotime($arrPeriode[0]));
            $endDate   = date('Y-m-d 23:59:59', strtotime($arrPeriode[1]));
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        });

        
        $query->when($filters['supplier'] ?? false, function ($query, $supplier) {
            $query->where('id_supplier', $supplier);
        });
    }
}
