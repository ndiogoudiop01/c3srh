<?php
namespace App\Exports;

use App\Invoice;
use App\Models\Conge;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InvoicesExport implements WithMultipleSheets
{
    use Exportable;

    protected $year;
    
    public function __construct(int $year)
    {
        $this->year = $year;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $month = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'];
        for ($i= 0; $i<= count($month); $i++) {
            $sheets[] = new PresenceParMois($this->year, $month[0]);
        }

        return $sheets;
    }
    
}
?>