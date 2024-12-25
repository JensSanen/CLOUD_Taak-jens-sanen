package com.jens.taak.facturatie;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
public class ProducingWebServiceApplication {

	public static void main(String[] args) {
		SpringApplication.run(ProducingWebServiceApplication.class, args);
	}
}

// To start the application:
// ./mvnw spring-boot:run
//
// To test the application:
// $response = Invoke-WebRequest -Uri "http://localhost:8080/ws" -Method Post -Body (Get-Content -Raw -Path "request.xml") -ContentType "text/xml"
// $response.Content