<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class CalculationController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'calculatie_rest_layer:5000']);
    }

    public function index($projectId)
    {
        log::info("Fetching calculations for project with id: $projectId");

        try {
            $response = $this->client->get("/api/projects/{$projectId}/calculations");
            $calculations = json_decode($response->getBody()->getContents(), true);
            // Log::info("Calculations fetched successfully: " . json_encode($calculations));
            return response()->json($calculations);
        } catch (\Exception $e) {
            Log::error("Calculations not found: " . $e->getMessage());
            return response()->json(['error' => 'Calculations not found'], 404);
        }
    }

    public function show($calculationId)
    {
        log::info("Fetching calculation with id: $calculationId");

        try {
            $response = $this->client->get("/api/calculations/{$calculationId}");
            $calculation = json_decode($response->getBody()->getContents(), true);
            // Log::info("Calculation fetched successfully: " . json_encode($calculation));
            return response()->json($calculation);
        } catch (\Exception $e) {
            Log::error("Calculation not found: " . $e->getMessage());
            return response()->json(['error' => 'Calculation not found'], 404);
        }
    }

    public function store($projectId, Request $request)
    {
        Log::info("Storing calculation for project with id: $projectId");

        $data = $request->all();

        log::info("Data to be stored: " . json_encode($data));

        try {
            $response = $this->client->post("/api/projects/{$projectId}/calculations", [
                'json' => $data
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($calculationId, Request $request)
    {
        Log::info("Updating calculation with id: $calculationId");

        $data = $request->all();

        log::info("Data to be updated: " . json_encode($data));

        try {
            $response = $this->client->put("/api/calculations/{$calculationId}", [
                'json' => $data
            ]);

            Log::info("Calculation updated successfully: " . json_encode($response->getBody()));

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($calculationId)
    {
        Log::info("Deleting calculation with id: $calculationId");

        try {
            $response = $this->client->delete("/api/calculations/{$calculationId}");

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


