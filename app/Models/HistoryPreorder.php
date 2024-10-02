<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPreorder extends Model
{
    use HasFactory;

    protected $table = 'history_preorders';
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['periode'] ?? false, function ($query, $periode) {
            $arrPeriode = explode(' - ', $periode);
            $query->whereBetween('date', $arrPeriode);
        });
    }
}
