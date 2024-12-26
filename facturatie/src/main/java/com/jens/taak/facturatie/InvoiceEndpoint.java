package com.jens.taak.facturatie;

import com.fasterxml.jackson.databind.ObjectMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.client.RestTemplate;
import org.springframework.web.util.UriComponentsBuilder;
import org.springframework.ws.server.endpoint.annotation.Endpoint;
import org.springframework.ws.server.endpoint.annotation.PayloadRoot;
import org.springframework.ws.server.endpoint.annotation.RequestPayload;
import org.springframework.ws.server.endpoint.annotation.ResponsePayload;

import java.util.Arrays;
import java.util.List;
import java.util.stream.Collectors;

import taak.jens.com.facturatie.GetInvoiceRequest;
import taak.jens.com.facturatie.GetInvoiceResponse;
import taak.jens.com.facturatie.Invoice;
import taak.jens.com.facturatie.Project;
import taak.jens.com.facturatie.Phase;
import taak.jens.com.facturatie.Worker;
import taak.jens.com.facturatie.Calculation;
import calculatie.Calculatie.GetProjectCalculationsResponse;

@Endpoint
public class InvoiceEndpoint {

    private static final String NAMESPACE_URI = "http://com.jens.taak/facturatie";
    private static final String API_PROJECT_URL = "http://localhost:30001/api/project/";
    private static final String API_WORKERS_URL = "http://localhost:30003/api/worked_hours/project/";

    private final RestTemplate restTemplate;
    private final ObjectMapper jsonMapper;
    private final GrpcClient grpcClient;

    @Autowired
    public InvoiceEndpoint(RestTemplate restTemplate) {
        this.restTemplate = restTemplate;
        this.jsonMapper = new ObjectMapper();
        this.grpcClient = new GrpcClient("localhost", 30002);
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

    private List<Worker> createWorkers(int projectId) throws Exception {
        String workers_URL = UriComponentsBuilder.fromUriString(API_WORKERS_URL + projectId).toUriString();

        // Stap 1: JSON ophalen van de API
        String jsonResponseWorkers = restTemplate.getForObject(workers_URL, String.class);

        // Stap 2: JSON naar objecten converteren 
        Worker[] workersArray = jsonMapper.readValue(jsonResponseWorkers, Worker[].class);
        return Arrays.asList(workersArray);
    }

    private List<Calculation> fetchCalculations(int projectId) throws Exception {
        // Haal berekeningen op via gRPC
        List<GetProjectCalculationsResponse> grpcCalculations = grpcClient.getProjectCalculations(projectId);

        // Zet de gRPC-responses om naar Calculation-objecten
        return grpcCalculations.stream().map(calc -> {
            Calculation calculation = new Calculation();
            calculation.setProjectId(projectId);
            calculation.setArticleId(calc.getArticleId());
            calculation.setDescription(calc.getDescription());
            calculation.setMeasurementType(calc.getMeasurementType());
            calculation.setMeasurementUnit(calc.getMeasurementUnit());
            calculation.setQuantity(calc.getQuantity());
            calculation.setPricePerUnit(calc.getPricePerUnit());
            calculation.setTotalPrice(calc.getTotalPrice());
            return calculation;
        }).collect(Collectors.toList());
    }

    private Invoice createInvoice(int projectId) throws Exception {
        Project project = createProject(projectId);
        List<Worker> workers = createWorkers(projectId);
        List<Calculation> calculations = fetchCalculations(projectId);

        // Voeg gegevens samen in een Invoice
        Invoice invoice = new Invoice();
        invoice.getWorkers().addAll(workers);
        invoice.setProject(project);
        invoice.getCalculations().addAll(calculations);

        return invoice;
    }
}

// To run the application, use the following command:
// ./mvnw spring-boot:run

// To test the application, use the following command:
// $response = Invoke-WebRequest -Uri "http://localhost:8080/ws" -Method Post -Body (Get-Content -Raw -Path "request.xml") -ContentType "text/xml"
// $response.Content