from flask import Flask, render_template_string, request, jsonify
from flask_cors import CORS
import pymysql
import os

app = Flask(__name__)
CORS(app)


def get_db_connection():
    return pymysql.connect( 
        host=os.getenv('DB_HOST', 'werfplanning_db'),
        user=os.getenv('DB_USER', 'werfplanning'),
        password=os.getenv('DB_PASSWORD', 'werfplanningPwd'),
        database=os.getenv('DB_NAME', 'werfplanningAPI'),
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )


@app.route('/api/projects', methods=['GET'])
def get_projects():
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM projects")
        projects = cursor.fetchall()
        return jsonify(projects), 200

    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()    


@app.route('/api/projects/<int:projectId>', methods=['GET'])
def get_project(projectId):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM projects WHERE projectId = %s", (projectId))
        project = cursor.fetchone()
        return jsonify(project), 200

    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects/<int:projectId>/phases', methods=['GET'])
def get_project_phases(projectId):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM phases where projectId = %s", (projectId))
        users = cursor.fetchall()
        return jsonify(users), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects/<int:projectId>/phases/<int:phaseId>', methods=['GET'])
def get_project_phase(projectId, phaseId):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM phases where projectId = %s AND phaseId = %s", (projectId, phaseId))
        users = cursor.fetchone()
        return jsonify(users), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects/<int:projectId>', methods=['DELETE'])
def delete_project(projectId):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("DELETE FROM projects WHERE projectId = %s", projectId)
        db.commit()
    
        if cursor.rowcount == 0:
            return jsonify({"message":"Project not found"}), 404
        return jsonify({"message":"Project deleted successfully"}), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects/<int:projectId>/phases/<int:phaseId>', methods=['DELETE'])
def delete_project_phase(projectId, phaseId):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("DELETE FROM phases WHERE projectId = %s AND phaseId = %s", (projectId, phaseId))
        db.commit()

        if cursor.rowcount == 0:
            return jsonify({"message":"Phase not found"}), 404
        return jsonify({"message":"Phase deleted successfully"}), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects', methods=['POST'])
def create_project():
    db = get_db_connection()
    data = request.get_json()

    name = data.get('name')
    description = data.get('description')
    location = data.get('location')
    status = data.get('status')

    if not name:
        return jsonify({"error": "Name required"}), 400
    if not location:
        return jsonify({"error": "Location required"}), 400
    if not status:
        return jsonify({"error": "Status required"}), 400
    try:
        cursor = db.cursor()
        sql = "INSERT INTO projects (name, description, location, status) VALUES (%s, %s, %s, %s)"
        cursor.execute(sql, (name, description, location, status))
        db.commit()
        return jsonify({"message": "Project created successfully"}), 201
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects/<int:projectId>/phases', methods=['POST'])
def create_project_phase(projectId):
    db = get_db_connection()
    data = request.get_json()

    name = data.get('name')
    description = data.get('description')
    startDate = data.get('startDate')
    endDate = data.get('endDate')

    if not name:
        return jsonify({"error": "Name required"}), 400
    if not startDate:
        return jsonify({"error": "Start date required"}), 400
    if not endDate:
        return jsonify({"error": "End date required"}), 400
    
    try:
        cursor = db.cursor()
        sql = "INSERT INTO phases (projectId, name, description, startDate, endDate) VALUES (%s, %s, %s, %s, %s)"
        cursor.execute(sql, (projectId, name, description, startDate, endDate))
        db.commit()
        return jsonify({"message": "Phase created successfully"}), 201
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500
    finally:
        db.close()

@app.route('/api/projects/<int:projectId>', methods=['PUT'])
def update_project(projectId):
    db = get_db_connection()
    data = request.get_json()

    name = data.get('name')
    description = data.get('description')
    location = data.get('location')
    status = data.get('status')
    if not name:
        return jsonify({"error": "Name required"}), 400
    if not location:
        return jsonify({"error": "Location required"}), 400
    if not status:
        return jsonify({"error": "Status required"}), 400
    
    try:
        cursor = db.cursor()
        cursor.execute("SELECT * FROM projects WHERE projectId = %s", (projectId,))
        project = cursor.fetchone()
        if not project:
            return jsonify({"error": "Project not found"}), 404
    
        sql = "UPDATE projects SET name = %s, description = %s, location = %s, status = %s WHERE projectId = %s"
        cursor.execute(sql, (name, description, location, status, projectId))
    
        db.commit()
        return jsonify({"message": "Project updated successfully"}), 200
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500

    finally:
        db.close()


@app.route('/api/projects/<int:projectId>/phases/<int:phaseId>', methods=['PUT'])
def update_project_phase(projectId, phaseId):
    db = get_db_connection()
    data = request.get_json()

    name = data.get('name')
    description = data.get('description')
    startDate = data.get('startDate')
    endDate = data.get('endDate')

    if not name:
        return jsonify({"error": "Name required"}), 400
    if not startDate:
        return jsonify({"error": "Start date required"}), 400
    if not endDate:
        return jsonify({"error": "End date required"}), 400
    
    try:
        cursor = db.cursor()
        cursor.execute("SELECT * FROM phases WHERE projectId = %s AND phaseId = %s", (projectId, phaseId))
        phase = cursor.fetchone()
        if not phase:
            return jsonify({"error": "Phase not found"}), 404
    
        sql = "UPDATE phases SET name = %s, description = %s, startDate = %s, endDate = %s WHERE projectId = %s AND phaseId = %s"
        cursor.execute(sql, (name, description, startDate, endDate, projectId, phaseId))
    
        db.commit()
        return jsonify({"message": "Phase updated successfully"}), 200
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500

    finally:
        db.close()

if __name__ == '__main__':

    app.run(host='0.0.0.0', debug=True)