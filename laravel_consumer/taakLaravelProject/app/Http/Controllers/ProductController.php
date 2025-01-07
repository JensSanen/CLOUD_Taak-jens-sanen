<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'stockbeheer_api:30005']);
    }

    public function index()
    {
        Log::info("Fetching products via GraphQL");
        $query = <<<GQL
        query {
            products {
                productId
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
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json($data['data']['products']);

        } catch (\Exception $e) {
            Log::error("Failed to fetch products: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch workers'], 500);
        }
    }

    public function show($productId)
    {
        Log::info("Fetching product with productId: $productId via GraphQL");
        $query = <<<GQL
        query {
            product(productId: $productId) {
                productId
                name
                description
                price
                quantity
                supplier {
                    supplierId
                    name
                }
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
        GQL;

        try {
            $response = $this->client->post('/graphql', [
                'json' => ['query' => $query]
            ]);
            $response_body = $response->getBody()->getContents();
            Log::info($response_body);
            $data = json_decode($response_body, true);
            Log::info($data);
            return response()->json($data['data']['product'], 200);

        } catch (\Exception $e) {
            Log::error("Failed to fetch product with productId: $productId: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product'], 500);
        }
    }

    public function showSupplier($productId)
    {
        Log::info("Fetching supplier of product with productId: $productId via GraphQL");
        $query = <<<GQL
        query {
            product(productId: $productId) {
                supplier {
                    supplierId
                    name
                    address
                    email
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
            return response()->json($data['data']['product']["supplier"], 200);

        } catch (\Exception $e) {
            Log::error("Failed to fetch supplier of product with productId: $productId: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch supplier of product'], 500);
        }
    }

    public function update(Request $request, $productId)
    {
        Log::info("Updating product with id: $productId");
        Log::info($request);
        $query = <<<GQL
        mutation {
            updateProduct(
                productId: $productId,
                name: "{$request->name}",
                description: "{$request->description}",
                price: {$request->price},
                quantity: {$request->quantity},
                supplierId: {$request->supplierId},
                locationId: {$request->locationId}
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
            return response()->json([
                'message' => $data['data']['updateProduct']['name'] . ' successfully updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($productId)
    {
        Log::info("Deleting product with id: $productId");
        $query = <<<GQL
        mutation {
            deleteProduct(productId: $productId) {
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
            return response()->json([
                'message' => $data['data']['deleteProduct']['name'] . ' successfully deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store (Request $request)
    {
        Log::info("Creating new product");
        $query = <<<GQL
        mutation {
            addProduct(
                name: "{$request->name}",
                description: "{$request->description}",
                price: {$request->price},
                quantity: {$request->quantity},
                supplierId: {$request->supplierId},
                locationId: {$request->locationId}
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
            return response()->json([
                'message' => $data['data']['addProduct']['name'] . ' succesvol aangemaakt!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
