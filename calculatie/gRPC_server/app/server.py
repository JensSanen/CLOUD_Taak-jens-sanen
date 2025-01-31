import grpc
from concurrent import futures
import time
import calculatie_pb2 as calculatie_pb2
import calculatie_pb2_grpc as calculatie_pb2_grpc
import pymysql
import os


# Functie om een databaseverbinding te maken
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
    # Service voor het uitvoeren van meetstaatberekeningen
    # Request = CalculatePriceRequest (streaming)
    # Response = ConfirmCalculationResponse (streaming)
    def CalculateProject(self, request_iterator, context):
        try:
            db = get_db_connection()
            cursor = db.cursor(pymysql.cursors.DictCursor)
            # Maak een nieuwe berekening voor elk item in de request_iterator (streaming)
            for request in request_iterator:
                totalPrice = request.quantity * request.pricePerUnit
                response = calculatie_pb2.ConfirmCalculationResponse(
                    articleId=request.articleId,
                    description=request.description,
                )
                cursor.execute("INSERT INTO calculations (projectId, articleId, description, measurementType, measurementUnit, quantity, pricePerUnit, totalPrice) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", (request.projectId, request.articleId, request.description, request.measurementType, request.measurementUnit, request.quantity, request.pricePerUnit, totalPrice))
                context.set_code(grpc.StatusCode.OK)
                yield response
            db.commit()
        except Exception as e:
            context.set_details(str(e))
            context.set_code(grpc.StatusCode.INTERNAL)
        finally:
            cursor.close()
            db.close()

    # Service voor het ophalen van alle berekeningen voor een project
    # Request = GetProjectCalculationsRequest 
    # Response = GetCalculationResponse (streaming)
    def GetProjectCalculations(self, request, context):
        db = get_db_connection()
        cursor = db.cursor(pymysql.cursors.DictCursor)
        try:
            cursor.execute("SELECT * FROM calculations WHERE projectId = %s", (request.projectId))
            calculations = cursor.fetchall()
            for calculation in calculations:
                context.set_code(grpc.StatusCode.OK)
                yield calculatie_pb2.GetCalculationResponse(
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

    # Service voor het ophalen van één berekening
    # Request = GetCalculationRequest
    # Response = GetCalculationResponse
    def GetCalculation(self, request, context):
        db = get_db_connection()
        cursor = db.cursor(pymysql.cursors.DictCursor)
        try:
            cursor.execute("SELECT * FROM calculations WHERE calculationId = %s", (request.calculationId))
            calculation = cursor.fetchone()
            if calculation:
                context.set_code(grpc.StatusCode.OK)
                return calculatie_pb2.GetCalculationResponse(
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
            else:
                context.set_details("Berekening niet gevonden")
                context.set_code(grpc.StatusCode.NOT_FOUND)
        except Exception as e:
            context.set_details(str(e))
            context.set_code(grpc.StatusCode.INTERNAL)
        finally:
            cursor.close()
            db.close()

    # Service voor het verwijderen van een berekening
    # Request = DeleteCalculationRequest
    # Response = ConfirmCalculationResponse
    def DeleteCalculation(self, request, context):
        db = get_db_connection()
        cursor = db.cursor(pymysql.cursors.DictCursor)
        try:
            cursor.execute("SELECT description, articleId FROM calculations WHERE calculationId = %s", (request.calculationId,))
            result = cursor.fetchone()
            if result:
                description = result['description']
                articleId = result['articleId']
                cursor.execute("DELETE FROM calculations WHERE calculationId = %s", (request.calculationId,))
                db.commit()
                # context.set_code(grpc.StatusCode.OK)
                return calculatie_pb2.ConfirmCalculationResponse(
                    articleId=articleId,
                    description=description
                )
            else:
                context.set_details("Berekening niet gevonden")
                context.set_code(grpc.StatusCode.NOT_FOUND)
        except Exception as e:
            context.set_details(str(e))
            context.set_code(grpc.StatusCode.INTERNAL)
        finally:
            cursor.close()
            db.close()

    # Service voor het bijwerken van een berekening
    # Request = UpdateCalculationRequest
    # Response = ConfirmCalculationResponse
    def UpdateCalculation(self, request, context):
        db = get_db_connection()
        cursor = db.cursor(pymysql.cursors.DictCursor)
        try:
            totalPrice = request.quantity * request.pricePerUnit
            cursor.execute("UPDATE calculations SET projectId = %s, articleId = %s, description = %s, measurementType = %s, measurementUnit = %s, quantity = %s, pricePerUnit = %s, totalPrice = %s WHERE calculationId = %s", (request.projectId, request.articleId, request.description, request.measurementType, request.measurementUnit, request.quantity, request.pricePerUnit, totalPrice, request.calculationId))
            db.commit()
            context.set_code(grpc.StatusCode.OK)
            return calculatie_pb2.ConfirmCalculationResponse(
                articleId=request.articleId,
                description=request.description
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
