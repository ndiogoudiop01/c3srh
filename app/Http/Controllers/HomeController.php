<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.dashboard');
    }

     // employee dashboard
     public function emDashboard()
     {
         $dt        = Carbon::now();
         $todayDate = $dt->toDayDateTimeString();
         return view('dashboard.emdashboard',compact('todayDate'));
     }
}