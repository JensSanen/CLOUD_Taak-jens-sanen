import grpc
import calculatie_pb2
import calculatie_pb2_grpc

channel = grpc.insecure_channel('localhost:30002')

def run_calculation():
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)

    # Voorbeelden van requests
    requests = [
        calculatie_pb2.CalculatePriceRequest(
            project_id=1,
            article_id=101,
            description="Staalbalk",
            measurement_type="FH",
            measurement_unit="m",
            quantity=10,
            price_per_unit=50.0
        ),
        calculatie_pb2.CalculatePriceRequest(
            project_id=1,
            article_id=102,
            description="Betonplaat",
            measurement_type="FH",
            measurement_unit="m²",
            quantity=20,
            price_per_unit=80.0
        )
    ]

    try:
        responses = stub.CalculateProject(iter(requests))
        for response in responses:
            print(f"Project {response.project_id} - Artikel {response.article_id}: Totale prijs = {response.total_price}")
    except grpc.RpcError as e:
        print(f"Fout tijdens berekening: {e.details()} (Statuscode: {e.code()})")

def run_get_project_calculations(project_id):
    stub = calculatie_pb2_grpc.ProjectCalculationServiceStub(channel)

    request = calculatie_pb2.GetProjectCalculationsRequest(project_id=project_id)

    try:
        responses = stub.GetProjectCalculations(request)
        print(f"Berekeningen voor project {project_id}:")
        for response in responses:
            print(f"Artikel {response.article_id}: {response.description}, {response.quantity} {response.measurement_unit} à {response.price_per_unit}, Totale prijs = {response.total_price}")
    except grpc.RpcError as e:
        print(f"Fout bij ophalen berekeningen: {e.details()} (Statuscode: {e.code()})")

if __name__ == '__main__':
    print("Start berekening...")
    run_calculation()

    print("\nHaal berekeningen op voor project 1...")
    run_get_project_calculations(project_id=1)
