<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Image;
use File;
use Auth;

class UserController extends Controller
{
    // protected $imagePath;

    // public function __construct(){
    //     $this->imagePath = storage_path('app/public/images/user');
    // }

    public function profile(){
    	$user = Auth::user();
    	return view('user.profile', compact('user'));
    }

    public function updateprofile(Request $request){
    	$request->validate([
    		'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', Rule::unique('users')->ignore(Auth::user()->id)],
            'password' => ['required', 'string', 'min:8'],
            // 'profile_image' => ['image'],
    	]);

        $user = Auth::user();

        if(!password_verify($request->password, $user->password)){
            $errors = collect(['password' => 'Wrong Password']);
            return view('user.profile', compact('user', 'errors'));
        }
    	$reset = false;

        $user->name = $request->name;
        if($user->email !== $request->email){
            // $reset = true;
    		$user->email_verified_at = null;
    		$user->email = $request->email;
    	}
        $user->timezone = $request->tz;
        // if($request->has('image')){
        //     if (!File::isDirectory($this->imagePath)) {
        //         File::makeDirectory($this->imagePath);
        //     }
        //     if($user->profile_image != null){
        //         if(File::exists($this->imagePath.'/'.$user->profile_image)){
        //             Storage::delete('public/images/user/'.$user->profile_image);
        //         }
        //     }
        //     $file = $request->file('image');
        //     $image = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        //     $resizedImage = Carbon::now()->timestamp . '_' . uniqid() . '_resized.' . $file->getClientOriginalExtension();
        //     Image::make($file)->save($this->imagePath . '/' . $image);

        //     $user->profile_image = $image;
        //     $user->save();
        // }
    	$user->save();

    	$success = "Successfully update your profile";
        // if($reset){
        //     $success = $success."\nYou will need to verify your email again";
        // }

    	return view('user.profile', compact('user', 'success'));
    }

    public function passwordreset(){
    	return view('user.password'); 
    }

    public function passwordupdate(Request $request){
		$this->validate($request, [
    		'password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    	]);

    	$user = Auth::user();
    	if(!password_verify($request->password, $user->password)){
    		$errors = collect(['password' => 'Wrong Old Password']);
    		return view('user.password', compact('user', 'errors'));
    	}

    	$user->password = bcrypt($request->new_password);
    	$user->save();

    	$success = "Successfully update your password";

    	return view('user.password', compact('success'));
    }

    public function cs(){
        $user = Auth::user();
        return view('user.dashboard', compact('user'));
    }

    public function emailEdit(){
        return view('auth.email.edit');
    }

    public function emailUpdate(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:191', Rule::unique('users')->ignore(Auth::user()->id)],
        ]);
        Auth::user()->email = $request->email;
        Auth::user()->save();
        return redirect()->route('verification.notice')->with('success', true);
    }
}
