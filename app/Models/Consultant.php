<?php

namespace App\Models;

use App\Models\ConsultingReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'contato_principal',
        'especialidade'
    ];
    

    public function consultingReport()
    {
        return $this->belongsTo(ConsultingReport::class);
    }
}
