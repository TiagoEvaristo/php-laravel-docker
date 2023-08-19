<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'relatorio',
        'meta_corte',
        'valor_estimado_ganho',
        'contas_corte', //quantidade de contas a cortar 
        'consultant_id',
    ];

    public function consulting()
    {
        return $this->belongsTo(Consulting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
