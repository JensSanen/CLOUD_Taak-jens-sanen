package com.jens.taak.facturatie;

import io.grpc.ManagedChannel;
import io.grpc.ManagedChannelBuilder;
import calculatie.CalculationServiceGrpc;
import calculatie.Calculatie.GetProjectCalculationsRequest;
import calculatie.Calculatie.GetCalculationResponse;

import java.util.ArrayList;
import java.util.List;

public class GrpcClient {
    private final ManagedChannel channel;
    private final CalculationServiceGrpc.CalculationServiceBlockingStub blockingStub;

    public GrpcClient(String host, int port) {
        // Maak verbinding met de gRPC server
        channel = ManagedChannelBuilder.forAddress(host, port)
                .usePlaintext()
                .build();
        blockingStub = CalculationServiceGrpc.newBlockingStub(channel);
    }

    public List<GetCalculationResponse> getProjectCalculations(int projectId) {
        // Maak een request aan de server
        GetProjectCalculationsRequest request = GetProjectCalculationsRequest.newBuilder()
                .setProjectId(projectId)
                .build();

        // Vraag de gegevens op via de blocking stub
        List<GetCalculationResponse> responses = new ArrayList<>();
        blockingStub.getProjectCalculations(request).forEachRemaining(responses::add);

        return responses;
    }

    public void shutdown() {
        channel.shutdownNow();
    }
}