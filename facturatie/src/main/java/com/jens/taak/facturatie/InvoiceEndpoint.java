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
import calculatie.Calculatie.GetCalculationResponse;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

@Endpoint
public class InvoiceEndpoint {

    private static final String NAMESPACE_URI = "http://com.jens.taak/facturatie";
    private static final String API_PROJECT_URL = "http://host.docker.internal:30007/api/projects/";
    private static final String API_WORKERS_URL = "http://host.docker.internal:30008/api/projects/";

    private final RestTemplate restTemplate;
    private final ObjectMapper jsonMapper;
    private final GrpcClient grpcClient;

    private static final Logger logger = LoggerFactory.getLogger(InvoiceEndpoint.class);

    @Autowired
    public InvoiceEndpoint(RestTemplate restTemplate) {
        this.restTemplate = restTemplate;
        this.jsonMapper = new ObjectMapper();
        this.grpcClient = new GrpcClient("host.docker.internal", 30002);
    }

    @PayloadRoot(namespace = NAMESPACE_URI, localPart = "getInvoiceRequest")
    @ResponsePayload
    public GetInvoiceResponse getInvoice(@RequestPayload GetInvoiceRequest request) {
        logger.info("Received SOAP request: {}", request);
        
        try {
            logger.info("Creating invoice for project with ID: {}", request.getProjectId());
            int projectId = request.getProjectId();
            // Maak een Invoice object aan
            Invoice invoice = createInvoice(projectId);
            GetInvoiceResponse response = new GetInvoiceResponse();
            response.setInvoice(invoice);
            logger.info("Invoice successfully created for project ID: {}", projectId);
            // Return de response
            return response;
        } catch (Exception e) {
            logger.error("Error while processing invoice request: {}", e.getMessage(), e);
            throw new RuntimeException("Failed to generate invoice", e);
        }
    }

    // Functie om een Project object te maken op basis van de projectId door de project data op te halen via de REST API
    private Project createProject(int projectId) throws Exception {
        logger.info("Fetching project details for project ID: {}", projectId);
        
        // Maak de URL's voor het ophalen van de project en de fases
        String project_URL = UriComponentsBuilder.fromUriString(API_PROJECT_URL + projectId).toUriString();
        String phases_URL = UriComponentsBuilder.fromUriString(project_URL + "/phases").toUriString();
        
        try {
            // Haal de JSON data op van de project en de fases
            String jsonResponseProject = restTemplate.getForObject(project_URL, String.class);
            String jsonResponsePhases = restTemplate.getForObject(phases_URL, String.class);
            
            // Maak een Project object aan en vul deze met de project en de fases
            Project project = jsonMapper.readValue(jsonResponseProject, Project.class);
            Phase[] phasesArray = jsonMapper.readValue(jsonResponsePhases, Phase[].class);
            List<Phase> phaseList = Arrays.asList(phasesArray);
            project.getPhases().addAll(phaseList);
            
            logger.info("Successfully fetched project and phases for project ID: {}", projectId);
            return project;
        } catch (Exception e) {
            logger.error("Error fetching project data for project ID {}: {}", projectId, e.getMessage(), e);
            throw e;
        }
    }

    // Functie om een List van Worker objecten te maken op basis van de projectId door de workers data op te halen via de REST API
    private List<Worker> createWorkers(int projectId) throws Exception {
        logger.info("Fetching workers for project ID: {}", projectId);
        
        // Maak de URL voor het ophalen van de workers
        String workers_URL = UriComponentsBuilder.fromUriString(API_WORKERS_URL + projectId + "/workedHours").toUriString();
        
        try {
            // Haal de JSON data op van de workers
            String jsonResponseWorkers = restTemplate.getForObject(workers_URL, String.class);

            // Maak een List van Worker objecten aan en vul deze met de workers
            Worker[] workersArray = jsonMapper.readValue(jsonResponseWorkers, Worker[].class);
            logger.info("Successfully fetched {} workers for project ID: {}", workersArray.length, projectId);
            return Arrays.asList(workersArray);
        } catch (Exception e) {
            logger.error("Error fetching workers for project ID {}: {}", projectId, e.getMessage(), e);
            throw e;
        }
    }

    // Functie om een List van Calculation objecten te maken op basis van de projectId door de calculations data op te halen via de gRPC client
    private List<Calculation> fetchCalculations(int projectId) throws Exception {
        logger.info("Fetching calculations for project ID: {}", projectId);
        
        try {
            // Haal de calculations data op via de gRPC client
            List<GetCalculationResponse> grpcCalculations = grpcClient.getProjectCalculations(projectId);
            // Maak een List van Calculation objecten aan en vul deze met de calculations
            List<Calculation> calculations = grpcCalculations.stream().map(calc -> {
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
            
            logger.info("Successfully fetched {} calculations for project ID: {}", calculations.size(), projectId);
            return calculations;
        } catch (Exception e) {
            logger.error("Error fetching calculations for project ID {}: {}", projectId, e.getMessage(), e);
            throw e;
        }
    }

    // Functie om een Invoice object te maken op basis van de projectId
    private Invoice createInvoice(int projectId) throws Exception {
        logger.info("Creating invoice object for project ID: {}", projectId);
        
        // Maak een Project object aan
        Project project = createProject(projectId);
        // Maak een List van Worker objecten aan
        List<Worker> workers = createWorkers(projectId);
        // Maak een List van Calculation objecten aan
        List<Calculation> calculations = fetchCalculations(projectId);

        // Maak een Invoice object aan en vul deze met de Project, Workers en Calculations
        Invoice invoice = new Invoice();
        invoice.setProject(project);
        invoice.getWorkers().addAll(workers);
        invoice.getCalculations().addAll(calculations);
        
        logger.info("Invoice object successfully created for project ID: {}", projectId);
        return invoice;
    }
}
