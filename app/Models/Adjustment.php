<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    use HasFactory;
    
    protected $table = 'adjustments';
    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class, 'id_product', 'id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['periode'] ?? false, function ($query, $periode) {
            // Jika filter 'periode' ada, gunakan rentang tanggal yang diberikan
            $arrPeriode = explode(' - ', $periode);
            $query->whereBetween('tanggal', $arrPeriode);
        }, function ($query) {
            // Jika filter 'periode' tidak ada, gunakan nilai default
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
        });
    }
}
