import grpc
from concurrent import futures
import time
import calculatie_pb2
import calculatie_pb2_grpc
import pymysql
import os

def get_db_connection():
    return pymysql.connect( 
        host=os.getenv('DB_HOST', 'calculatie_db'),
        user=os.getenv('DB_USER', 'calculatie'),
        password=os.getenv('DB_PASSWORD', 'calculatiePwd'),
        database=os.getenv('DB_NAME', 'calculatieAPI'),
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )

# Implementatie van de CalculationService
class CalculationService(calculatie_pb2_grpc.CalculationServiceServicer):
    def CalculateProject(self, request_iterator, context):
        try:
            db = get_db_connection()
            cursor = db.cursor(pymysql.cursors.DictCursor)
            for request in request_iterator:
                total_price = request.quantity * request.price_per_unit
                response = calculatie_pb2.CalculatePriceResponse(
                    project_id=request.project_id,
                    article_id=request.article_id,
                    total_price=total_price
                )
                cursor.execute("INSERT INTO calculations (project_id, article_id, description, measurement_type, measurement_unit, quantity, price_per_unit, total_price) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", (request.project_id, request.article_id, request.description, request.measurement_type, request.measurement_unit, request.quantity, request.price_per_unit, total_price))
                yield response
            db.commit()
        except Exception as e:
            context.set_details(str(e))
            context.set_code(grpc.StatusCode.INTERNAL)
        finally:
            cursor.close()
            db.close()

class ProjectCalculationService(calculatie_pb2_grpc.ProjectCalculationServiceServicer):
    def GetProjectCalculations(self, request, context):
        db = get_db_connection()
        cursor = db.cursor(pymysql.cursors.DictCursor)
        try:
            cursor.execute("SELECT * FROM calculations WHERE project_id = %s", (request.project_id))
            calculations = cursor.fetchall()
            for calculation in calculations:
                yield calculatie_pb2.GetProjectCalculationsResponse(
                    project_id=calculation['project_id'],
                    article_id=calculation['article_id'],
                    description=calculation['description'],
                    measurement_type=calculation['measurement_type'],
                    measurement_unit=calculation['measurement_unit'],
                    quantity=calculation['quantity'],
                    price_per_unit=calculation['price_per_unit'],
                    total_price=calculation['total_price']
                )
        except Exception as e:
            context.set_details(str(e))
            context.set_code(grpc.StatusCode.INTERNAL)
        finally:
            cursor.close()
            db.close()

def serve():
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    calculatie_pb2_grpc.add_CalculationServiceServicer_to_server(CalculationService(), server)
    calculatie_pb2_grpc.add_ProjectCalculationServiceServicer_to_server(ProjectCalculationService(), server)

    server.add_insecure_port('[::]:50051')
    print("Server draait op poort 50051...")
    server.start()

    try:
        while True:
            time.sleep(86400)  # Laat de server draaien
    except KeyboardInterrupt:
        server.stop(0)

if __name__ == '__main__':
    serve()
