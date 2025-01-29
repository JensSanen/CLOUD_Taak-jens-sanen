<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LaravelWorkers;

class LaravelWorkersController extends Controller
{
    public function index()
    {
        try {
            // Haal alle werknemers op en converteer naar een array
            $workers = LaravelWorkers::all()->toArray();
            return response()->json($workers, 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function show($workerId)
    {
        try {
            // Zoek de werknemer op basis van workerId
            $worker = LaravelWorkers::find($workerId);

            if ($worker) {
                // Converteer de werknemer naar een array en retourneer deze
                return response()->json($worker->toArray(), 200);
            } else {
                // Retourneer een foutmelding als de werknemer niet gevonden is
                return response()->json(['error' => 'Worker not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
