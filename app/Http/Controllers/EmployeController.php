<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use Illuminate\Http\Request;
use App\Models\Employe;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Departement;
use App\Models\PersonnelInformation;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * List des employes Card View
     */
    public function cardAllEmploye(Request $request)
    {
        
        $employes = DB::table('users')
                       ->join('employes', 'users.matricule', '=', 'employes.matricule')
                       ->select('users.*', 'employes.datenaissance', 'employes.genre', 'employes.compagnie')
                       ->get();
        $employeList = DB::table('users')->get();
        $typeconges = DB::table('type_conges')->get();
        $conges = DB::table('conges')->get();
        return view('form.allemployeecard', compact('employes', 'employeList', 'typeconges', 'conges'));
    }

    /**
     * Liste de tous les employees
     */
    public function listAllEmploye()
    {
        
        $employes = DB::table('users')
                       ->join('employes', 'users.matricule', '=', 'employes.matricule')
                       ->select('users.*', 'employes.datenaissance', 'employes.genre', 'employes.compagnie')
                       ->get();
        $employeList = DB::table('users')->get();
        $typeconges = DB::table('type_conges')->get();
        $conges = DB::table('conges')->get();
        return view('form.allemployeecard', compact('employes', 'employeList', 'typeconges', 'conges'));
    }

    // employee profile with all controller user
    public function profileEmployee($matricule)
    {
        $employers = DB::table('employes')
                ->leftJoin('personnel_information','personnel_information.matricule','employes.matricule')
                ->leftJoin('users','users.matricule','employes.matricule')
                ->where('users.matricule',$matricule)
                ->first();
        $employer = DB::table('employes')
                ->leftJoin('personnel_information','personnel_information.matricule','employes.matricule')
                ->leftJoin('users','users.matricule','employes.matricule')
                ->where('users.matricule',$matricule)
                ->get(); 
        $Abslogs  = DB::table('conges')
                 ->select('libelle', 'nbre_jours', 'date_debut', 'date_fin',DB::raw('sum(nbre_jours) as total'))
                 ->where('conges.matricule', $matricule)
                 ->where('conges.type_conge', 'Absence')
                 ->groupBy('conges.id')
                 ->get();
        $Mallogs  = DB::table('conges')
                 ->select('libelle', 'nbre_jours', 'date_debut', 'date_fin',DB::raw('sum(nbre_jours) as total'))
                 ->where('conges.matricule', $matricule)
                 ->where('conges.type_conge', 'Maladie')
                 ->groupBy('conges.id')
                 ->get();
        $Anlogs   = DB::table('conges')
                  ->select('libelle', 'nbre_jours', 'date_debut', 'date_fin',DB::raw('sum(nbre_jours) as total'))
                 ->where('conges.matricule', $matricule)
                 ->where('conges.type_conge', 'Annuel')
                 ->groupBy('conges.id')
                 ->get() ;
        return view('form.employeeprofile',compact('employer','employers', 'Abslogs', 'Mallogs', 'Anlogs'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addEmploye(Request $request)
    {
        //dd($request->input());
       $request->validate([
        'nom'    => 'required|string|max:255',
        'genre'         => 'required|string',
        'matricule'     => 'required'
       ]);

       DB::beginTransaction();
       try{
            $emp = Employe::where('matricule', '=', $request->matricule)->first();
            $user = Auth::User();
            //dd($emp);
            if ($emp === null) {
                
                $employe = new Employe();
                $employe->user_id               = $user->id;
                $employe->nom                   = $request->nom;
                $employe->datenaissance         = $request->datenaissance;
                $employe->adresse               = $request->adresse;
                $employe->telephone             = $request->telephone;
                $employe->genre                 = $request->genre;
                $employe->matricule             = $request->matricule;
                $employe->compagnie             = $request->compagnie;
                $employe->save();
                //dd($employe);

                $infos = new PersonnelInformation();
                $infos->matricule = $request->matricule;
                $infos->user_id = $user->id;
                $infos->cin = $request->cin;
                $infos->passport = $request->passport;
                $infos->nationalite = $request->nationalite;
                $infos->situation_matrimoniale = $request->situation_matrimoniale;
                $infos->nombre_epouse = $request->nombre_epouse;
                $infos->nombre_enfant = $request->nombre_enfant;
                $infos->ville = $request->ville;
                $infos->save();
                //dd($infos);
                $user = new User();
                $email  = $request->nom.'@c3s.sn';
                $role_name  = 'Employee';
                $status = 'Inactive';
                $password = Hash::make('00'.$request->matricule);
                $user->name                  = $request->nom;
                $user->matricule             = $request->matricule;
                $user->email                 = $email;
                $user->telephone             = $request->telephone;
                $user->status                =  $status;
                $user->role_name             = $role_name;
                $user->password              = $password;
                $user->save();
                //dd($user);

                DB::commit();
                Toastr::success('Add new employee successfully :)','Success');
                return redirect()->route('all/employee/card');
            } else {
                DB::rollback();
                Toastr::error('Add new employee exits :)','Error');
                return redirect()->back();
            }
       }catch(\Exception $e){
            DB::rollBack();
            Toastr::error('Add new employee exits :)','Error');
                return redirect()->back();
       }
    }

   
    public function addAttendanceLogs(Request $request)
    {
        //dd($request->input());
        $request->validate([
            'matricule'        => 'required|string|max:255',
            'libelle'          => 'required|string|max:255',
            'type_conge'       => 'required|string|max:255',
            'date_debut'       => 'required',
            'date_fin'         => 'required',
        ]);
        
        DB::beginTransaction();
        try{

            $conges = Conge::where('matricule', '=',$request->matricule)->first();
            $user = Auth::User();
            /**CONVERSION DATETIME */
            //echo $request->date_debut;
            $debut = $request->date_debut;
            $fin = $request->date_fin;
            $debut = strtotime($debut);
            $fin = strtotime($fin);
            $nbre_jours= ceil(abs($fin - $debut) / 86400);
            if ($conges === null)
            {

                $conge = new Conge();
                $conge->matricule         = $request->matricule;
                $conge->libelle           = $request->libelle;
                $conge->type_conge        = $request->type_conge;
                $conge->user_id           = $user->id;
                $conge->date_debut        = $request->date_debut;
                $conge->date_fin          = $request->date_fin;
                $conge->nbre_jours        = $nbre_jours;
                
                $conge->save();
                
                DB::commit();
                Toastr::success(' Ajout reussie :)','Success');
                return redirect()->route('all/employee/card');
            } else {
                DB::rollback();
                Toastr::error('Add new employee exits :)','Error');
                return redirect()->back();
            }
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Add new employee fail :)','Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function show(Employe $employe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function edit(Employe $employe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employe $employe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employe $employe)
    {
        //
    }
}
