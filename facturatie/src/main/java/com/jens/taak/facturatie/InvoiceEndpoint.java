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

import java.util.Arrays;
import java.util.List;

import taak.jens.com.facturatie.GetInvoiceRequest;
import taak.jens.com.facturatie.GetInvoiceResponse;
import taak.jens.com.facturatie.Invoice;
import taak.jens.com.facturatie.Project;
import taak.jens.com.facturatie.Phase;

@Endpoint
public class InvoiceEndpoint {

    private static final String NAMESPACE_URI = "http://com.jens.taak/facturatie";
    private static final String API_PROJECT_URL = "http://localhost:30001/api/project/";

    private RestTemplate restTemplate;
    private ObjectMapper jsonMapper;

    @Autowired
    public InvoiceEndpoint(RestTemplate restTemplate) {
        this.restTemplate = restTemplate;
        this.jsonMapper = new ObjectMapper();
    }

    @PayloadRoot(namespace = NAMESPACE_URI, localPart = "getInvoiceRequest")
    @ResponsePayload
    public GetInvoiceResponse getInvoice(@RequestPayload GetInvoiceRequest request) throws Exception {
        int projectId = request.getProjectId();
        Invoice invoice = createInvoice(projectId);
        GetInvoiceResponse response = new GetInvoiceResponse();
        response.setInvoice(invoice);
        return response;
    }

    private Project createProject(int projectId) throws Exception {
        String project_URL = UriComponentsBuilder.fromUriString(API_PROJECT_URL + projectId).toUriString();
        String phases_URL = UriComponentsBuilder.fromUriString(project_URL + "/phases").toUriString(); 

        // Stap 1: JSON ophalen van de API
        String jsonResponseProject = restTemplate.getForObject(project_URL, String.class);
        String jsonResponsePhases = restTemplate.getForObject(phases_URL, String.class);

        // Stap 2: JSON naar objecten converteren 
        Project project = jsonMapper.readValue(jsonResponseProject, Project.class);
        Phase[] phasesArray = jsonMapper.readValue(jsonResponsePhases, Phase[].class);
        List<Phase> phaseList = Arrays.asList(phasesArray);

        // Voeg de nieuwe fases toe aan de bestaande lijst
        project.getPhases().addAll(phaseList);

        return project;
    }

    private Invoice createInvoice(int projectId) throws Exception {
        Project project = createProject(projectId);
        Invoice invoice = new Invoice();
        invoice.setProject(project);
        return invoice;
    }
}

// To run the application, use the following command:
// ./mvnw spring-boot:run

// To test the application, use the following command:
// $response = Invoke-WebRequest -Uri "http://localhost:8080/ws" -Method Post -Body (Get-Content -Raw -Path "request.xml") -ContentType "text/xml"
// $response.Content

