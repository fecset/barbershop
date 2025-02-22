<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Appointment; 

class UserController extends Controller
{
    public function profile()
    {
        $appointments = Appointment::where('клиент_id', auth()->id())->get();

        return view('profile', compact('appointments'));
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        \App\Models\Client::create([
            'клиент_id' => $user->id, 
            'имя' => $user->name,  
            'фамилия' => $request->last_name, 
            'email' => $user->email,
            'телефон' => $request->phone, 
        ]);

        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(LoginRequest $request)
    {
        if(auth()->attempt($request->validated())){
            return redirect()->route('profile');
        }
        return redirect()->route('login', ['auth' => false]);
    }


}
