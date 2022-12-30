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
use App\Models\Tracabilite;
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
        $conges = DB::table('conges')->join('employes', 'employes.matricule', '=', 'conges.matricule')->get();
        $departement = DB::table('departements')->get();
        return view('form.allemployeecard', compact('employes', 'employeList', 'typeconges', 'conges', 'departement'));
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
        $departement = DB::table('departements')->get();
        return view('form.allemployeecard', compact('employes', 'employeList', 'typeconges', 'conges', 'departement'));
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
                $infos->matricule              = $request->matricule;
                $infos->user_id                = $user->id;
                $infos->cin                    = $request->cin;
                $infos->passport               = $request->passport;
                $infos->nationalite            = $request->nationalite;
                $infos->situation_matrimoniale = $request->situation_matrimoniale;
                $infos->nombre_epouse          = $request->nombre_epouse;
                $infos->nombre_enfant          = $request->nombre_enfant;
                $infos->ville                  = $request->ville;
                $infos->save();
                //dd($infos);

                $user = new User();
                $email                       = $request->nom.'@c3s.sn';
                $role_name                   = 'Employee';
                $status                      = 'Inactive';
                $password                    = Hash::make('00'.$request->matricule);
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
        ]); 
        $conges = Conge::where('matricule', '=',$request->matricule)->latest('date_debut')->first();
        DB::beginTransaction();
        try{

            
            $user = Auth::User();
            /**CONVERSION DATETIME */
            $date=Carbon::now();
            $now = $date->format('Y-m-d');
            $conge = new Conge();

            if ($conges != null) {
                
                // $created = new \DateTime();
                $created=new \DateTime($conges->date_debut);
                $created = $created->format('Y-m-d');
                $days = (int)$conges->nbre_jours;
                
                if($created == $request->date_debut){
                   
                    if (!empty($request->nbre_jours)) {
                        $nbre_jours = $request->nbre_jours+$days;
                        $debut = $request->date_debut;
                        $fin = $request->date_fin;
                    } else {
                        $debut = $request->date_debut;
                        $fin = $request->date_fin;
                        $debutc = strtotime($debut);
                        $finc = strtotime($fin);
                        $nbre_jours= ceil(abs($finc - $debutc) / 86400)+$days;        
                    }
                }else{
                    if (!empty($request->nbre_jours)) {
                        $nbre_jours = $request->nbre_jours;
                        $debut = $request->date_debut;
                        $fin = $request->date_fin;
                    } else {
                        $debut = $request->date_debut;
                        $fin = $request->date_fin;
                        $debutc = strtotime($debut);
                        $finc = strtotime($fin);
                        $nbre_jours= ceil(abs($finc - $debutc) / 86400);
                    }
                }
                $conge->matricule         = $request->matricule;
                $conge->libelle           = $request->libelle;
                $conge->type_conge        = $request->type_conge;
                $conge->user_id           = $user->id;
                $conge->date_debut        = $debut;
                $conge->date_fin          = $fin;
                $conge->nbre_jours        = $nbre_jours;
            }else{
               
                if (!empty($request->nbre_jours)) {
                    $nbre_jours = $request->nbre_jours;
                    $debut = Carbon::now();
                    $fin = Carbon::now();
                } else {
                    $debut = $request->date_debut;
                    $fin = $request->date_fin;
                    $debutc = strtotime($debut);
                    $finc = strtotime($fin);
                    $nbre_jours= ceil(abs($finc - $debutc) / 86400);
                }

                $conge->matricule         = $request->matricule;
                $conge->libelle           = $request->libelle;
                $conge->type_conge        = $request->type_conge;
                $conge->user_id           = $user->id;
                $conge->date_debut        = $debut;
                $conge->date_fin          = $fin;
                $conge->nbre_jours        = $nbre_jours;
            }
            

                
               
                
                $conge->save();
                
                DB::commit();
                Toastr::success(' Ajout reussie :)','Success');
                return redirect()->route('all/employee/card');
            
            
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
    public function showRecord($matricule)
    {
        $user = Auth::User();
        $employes = DB::table('employes')
            ->join('personnel_information', 'employes.matricule', '=', 'personnel_information.matricule')
            ->select('employes.*', 'personnel_information.*')
            ->where('employes.matricule', '=', $matricule)
            ->get();
        return view('form.edit.editemployee', compact('employes', 'user'));
    }

    public function updateRecord(Request $request)
    {
        $user = Auth::User();
        $emp = Employe::where('matricule', '=',$request->matricule)->first();
        DB::transaction();
        try {
            if($emp->nom != $request->nom)
            {

            }
            $updateEm = [
                'matricule' => $request->id,
                'nom'       => $request->nom,
                'telephone' => $request->telephone,
                'datenaissance' => $request->datenaissance,
                'genre'         => $request->genre,
                'compagnie'     => $request->compagnie,
            ];

            $personnel = [
                'cin' => $request->cin,
                'nationalite' => $request->nationalite
            ];
            
            Employe::where('matricule', $request->matricule)->update($updateEm);
            PersonnelInformation::where('matricule',$request->matricule)->update($personnel);

            DB::commit();
            Toastr::success('updated record successfully :)','Success');
            return redirect()->route('all/employee/card');
        } catch (\Throwable $th) {
            //throw $th;
        }
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
    public function destroyEmp($id)
    {

        DB::beginTransaction();
        try {
            $emp = Conge::where('id', '=', $id)->first();
            $user = Auth::User();
            $ancien_valeur = $emp->matricule.' & '.$emp->libelle;
            $tracabilite = [
                'user_id'       => $user->id,
                'table_name'    => 'conges',
                'id_ligne'      => $emp->id,
                'ancien_valeur' => $ancien_valeur,
                'type_modif'    =>'delete'
            ];
  
           DB::table('tracabilite')->insert($tracabilite);
             
            Conge::where('id', $id)->delete();

            DB::commit();
            Toastr::success('Suppresion avec succes :)', 'Success');
            return redirect()->route('all/employee/card');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Delete record fail :)','Error');
            return redirect()->back();
        }
    }
}
