<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use Brian2694\Toastr\Facades\Toastr;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeuillesAbsenceController extends Controller
{
    //Les absences
    public function liste_absences()
    {
        $total_absence = DB::table('conges')->get()->sum("nbre_jours");
        $total_employe = DB::table('employes')->get()->count('id');
        $employeList = DB::table('employes')->get();
        $liste_absence = DB::table('conges')
                         ->join('employes', 'employes.matricule', '=', 'conges.matricule')
                         ->select('conges.id', 'conges.matricule', 'conges.libelle', 'conges.type_conge', 'conges.date_debut', 'conges.date_fin','conges.nbre_jours', DB::raw('SUM(conges.nbre_jours) as days'),'employes.nom', 'employes.compagnie')
                         ->groupBy('conges.id', 'employes.id')
                         ->get();
        return view('form.leaves', compact('liste_absence', 'total_absence', 'total_employe', 'employeList'));
    }

    //recherche
    public function absenceSearch(Request $request)
    {
        $total_absence = DB::table('conges')->get()->sum("nbre_jours");
        
        $liste_absence= DB::table('conges')
                         ->join('employes', 'employes.matricule', '=', 'conges.matricule')
                         ->select('conges.*', 'employes.nom', 'employes.compagnie')
                         ->groupBy('conges.id')
                         ->get();
        //recherche par nom
        if($request->nom)
        {
            $liste_absence= DB::table('conges')
                      ->join('employes', 'conges.matricule', '=', 'employes.matricule')
                      ->select('conges.*', 'employes.nom', 'employes.compagnie')
                      ->where('employes.nom', 'LIKE', '%'.$request->nom.'%')
                      ->get();
        }
        //recherche par Type Absence
        if($request->type_conge)
        {
            $liste_absence= DB::table('conges')
                      ->join('employes', 'conges.matricule', '=', 'employes.matricule')
                      ->select('conges.*', 'employes.nom', 'employes.compagnie')
                      ->where('conges.type_conge', 'LIKE', '%'.$request->type_conge.'%')
                      ->get();
        }
        //recherche par matricule
        if($request->matricule)
        {
            $liste_absence= DB::table('conges')
                      ->join('employes', 'conges.matricule', '=', 'employes.matricule')
                      ->select('conges.*', 'employes.nom', 'employes.compagnie')
                      ->where('employes.matricule', '=', $request->matricule)
                      ->get();
        }
        //recherche par nom & matricule & type conges
        if($request->nom && $request->matricule && $request->type_conge)
        {
            $liste_absence= DB::table('conges')
                      ->join('employes', 'conges.matricule', '=', 'employes.matricule')
                      ->select('conges.*', 'employes.nom', 'employes.compagnie')
                      ->where('employes.nom', 'LIKE', '%'.$request->nom.'%')
                      ->where('conges.matricule', 'LIKE', '%'.$request->matricule.'%')
                      ->where('conges.type_conge', 'LIKE', '%'.$request->type_conge.'%')
                      ->get();
        }
        //recherche par nom & matricule 
        if($request->nom && $request->matricule)
        {
            $liste_absence= DB::table('conges')
                      ->join('employes', 'conges.matricule', '=', 'employes.matricule')
                      ->select('conges.*', 'employes.nom', 'employes.compagnie')
                      ->where('employes.nom', 'LIKE', '%'.$request->nom.'%')
                      ->where('conges.matricule', 'LIKE', '%'.$request->matricule.'%')
                      ->get();
        }
        //recherche par matricule & type conges
        if($request->matricule && $request->type_conge)
        {
            $liste_absence= DB::table('conges')
                      ->join('employes', 'conges.matricule', '=', 'employes.matricule')
                      ->select('conges.*', 'employes.nom', 'employes.compagnie')
                      ->where('conges.matricule', 'LIKE', '%'.$request->matricule.'%')
                      ->where('conges.type_conge', 'LIKE', '%'.$request->type_conge.'%')
                      ->get();
        }
        //recherche par nom & type conges
        if($request->nom  && $request->type_conge)
        {
            $liste_absence= DB::table('conges')
                      ->join('employes', 'conges.matricule', '=', 'employes.matricule')
                      ->select('conges.*', 'employes.nom', 'employes.compagnie')
                      ->where('employes.nom', 'LIKE', '%'.$request->nom.'%')
                      ->where('conges.type_conge', 'LIKE', '%'.$request->type_conge.'%')
                      ->get();
        }
        return view('form.leaves', compact('liste_absence', 'total_absence'));
    }

    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'leave_type'   => 'required|string|max:255',
            'from_date'    => 'required|string|max:255',
            'to_date'      => 'required|string|max:255',
            'leave_reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day     = $from_date->diff($to_date);
            $days    = $day->d;

            $leaves = new Conge();
            $leaves->user_id        = $request->user_id;
            $leaves->leave_type    = $request->leave_type;
            $leaves->from_date     = $request->from_date;
            $leaves->to_date       = $request->to_date;
            $leaves->day           = $days;
            $leaves->leave_reason  = $request->leave_reason;
            $leaves->save();
            
            DB::commit();
            Toastr::success('Create new Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Leaves fail :)','Error');
            return redirect()->back();
        }
    }

    // edit record
    public function editRecordLeave(Request $request)
    {
        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day     = $from_date->diff($to_date);
            $days    = $day->d;

            $update = [
                'id'           => $request->id,
                'leave_type'   => $request->leave_type,
                'from_date'    => $request->from_date,
                'to_date'      => $request->to_date,
                'day'          => $days,
                'leave_reason' => $request->leave_reason,
            ];

            Conge::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Updated Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update Leaves fail :)','Error');
            return redirect()->back();
        }
    }

    // delete record
    public function deleteLeave(Request $request)
    {
        try {

            Conge::destroy($request->id);
            Toastr::success('Leaves admin deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Leaves admin delete fail :)','Error');
            return redirect()->back();
        }
    }

    // leaveSettings
    public function leaveSettings()
    {
        return view('form.leavesettings');
    }

    // attendance admin
    public function attendanceIndex()
    {
        return view('form.attendance');
    }

    // attendance employee
    public function AttendanceEmployee()
    {
        return view('form.attendanceemployee');
    }

    // leaves Employee
    public function leavesEmployee()
    {
        return view('form.leavesemployee');
    }

    // shiftscheduling
    public function shiftScheduLing()
    {
        return view('form.shiftscheduling');
    }

    // shiftList
    public function shiftList()
    {
        return view('form.shiftlist');
    }
}
