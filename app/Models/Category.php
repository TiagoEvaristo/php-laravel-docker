<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao'
    ];

    public function finance_categories(){
        return $this->hasMany(FinanceCategory::class);
    }
}
