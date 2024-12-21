<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FlaskUserController extends Controller
{
    // Definieer een Client object dat we binnen de controller kunnen herbruiken
    protected $client;

    public function __construct()
    {
        // uri is de flask service uit onze docker-compose
        $this->client = new Client(['base_uri' => 'werfplanning_api:5000']);
    }

    // GET all users
    public function index(Request $request)
    {
        $response = $this->client->get('/api/projects', [
            'query' => ['pwd' => 'mypassword']
        ]);
        // error_log($response);
        $users = json_decode($response->getBody()->getContents(), true);
        return response()->json($users);
    }

    // GET single user
    public function show($id)
    {
        try {
            $response = $this->client->get("/api/users/{$id}");
            $user = json_decode($response->getBody()->getContents(), true);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // POST create user
    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        try {
            $response = $this->client->post('/api/users', [
                'json' => $data
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // DELETE user
    public function destroy($id)
    {
        try {
            $response = $this->client->delete("/api/users/{$id}");

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
