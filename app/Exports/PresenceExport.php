<?php

namespace App\Exports;

use App\Models\Conge;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PresenceExport implements FromView
{
    public function __construct($liste_absence)
    {
        $this->liste_absence = $liste_absence;
    }
    public function view(): View
    {
        return view('form.export', [
            'liste_absence' => $this->liste_absence
        ]);
    }
    
}
