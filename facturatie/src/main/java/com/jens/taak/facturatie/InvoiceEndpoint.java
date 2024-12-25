package com.jens.taak.facturatie;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.dataformat.xml.XmlMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.client.RestTemplate;
import org.springframework.web.util.UriComponentsBuilder;
import org.springframework.ws.server.endpoint.annotation.Endpoint;
import org.springframework.ws.server.endpoint.annotation.PayloadRoot;
import org.springframework.ws.server.endpoint.annotation.RequestPayload;
import org.springframework.ws.server.endpoint.annotation.ResponsePayload;

import taak.jens.com.facturatie.GetInvoiceRequest;
import taak.jens.com.facturatie.GetInvoiceResponse;
import taak.jens.com.facturatie.Invoice;
import taak.jens.com.facturatie.Project;
import taak.jens.com.facturatie.Phase;

@Endpoint
public class InvoiceEndpoint {

    private static final String NAMESPACE_URI = "http://com.jens.taak/facturatie";
    private static final String API_URL = "http://localhost:30001/api/project/";

    private RestTemplate restTemplate;
    private ObjectMapper jsonMapper;
    private XmlMapper xmlMapper;

    @Autowired
    public InvoiceEndpoint(RestTemplate restTemplate) {
        this.restTemplate = restTemplate;
        this.jsonMapper = new ObjectMapper();
        this.xmlMapper = new XmlMapper();
    }

    @PayloadRoot(namespace = NAMESPACE_URI, localPart = "getInvoiceRequest")
    @ResponsePayload
    public GetInvoiceResponse getInvoice(@RequestPayload GetInvoiceRequest request) throws Exception {
        int projectId = request.getProjectId();
        System.out.println("Project ID: " + projectId);
        String url = UriComponentsBuilder.fromUriString(API_URL + projectId).toUriString();

        // Stap 1: JSON ophalen van de API
        String jsonResponse = restTemplate.getForObject(url, String.class);

        System.out.println("JSON Response: " + jsonResponse);

        // Stap 2: JSON naar objecten converteren (lijst van Invoice-objecten)
        Project project = jsonMapper.readValue(jsonResponse, Project.class);
        // Invoice invoice = jsonMapper.readValue(jsonResponse, Invoice.class);
        Invoice invoice = new Invoice();
        invoice.setProject(project);

        System.out.println("Converted Invoice: " + invoice);

        // Stap 3: XML converteren
        String xmlResponse = xmlMapper.writeValueAsString(invoice);

        System.out.println("Converted XML: " + xmlResponse);

        // Stap 4: Response opbouwen
        GetInvoiceResponse response = new GetInvoiceResponse();
        response.setInvoice(invoice);
        return response;
    }
}

// To run the application, use the following command:
// ./mvnw spring-boot:run

// To test the application, use the following command:
// $response = Invoke-WebRequest -Uri "http://localhost:8080/ws" -Method Post -Body (Get-Content -Raw -Path "request.xml") -ContentType "text/xml"
// $response.Content

