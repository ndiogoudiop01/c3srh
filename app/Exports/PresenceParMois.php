<?php

namespace App\Exports;

use App\Models\Conge;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class PresenceParMois implements FromQuery, WithTitle
{

    private $year;
    private $month;
    public function __construct(int $year, array $month)
    {
        $this->annee = $year;
        $this->months[] = $month;
    }

    public function query()
    {
        return Conge::query()
                             ->whereYear('created_at', $this->annee)
                             ->whereMonth('created_at', $this->months);
    }
    
    public function title(): string
    {
        return 'Mois :' . $this->months;
    }
}
