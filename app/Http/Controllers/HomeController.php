<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::guest()){
            return view('default');
        }else{
            return redirect()->route('lapangan.index');
        }
    }

    public function auth()
    {
        return view('user.dashboard')->with('user', Auth::user());
    }
}
