<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(Service::all());
    }

    public function destroy($id)
    {
        $service = Service::find($id);

        if ($service) {
            $service->delete();
            return response()->json(['message' => 'Service deleted successfully.'], 200);
        }

        return response()->json(['message' => 'Service not found.'], 404);
    }

    public function store(Request $request)
    {

        $request->validate([
            'название' => 'required|max:255',
            'цена' => 'required|numeric|min:0',
            'специализация' => 'required|max:255',
        ]);

        $service = Service::create([
            'название' => $request->название,
            'цена' => $request->цена,
            'специализация' => $request->специализация,
        ]);

        return response()->json($service, 201);
    }

}

