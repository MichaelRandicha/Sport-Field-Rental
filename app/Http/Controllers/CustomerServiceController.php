<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerServiceController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $CS)
    {
        Auth::user()->authorizeRoles('PL');
        if(Auth::user()->creates()->where('id', $CS->id)->count() > 0)
            if(Auth::user()->lapangan()->count() - $CS->services()->count() > 0)
                return view('user.CS.service.create', compact('CS'));
            else
                return redirect()->back()->with('status', 'This Customer Service already manage every Lapangan you have!');
        else
            return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $CS, Request $request)
    {
        Auth::user()->authorizeRoles('PL');
        if(Auth::user()->creates()->where('id', $CS->id)->count() > 0){
            $lapangans = Auth::user()->lapangan()->whereNotIn('id', array_column($CS->serve()->get()->toArray(), 'id'))->pluck('id')->toArray();
            $request->validate([
                'lapangan_id' => ['required', Rule::in($lapangans)],
            ]);
            CustomerService::create([
                'user_id' => $CS->id,
                'lapangan_id' => $request->lapangan_id
            ]);
            return redirect()->route('CS.show', ['CS' => $CS]);
        }else
            return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lapangan  $lapangan
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $CS, CustomerService $service)
    {
        Auth::user()->authorizeRoles('PL');
        if(Auth::user()->creates()->where('id', $CS->id)->count() > 0 && $CS->id == $service->user_id){
            if(Auth::user()->lapangan()->where('id', $service->lapangan_id)->count() > 0)
                $service->delete();
            return redirect()->back();
        }else
            return redirect()->back();
    }
}
