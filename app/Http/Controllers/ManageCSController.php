<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Session;
use Illuminate\Validation\Rule;

class ManageCSController extends Controller
{

    protected $CS_ROLE = 3;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->authorizeRoles('PL');
        $CS = Auth::user()->creates()->paginate(5);
        return view('user.CS.index', compact('CS'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->authorizeRoles('PL');
        return view('user.CS.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::user()->authorizeRoles('PL');
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $CS = User::create([
            'created_by_id' => Auth::user()->id,
            'role_id' => $this->CS_ROLE,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'timezone' => $request->tz,
        ]);

        return redirect()->route('CS.show', ['CS' => $CS]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $CS
     * @return \Illuminate\Http\Response
     */
    public function show(User $CS)
    {
        Auth::user()->authorizeRoles('PL');
    	if($CS->isCS())
            if($CS->created_by->id == Auth::user()->id){
                $services = $CS->services()->paginate(5);
                return view('user.CS.show', compact('CS', 'services'));
            }else
                return abort(401);
        else
        	return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $CS
     * @return \Illuminate\Http\Response
     */
    public function edit(User $CS)
    {
        Auth::user()->authorizeRoles('PL');
    	if($CS->isCS())
            if($CS->created_by->id == Auth::user()->id)
        	    return view('user.CS.edit', compact('CS'));
            else
                return abort(401);
        else
        	return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $CS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $CS)
    {
        Auth::user()->authorizeRoles('PL');
        if($CS->isCS()){
            if($CS->created_by->id == Auth::user()->id){
                $request->validate([
                    'name' => ['required', 'string', 'max:191'],
                    'email' => ['required', 'string', 'email', 'max:191', Rule::unique('users')->ignore($CS->id)],
                    'password' => ['nullable', 'string', 'min:8'],
                ]);
                $CS->name = $request->name;
                $CS->email = $request->email;
                if($request->has('password')){
                    $CS->password = bcrypt($request->password);
                }
                $CS->timezone = $request->tz;
                $CS->save();
                return redirect()->route('CS.index');
            }else{
                return abort(401);
            }
        }else{
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $CS
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $CS)
    {
        Auth::user()->authorizeRoles('PL');
        if($CS->isCS()){
            if($CS->created_by->id == Auth::user()->id){
                $CS->delete();
                return redirect()->route('CS.index')->with('status', 'Your Record Deleted Successfully.');
            }else{
                return abort(401);
            }
        }else{
            return redirect()->back();
        }
    }
}
