<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'objetivo',
        'data_reunião',
        'forma_contato',
        'status',
        'consulting_report_id',
        'consultant_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }

    public function consulting_report()
    {
        return $this->belongsTo(ConsultingReport::class);
    }
}
