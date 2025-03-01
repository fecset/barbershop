<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    // Получение всех администраторов
    public function index()
    {
        return Admin::all();
    }

    public function dashboard()
    {
        if (!auth('superadmin')->check() && !auth('admin')->check()) {
            return redirect()->route('admin.login');
        }

        return view('admin-panel.index');
    }


    public function store(Request $request)
    {

        $request->validate([
            'имя' => 'required',
            'логин' => 'required',
            'пароль' => 'required',
        ]);

        $admin = Admin::create([
            'имя' => $request->имя,
            'логин' => $request->логин,
            'пароль' => bcrypt($request->пароль),
        ]);

        return response()->json($admin, 201);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'имя' => 'required|max:255',
            'логин' => 'required|max:255',
        ]);

        $admin->update([
            'имя' => $request->имя,
            'логин' => $request->логин,
        ]);

        return response()->json($admin, 200);
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json(['message' => 'Администратор удалён']);
    }
}
