from flask import Flask, render_template_string, request, jsonify
from flask_cors import CORS
import grpc
import calculatie_pb2_grpc
import calculatie_pb2


# Maak een gRPC kanaal voor communicatie
channel = grpc.insecure_channel('calculatie_api:50051')

# Maak een Flask-app
app = Flask(__name__)
CORS(app)

# Functie om berekeningen te starten
def run_calculation(projectId, request_data):
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)

    # Voorbereiden van de berekeningsaanvragen uit de ontvangen gegevens
    requests = []
    for item in request_data:
        calc_request = calculatie_pb2.CalculatePriceRequest(
            projectId=int(projectId),
            articleId=item['articleId'],
            description=item['description'],
            measurementType=item['measurementType'],
            measurementUnit=item['measurementUnit'],
            quantity=item['quantity'],
            pricePerUnit=item['pricePerUnit']
        )
        requests.append(calc_request)

    try:
        responses = stub.CalculateProject(iter(requests))
        result = []
        for response in responses:
            result.append({
                "articleId": response.articleId,
                "description": response.description
            })
        return result
    except grpc.RpcError as e:
        return {"error": f"Fout tijdens berekening: {e.details()} (Statuscode: {e.code()})"}

# REST API endpoint voor berekening
@app.route('/api/projects/<int:projectId>/calculations', methods=['POST'])
def calculate(projectId):
    request_data = request.get_json()
    print(request_data)

    if not request_data:
        return jsonify({"error": "Geen gegevens ontvangen"}), 400

    result = run_calculation(projectId, request_data)
    return jsonify(result), 201

# Functie om projectberekeningen op te halen
def run_get_project_calculations(projectId):
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)

    request = calculatie_pb2.GetProjectCalculationsRequest(projectId=projectId)

    try:
        responses = stub.GetProjectCalculations(request)
        result = []
        for response in responses:
            result.append({
                "calculationId": response.calculationId,
                "projectId": response.projectId,
                "articleId": response.articleId,
                "description": response.description,
                "measurementType": response.measurementType,
                "measurementUnit": response.measurementUnit,
                "quantity": response.quantity,
                "pricePerUnit": response.pricePerUnit,
                "totalPrice": response.totalPrice
            })
        return result
    except grpc.RpcError as e:
        return {"error": f"Fout bij ophalen berekeningen: {e.details()} (Statuscode: {e.code()})"}

# REST API endpoint voor projectberekeningen
@app.route('/api/projects/<int:projectId>/calculations', methods=['GET'])
def get_project_calculations(projectId):
    result = run_get_project_calculations(projectId)
    return jsonify(result)

def run_get_calculation(calculationId):
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)

    request = calculatie_pb2.GetCalculationRequest(calculationId=calculationId)

    try:
        response = stub.GetCalculation(request)
        result = {
            "calculationId": response.calculationId,
            "projectId": response.projectId,
            "articleId": response.articleId,
            "description": response.description,
            "measurementType": response.measurementType,
            "measurementUnit": response.measurementUnit,
            "quantity": response.quantity,
            "pricePerUnit": response.pricePerUnit,
            "totalPrice": response.totalPrice
        }
        return result
    except grpc.RpcError as e:
        return {"error": f"Fout bij ophalen berekening: {e.details()} (Statuscode: {e.code()})"}
    
@app.route('/api/calculations/<int:calculationId>', methods=['GET'])
def get_calculation(calculationId):
    result = run_get_calculation(calculationId)
    return jsonify(result)

def run_delete_calculation(calculationId):
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)

    request = calculatie_pb2.DeleteCalculationRequest(calculationId=calculationId)

    try:
        response = stub.DeleteCalculation(request)
        result = {
            "articleId": response.articleId,
            "description": response.description
        }
        return result
    except grpc.RpcError as e:
        return {"error": f"Fout bij verwijderen berekening: {e.details()} (Statuscode: {e.code()})"}
    
@app.route('/api/calculations/<int:calculationId>', methods=['DELETE'])
def delete_calculation(calculationId):
    result = run_delete_calculation(calculationId)
    return jsonify(result)

def run_update_calculation(calculationId, request_data):
    stub = calculatie_pb2_grpc.CalculationServiceStub(channel)

    request = calculatie_pb2.UpdateCalculationRequest(
        calculationId=calculationId,
        projectId=request_data['projectId'],
        articleId=request_data['articleId'],
        description=request_data['description'],
        measurementType=request_data['measurementType'],
        measurementUnit=request_data['measurementUnit'],
        quantity=request_data['quantity'],
        pricePerUnit=request_data['pricePerUnit']
    )

    try:
        response = stub.UpdateCalculation(request)
        result = {
            "articleId": response.articleId,
            "description": response.description
        }
        return result
    except grpc.RpcError as e:
        return {"error": f"Fout bij bijwerken berekening: {e.details()} (Statuscode: {e.code()})"}
    
@app.route('/api/calculations/<int:calculationId>', methods=['PUT'])
def update_calculation(calculationId):
    request_data = request.get_json()
    if not request_data:
        return jsonify({"error": "Geen gegevens ontvangen"}), 400

    result = run_update_calculation(calculationId, request_data)
    return jsonify(result)

# Start de Flask-server
if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True)