<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranSecond extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_second';
    protected $table = 'pembayarans';
    protected $guarded = ['id'];
    
    public function supplier() {
        return $this->belongsTo(SupplierSecond::class, 'id_supplier', 'id');
    }
}
