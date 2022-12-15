<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;
    protected $fillable = [
        'matricule',
        'libelle',
        'type_conge',
        'user_id',
        'date_debut',
        'date_fin',
        'nbre_jours'
    ];
}
