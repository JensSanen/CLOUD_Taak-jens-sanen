<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WorkerController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'uurregistratie_api:5000']);
    }

    public function index()
    {
        log::info("Fetching workers");
        $response = $this->client->get("/api/workers");
        $workers = json_decode($response->getBody()->getContents(), true);
        // Log::info($workers);
        return response()->json($workers);
    }

    public function show($workerId)
    {
        try {
            $response = $this->client->get("/api/workers/{$workerId}");
            $worker = json_decode($response->getBody()->getContents(), true);

            return response()->json($worker);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Worker not found'], 404);
        }
    }

}




