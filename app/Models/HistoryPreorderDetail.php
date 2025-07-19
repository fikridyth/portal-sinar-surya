<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPreorderDetail extends Model
{
    use HasFactory;

    protected $table = 'history_preorder_details';
    protected $guarded = ['id'];
    
    public function product() {
        return $this->belongsTo(Product::class, 'kode', 'kode');
    }
}
