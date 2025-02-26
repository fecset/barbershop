<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;

class MasterController extends Controller
{
    public function index()
    {
        return response()->json(Master::all());
    }

    public function destroy($id)
    {
        $master = Master::find($id);

        if ($master) {
            $master->delete();
            return response()->json(['message' => 'Master deleted successfully.'], 200);
        }

        return response()->json(['message' => 'Master not found.'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'имя' => 'required|string|max:255',
            'специализация' => 'required|string|max:255',
            'график_работы' => 'required|string|max:500',
        ]);

        $master = Master::create([
            'имя' => $request->имя,
            'специализация' => $request->специализация,
            'график_работы' => $request->график_работы,
        ]);

        return response()->json(['message' => 'Master created successfully.', 'master' => $master], 201);
    }

    public function update(Request $request, $id)
    {
        $master = Master::findOrFail($id);

        $request->validate([
            'имя' => 'required|max:255',
            'специализация' => 'required|max:255',
            'график_работы' => 'required|max:255',
        ]);

        $master->update([
            'имя' => $request->имя,
            'специализация' => $request->специализация,
            'график_работы' => $request->график_работы,
        ]);

        return response()->json($master, 200);
    }


}
