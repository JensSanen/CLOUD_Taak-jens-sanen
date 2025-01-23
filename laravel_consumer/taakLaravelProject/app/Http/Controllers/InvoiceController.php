<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class InvoiceController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'facturatie-api:8080',
            'headers' => [
                'Content-Type' => 'text/xml',
            ],
        ]);
    }

    private function xmlToJson($xml)
    {
        Log::info("Converting XML to JSON");
        Log::info("XML: " . $xml);

        $xml = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);

        Log::info("XML: " . $xml->asXML());

        $jsonArray = json_decode(json_encode($xml), true);

        Log::info("JSON: " . json_encode($jsonArray, JSON_PRETTY_PRINT));

        $json = json_encode($jsonArray, JSON_PRETTY_PRINT);

        return $json;
    }


    public function index($projectId)
    {
        Log::info("Fetching invoice for project with id: $projectId");

        // SOAP Request XML
        $soapRequest = <<<XML
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:gs="http://com.jens.taak/facturatie">
                <soapenv:Header/>
                <soapenv:Body>
                    <gs:getInvoiceRequest>
                        <gs:projectId>{$projectId}</gs:projectId>
                    </gs:getInvoiceRequest>
                </soapenv:Body>
            </soapenv:Envelope>
        XML;

        Log::info("SOAP Request: " . $soapRequest);

        try {
            // Stuur het SOAP-request
            $response = $this->client->post('/api/invoice', [
                'body' => $soapRequest,
            ]);

            // Lees en decodeer de SOAP-response
            $responseBody = $response->getBody()->getContents();

            Log::info("SOAP Response: " . $responseBody);

            // Gebruik simplexml_load_string en registreer namespaces
            $xml = simplexml_load_string($responseBody);

            if ($xml === false) {
                Log::error("Failed to load XML.");
                return response()->json(['error' => 'Failed to load XML'], 500);
            }

            // Registreren van namespaces
            $namespaces = $xml->getNamespaces(true);
            foreach ($namespaces as $prefix => $namespace) {
                $xml->registerXPathNamespace($prefix, $namespace);
            }

            // Log de XML na verwerking om te controleren of deze correct is geladen
            Log::info("Processed XML: " . $xml->asXML());

            // // Convert XML to JSON
            // $invoice = $this->xmlToJson($xml->asXML());

            // Log::info("Returning JSON: " . $invoice);

            return response($xml->asXML(), 200)->header('Content-Type', 'text/xml');
        } catch (\Exception $e) {
            Log::error("Invoice not found: " . $e->getMessage());
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    }

}
