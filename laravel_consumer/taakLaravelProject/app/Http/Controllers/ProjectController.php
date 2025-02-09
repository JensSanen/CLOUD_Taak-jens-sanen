<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ProjectController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'werfplanning_api2:8080']);
    }

    public function index()
    {
        $response = $this->client->get('/api/projects');
        $projects = json_decode($response->getBody()->getContents(), true);
        return response()->json($projects);
    }

    public function show($projectId)
    {
        try {
            $response = $this->client->get("/api/projects/{$projectId}");
            $project = json_decode($response->getBody()->getContents(), true);

            return response()->json($project);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Project not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'location' => $request->input('location'),
            'status' => $request->input('status')
        ];

        Log::info('Creating project with data: ' . json_encode($data));

        try {
            $response = $this->client->post('/api/projects', [
                'json' => $data
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($projectId)
    {
        Log::info("Deleting project with id: $projectId");
        try {
            $response = $this->client->delete("/api/projects/{$projectId}");

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($projectId, Request $request)
    {
        $data = [
            'projectId' => $projectId,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'location' => $request->input('location'),
            'status' => $request->input('status')
        ];

        Log::info("Updating project with id: $projectId and data: " . json_encode($data));

        try {
            $response = $this->client->put("/api/projects/{$projectId}", [
                'json' => $data
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


