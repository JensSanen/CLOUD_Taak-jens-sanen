<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'stockbeheer_api:30005']);
    }


    // Functie om alle leveranciers op te halen
    public function index()
    {
        Log::info("Fetching supplier via GraphQL");
        $query = <<<GQL
        query {
            suppliers {
                supplierId
                name
                email
                address
                products {
                    name
                    description
                    price
                    quantity
                    location {
                        locationId
                        row
                        rack {
                            rackId
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
            return response()->json($data['data']['suppliers']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch suppliers: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch suppliers'], 500);
        }
    }

    // Functie om alle leverancier namen op te halen -> gebruikt in dropdown voor aanmaken / updaten product
    public function indexNames()
    {
        Log::info("Fetching supplier names via GraphQL");
        $query = <<<GQL
        query {
            suppliers {
                supplierId
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
            return response()->json($data['data']['suppliers']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch products: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch workers'], 500);
        }
    }


    // Functie om alle producten van een leverancier op te halen
    public function indexProducts($supplierId)
    {
        Log::info("Fetching products from supplier with supplierId: $supplierId via GraphQL");
        $query = <<<GQL
        query {
            supplier(supplierId: $supplierId) {
                products {
                    name
                    description
                    price
                    quantity
                    location {
                        row
                        rack {
                            name
                        }
                    }
                    supplier {
                        name
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
            return response()->json($data['data']['supplier']['products']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch products: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch workers'], 500);
        }
    }


    // Functie om een leverancier op te halen
    public function show($supplierId)
    {
        Log::info("Fetching supplier with supplierId: $supplierId via GraphQL");
        $query = <<<GQL
        query {
            supplier(supplierId: $supplierId) {
                supplierId
                name
                email
                address
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
            return response()->json($data['data']['supplier']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch supplier: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch supplier'], 500);
        }
    }


    // Functie om een leverancier te updaten
    public function update(Request $request, $supplierId)
    {
        Log::info("Updating supplier with supplierId: $supplierId");
        $query = <<<GQL
        mutation {
            updateSupplier(
                supplierId: $supplierId,
                name: "{$request->name}",
                email: "{$request->email}",
                address: "{$request->address}"
            ) {
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
            return response()->json(['message' => $data['data']['updateSupplier']['name'] . ' succesvol geupdatet'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Functie om een leverancier te verwijderen
    public function destroy($supplierId)
    {
        Log::info("Deleting supplier with supplierId: $supplierId");
        $query = <<<GQL
        mutation {
            deleteSupplier(supplierId: $supplierId) {
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
            return response()->json(['message' => $data['data']['deleteSupplier']['name'] . ' succesvol verwijderd'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Functie om een leverancier aan te maken
    public function store(Request $request)
    {
        Log::info("Storing supplier");
        $query = <<<GQL
        mutation {
            addSupplier(
                name: "{$request->name}",
                email: "{$request->email}",
                address: "{$request->address}"
            ) {
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
            return response()->json(['message' => $data['data']['addSupplier']['name'] . ' succesvol aangemaakt'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
