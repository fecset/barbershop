<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Appointment; 

class UserController extends Controller
{
    public function profile()
    {
        $client = \App\Models\Client::where('email', Auth::user()->email)->first();
        if (!$client) {
            return view('profile', ['appointments' => []]);
        }
        $appointments = Appointment::where('клиент_id', $client->клиент_id)->get();
        return view('profile', compact('appointments'));
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password
        ]);

        \App\Models\Client::create([
            'клиент_id' => $user->id,
            'имя' => $user->name,
            'фамилия' => $user->last_name,
            'телефон' => $user->phone,
            'email' => $user->email
        ]);

        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(LoginRequest $request)
    {
        try {
            if(auth()->attempt(['phone' => $request->phone, 'password' => $request->password])){
                return redirect()->route('profile');
            }
            return redirect()->route('login', ['auth' => false]);
        } catch (\Exception $e) {
            \Log::error('Ошибка авторизации: ' . $e->getMessage());
            return redirect()->route('login', ['auth' => false]);
        }
    }


}
