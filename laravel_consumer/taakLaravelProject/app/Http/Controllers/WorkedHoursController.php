<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WorkedHoursController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'uurregistratie_api:5000']);
    }

    public function index($projectId)
    {
        log::info("Fetching worked hours for project with id: $projectId");
        $response = $this->client->get("/api/projects/{$projectId}/workedHours");
        $workedHours = json_decode($response->getBody()->getContents(), true);
        // Log::info($workedHours);
        return response()->json($workedHours);
    }

    public function indexWorkers($projectId, $workerId)
    {
        log::info("Fetching worked hours for worker with id: $workerId and for project with id: $projectId");
        $response = $this->client->get("/api/projects/{$projectId}/workedHours/{$workerId}");
        $workedHours = json_decode($response->getBody()->getContents(), true);
        // Log::info($workedHours);
        return response()->json($workedHours);
    }

    public function store($projectId, Request $request)
    {
        $data = [
            'fullName' => $request->input('fullName'),
            'hours' => $request->input('hours'),
            'comment' => $request->input('comment'),
            'date' => $request->input('date')
        ];

        Log::info($data);

        try {
            $response = $this->client->post("/api/projects/${projectId}/workedHours", [
                'json' => $data
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($whId, Request $request)
    {
        try {
            Log::info("Deleting worked hours with id: $whId");
            $response = $this->client->delete("/api/workedHours/{$whId}?pwd={$request->input('password')}");

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}


