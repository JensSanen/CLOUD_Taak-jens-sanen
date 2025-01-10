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
                totalPrice = request.quantity * request.pricePerUnit
                response = calculatie_pb2.CalculatePriceResponse(
                    projectId=request.projectId,
                    articleId=request.articleId,
                    totalPrice=totalPrice
                )
                cursor.execute("INSERT INTO calculations (projectId, articleId, description, measurementType, measurementUnit, quantity, pricePerUnit, totalPrice) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", (request.projectId, request.articleId, request.description, request.measurementType, request.measurementUnit, request.quantity, request.pricePerUnit, totalPrice))
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
            cursor.execute("SELECT * FROM calculations WHERE projectId = %s", (request.projectId))
            calculations = cursor.fetchall()
            for calculation in calculations:
                yield calculatie_pb2.GetProjectCalculationsResponse(
                    calculationId=calculation['calculationId'],
                    projectId=calculation['projectId'],
                    articleId=calculation['articleId'],
                    description=calculation['description'],
                    measurementType=calculation['measurementType'],
                    measurementUnit=calculation['measurementUnit'],
                    quantity=calculation['quantity'],
                    pricePerUnit=calculation['pricePerUnit'],
                    totalPrice=calculation['totalPrice']
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
