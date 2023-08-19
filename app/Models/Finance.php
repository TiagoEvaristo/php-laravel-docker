<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tipo',
        'nome',
        'descricao',
        'valor',
        'data',
        'user_id',
        'status', //true = pago | false = não pago
    ];

    public function finance_categories()
    {
        return $this->hasMany(FinanceCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
