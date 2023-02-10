<?php
$pivot = array();

foreach($liste_absence as $liste)
{
    $nom = $liste->nom;
    $mois = '';
                                                if($liste->date_create == '01/2022')
                                                {
                                                    $mois = 'Janvier';
                                                }else if($liste->date_create == '02/2022')
                                                {
                                                    $mois = 'Fevrier';
                                                }else if($liste->date_create == '03/2022')
                                                {
                                                    $mois = 'Mars';
                                                } else
                                                if($liste->date_create == '04/2022')
                                                {
                                                    $mois = 'Avril';
                                                }else if($liste->date_create == '05/2022')
                                                {
                                                    $mois = 'Mai';
                                                }else if($liste->date_create == '06/2022')
                                                {
                                                    $mois = 'Juin';
                                                }else if($liste->date_create == '07/2022')
                                                {
                                                    $mois = 'Juillet';
                                                }else if($liste->date_create == '08/2022')
                                                {
                                                    $mois = 'Aout';
                                                }else if($liste->date_create == '09/2022')
                                                {
                                                    $mois = 'Septembre';
                                                }else if($liste->date_create == '10/2022')
                                                {
                                                    $mois = 'Decembre';
                                                }else if($liste->date_create == '11/2022')
                                                {
                                                    $mois = 'Novembre';
                                                }else if($liste->date_create == '12/2022')
                                                {
                                                    $mois = 'Decembre';
                                                }
    $days = $liste->nbre_jours;
    $pivot[$nom][$mois] = $days;
}

$listes = array_keys($pivot);
$months = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");

echo "<table border='1'>";
echo "<tr><td></td>";
foreach($months as $month){
    echo "<td>".$month."</td>";
}
echo "</tr>";
foreach($pivot as $nom => $days)
{
    echo "<tr>";
    echo "<td>".$nom."</td>";
    foreach($months as $month)
    {
        echo "<td>".(isset($days[$month]) ? $days[$month] : 0)."</td>";
    }

    echo "</tr>";
}
echo "</table>";
?>