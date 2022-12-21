<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeuillesAbsenceController extends Controller
{
    //Les absences
    public function liste_absences()
    {
        $liste_absence = DB::table('conges')
                         ->join('employes', 'employes.matricule', '=', 'conges.matricule')
                         ->select('conges.*', 'employes.nom', 'employes.compagnie')
                         ->get();
        return view('form.leaves', compact('liste_absence'));
    }
}
