<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelInformation extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'matricule',
        'user_id',
        'cin',
        'nationalite',
        'situation_matrimoniale',
        'nombre_epouse',
        'nombre_enfant',
        'ville'
    ];
}
