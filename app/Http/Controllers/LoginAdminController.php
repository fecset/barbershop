<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use App\Models\Superadmin;
use Illuminate\Support\Facades\Hash;


class LoginAdminController extends Controller
{
    public function login()
    {
        return view('admin-panel.auth');
    }

    public function loginPost(AdminLoginRequest $request)
    {
        $login = $request->login;
        $password = $request->password;

        $superAdmin = Superadmin::where('логин', $login)->first();
        if ($superAdmin && Hash::check($password, $superAdmin->пароль)) {
            auth('superadmin')->login($superAdmin); // Используем guard 'superadmin'
            return redirect()->route('admin.dashboard');
        }

        $admin = Admin::where('логин', $login)->first();
        if ($admin && Hash::check($password, $admin->пароль)) {
            auth('admin')->login($admin); // Используем guard 'admin'
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login', ['auth' => false]);
    }

}

