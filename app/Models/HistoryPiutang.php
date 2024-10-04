<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPiutang extends Model
{
    use HasFactory;

    protected $table = 'history_piutangs';
    protected $guarded = ['id'];

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
