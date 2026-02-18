<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id',
        'number',
        'date',
        'last_change_date',
        'date_close',
        'supplier_article',
        'tech_size',
        'barcode',
        'nm_id',
        'quantity',
        'total_price',
        'warehouse_name',
    ];
}
