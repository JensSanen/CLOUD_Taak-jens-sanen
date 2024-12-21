<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FlaskProjectController extends Controller
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
}

