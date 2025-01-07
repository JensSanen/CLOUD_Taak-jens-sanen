<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class PhaseController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'werfplanning_api:5000']);
    }

    public function index($projectId)
    {
        log::info("Fetching phases for project with id: $projectId");
        $response = $this->client->get("/api/projects/{$projectId}/phases");
        $phases = json_decode($response->getBody()->getContents(), true);
        return response()->json($phases);
    }

    public function show($projectId, $phaseId)
    {
        try {
            $response = $this->client->get("/api/projects/{$projectId}/phases/{$phaseId}");
            $project = json_decode($response->getBody()->getContents(), true);

            return response()->json($project);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Project not found'], 404);
        }
    }

    public function store($projectId, Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate')
        ];

        try {
            $response = $this->client->post("/api/projects/{$projectId}/phases", [
                'json' => $data
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($projectId, $phaseId)
    {
        try {
            $response = $this->client->delete("/api/projects/{$projectId}/phases/{$phaseId}");

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($projectId, $phaseId, Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate')
        ];

        try {
            $response = $this->client->put("/api/projects/{$projectId}/phases/{$phaseId}", [
                'json' => $data
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


