<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = ['id'];

    public function unit() {
        return $this->belongsTo(Unit::class, 'id_unit', 'id');
    }

    public function departemen() {
        return $this->belongsTo(Departemen::class, 'id_departemen', 'id');
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }

    public function productstocks() {
        return $this->hasMany(ProductStock::class, 'kode', 'kode');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(isset($filters['unit']) && $filters['unit'] !== '', function ($query) use ($filters) {
            return $query->where('id_unit', $filters['unit']);
        });
        $query->when(isset($filters['departemen']) && $filters['departemen'] !== '', function ($query) use ($filters) {
            return $query->where('id_departemen', $filters['departemen']);
        });
        $query->when(isset($filters['supplier']) && $filters['supplier'] !== '', function ($query) use ($filters) {
            return $query->where('id_supplier', $filters['supplier']);
        });
        $query->when($filters['periode'] ?? false, function ($query, $periode) {
            $arrPeriode = explode(' - ', $periode);
            $query->whereBetween('tanggal', $arrPeriode);
        });
    }
}
