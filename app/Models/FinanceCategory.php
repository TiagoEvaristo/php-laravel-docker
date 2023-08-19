<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    use HasFactory;


    protected $fillable = [
        'finance_id',
        'category_id',
    ];

    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
