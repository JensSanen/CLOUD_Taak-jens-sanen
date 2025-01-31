<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RackController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'stockbeheer_api:30005']);
    }

    // Functie om alle racks op te halen
    public function index()
    {
        Log::info("Fetching racks via GraphQL");
        $query = <<<GQL
        query {
            racks {
                rackId
                name
                rows
                products {
                    name
                }
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json($data['data']['racks']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch racks: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch racks'], 500);
        }
    }

    // Functie om alle rack namen op te halen -> gebruikt in dropdown voor aanmaken / updaten product
    public function indexNames()
    {
        Log::info("Fetching rack names via GraphQL");
        $query = <<<GQL
        query {
            racks {
                rackId
                name
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json($data['data']['racks']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch racks names: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch racks names'], 500);
        }
    }

    // Functie om alle producten in een rack op te halen
    public function indexProducts($rackId)
    {
        Log::info("Fetching products in rack via GraphQL");
        $query = <<<GQL
        query {
            rack(rackId: $rackId) {
                products {
                    name
                    description
                    price
                    quantity
                    supplier {
                        name
                    }
                    location {
                        row
                        rack {
                            name
                        }
                    }
                }
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json($data['data']['rack']['products']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch racks products: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch racks products'], 500);
        }
    }

    // Functie om alle lege locaties in een rack op te halen -> gebruikt in dropdown voor aanmaken / updaten product
    public function indexEmptyLocations($rackId)
    {
        Log::info("Fetching rack names via GraphQL");
        $query = <<<GQL
        query {
            rack(rackId: $rackId) {
                emptyLocations {
                    locationId
                    row
                }
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json($data['data']['rack']['emptyLocations']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch racks empty locations: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch racks empty locations'], 500);
        }
    }

    // Functie om een rack op te halen
    public function show($rackId)
    {
        Log::info("Fetching rack via GraphQL");
        $query = <<<GQL
        query {
            rack(rackId: $rackId) {
                rackId
                name
                rows
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json($data['data']['rack']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch rack: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch rack'], 500);
        }
    }

    // Functie om een rack op te slaan
    public function store(Request $request)
    {
        Log::info("Storing rack via GraphQL");
        $query = <<<GQL
        mutation {
            addRack(name: "{$request->name}", rows: {$request->rows}) {
                name
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json(['message' => $data['data']['addRack']['name'] . ' rack stored successfully'], 200);

        } catch (\Exception $e) {
            Log::error("Failed to store rack: " . $e->getMessage());
            return response()->json(['error' => 'Failed to store rack'], 500);
        }
    }

    // Functie om een rack te verwijderen
    public function destroy($rackId)
    {
        Log::info("Deleting rack via GraphQL");
        $query = <<<GQL
        mutation {
            deleteRack(rackId: $rackId) {
                name
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json(['message' => $data['data']['deleteRack']['name'] . ' rack deleted successfully'], 200);

        } catch (\Exception $e) {
            Log::error("Failed to delete rack: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete rack'], 500);
        }
    }

    // Functie om een rack te updaten
    public function update(Request $request, $rackId)
    {
        Log::info("Updating rack via GraphQL");
        $query = <<<GQL
        mutation {
            updateRack(rackId: $rackId, name: "{$request->name}", rows: {$request->rows}) {
                name
            }
        }
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json(['message' => $data['data']['updateRack']['name'] . ' rack updated successfully'], 200);

        } catch (\Exception $e) {
            Log::error("Failed to update rack: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update rack'], 500);
        }
    }
}
