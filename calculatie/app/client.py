import grpc
import calculatie_pb2
import calculatie_pb2_grpc

def run():
    # Maak verbinding met de server
    channel = grpc.insecure_channel('localhost:30002')
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)
    
    # Maak een lijst van requests die naar de server worden gestuurd
    requests = [
        calculatie_pb2.CalculatePriceRequest(
            building_specification_id="ID_001", name="Project A", unit="m2", quantity=100, price_per_unit=50),
        calculatie_pb2.CalculatePriceRequest(
            building_specification_id="ID_002", name="Project B", unit="m2", quantity=200, price_per_unit=40),
        calculatie_pb2.CalculatePriceRequest(
            building_specification_id="ID_003", name="Project C", unit="m2", quantity=150, price_per_unit=60)
    ]
    
    # Roep de gRPC-methode aan met de lijst van berekeningen
    response_iterator = stub.CalculateProject(iter(requests))
    
    # Ontvang de resultaten van de server
    for response in response_iterator:
        print(f"Building Specification ID: {response.building_specification_id}, Total Price: {response.total_price}")

if __name__ == '__main__':
    run()
