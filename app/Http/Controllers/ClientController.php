<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'имя' => 'required|string|max:255',
                'фамилия' => 'required|string|max:255',
                'телефон' => 'required|string|max:20'
            ]);

            // Проверяем, существует ли клиент с таким телефоном
            $existingClient = Client::where('телефон', $validated['телефон'])->first();

            if ($existingClient) {
                // Обновляем существующего клиента
                $existingClient->update([
                    'имя' => $validated['имя'],
                    'фамилия' => $validated['фамилия']
                ]);
                return response()->json($existingClient);
            }

            // Создаем нового клиента
            $client = Client::create([
                'имя' => $validated['имя'],
                'фамилия' => $validated['фамилия'],
                'телефон' => $validated['телефон'],
                'email' => null
            ]);

            return response()->json($client, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);
            return response()->json($client);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);
            
            $validated = $request->validate([
                'имя' => 'required|string|max:255',
                'фамилия' => 'required|string|max:255',
                'телефон' => 'required|string|max:20',
                'email' => 'nullable|string|max:255'
            ]);

            $client->update($validated);
            return response()->json($client);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    }
}