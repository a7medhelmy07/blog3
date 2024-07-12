<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $id = Auth::id();
        if ($user->profile == null) {
            $data = Profile::create([
                'gender' => 'male',
                'bio' => 'this my bio',
                'age' => '20',
                'user_id' => $id,
            ]);
        }
        return view('users.profile',compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'gender' => 'required',
                'bio' => 'required',
                'age' => 'required',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->profile->gender = $request->gender;
        $user->profile->bio = $request->bio;
        $user->profile->age = $request->age;
        $user->profile->save();
        $user->save;


        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            $user->save;
        }
        return redirect()->route('profile');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
