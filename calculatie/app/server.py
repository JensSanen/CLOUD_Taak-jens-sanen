import grpc
from concurrent import futures
import time
import calculatie_pb2
import calculatie_pb2_grpc

# Implementeer de service door de gRPC-klasse te erven
class CalculationService(calculatie_pb2_grpc.CalculationServiceServicer):
    def CalculateProject(self, request_iterator, context):
        for request in request_iterator:
            # Bereken de totale prijs
            total_price = request.quantity * request.price_per_unit
            
            # Maak de respons
            response = calculatie_pb2.CalculatePriceResponse()
            response.building_specification_id = request.building_specification_id
            response.total_price = total_price
            
            # Stuur het antwoord naar de client
            yield response

# Maak en start de gRPC-server
def serve():
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    calculatie_pb2_grpc.add_CalculationServiceServicer_to_server(CalculationService(), server)
    
    # Start de server op poort 50051
    server.add_insecure_port('[::]:50051')
    print("Server gestart op poort 50051...")
    server.start()
    server.wait_for_termination()

if __name__ == '__main__':
    serve()
