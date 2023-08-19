<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
}
