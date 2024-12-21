<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ProjectController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'werfplanning_api:5000']);
    }

    public function index(Request $request)
    {
        $response = $this->client->get('/api/projects', [
            'query' => ['pwd' => 'mypassword']
        ]);

        $projects = json_decode($response->getBody()->getContents(), true);
        return response()->json($projects);
    }

    // DELETE project
    public function destroy($id)
    {
        try {
            $response = $this->client->delete("/api/projects/{$id}");

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

