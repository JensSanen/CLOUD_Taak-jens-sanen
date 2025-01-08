import grpc
import calculatie_pb2
import calculatie_pb2_grpc

channel = grpc.insecure_channel('localhost:30002')

def run_calculation():
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)

    # Voorbeelden van requests
    requests = [
        calculatie_pb2.CalculatePriceRequest(
            projectId=1,
            articleId=101,
            description="Staalbalk",
            measurementType="FH",
            measurementUnit="m",
            quantity=10,
            pricePerUnit=50.0
        ),
        calculatie_pb2.CalculatePriceRequest(
            projectId=1,
            articleId=102,
            description="Betonplaat",
            measurementType="FH",
            measurementUnit="m²",
            quantity=20,
            pricePerUnit=80.0
        )
    ]

    try:
        responses = stub.CalculateProject(iter(requests))
        for response in responses:
            print(f"Project {response.projectId} - Artikel {response.articleId}: Totale prijs = {response.totalPrice}")
    except grpc.RpcError as e:
        print(f"Fout tijdens berekening: {e.details()} (Statuscode: {e.code()})")

def run_get_project_calculations(projectId):
    stub = calculatie_pb2_grpc.ProjectCalculationServiceStub(channel)

    request = calculatie_pb2.GetProjectCalculationsRequest(projectId=projectId)

    try:
        responses = stub.GetProjectCalculations(request)
        print(f"Berekeningen voor project {projectId}:")
        for response in responses:
            print(f"Artikel {response.articleId}: {response.description}, {response.quantity} {response.measurementUnit} à {response.pricePerUnit}, Totale prijs = {response.totalPrice}")
        return responses
    except grpc.RpcError as e:
        print(f"Fout bij ophalen berekeningen: {e.details()} (Statuscode: {e.code()})")

if __name__ == '__main__':
    print("Start berekening...")
    run_calculation()

    print("\nHaal berekeningen op voor project 1...")
    run_get_project_calculations(projectId=1)
