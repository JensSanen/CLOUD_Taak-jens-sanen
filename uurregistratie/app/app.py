from flask import Flask, render_template_string, request, jsonify
from flask_cors import CORS
import pymysql
import os

app = Flask(__name__)
CORS(app)


def get_db_connection():
    return pymysql.connect( 
        host=os.getenv('DB_HOST', 'uurregistratie_db'),
        user=os.getenv('DB_USER', 'uurregistratie'),
        password=os.getenv('DB_PASSWORD', 'uurregistratiePwd'),
        database=os.getenv('DB_NAME', 'uurregistratieAPI'),
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )

@app.route('/api/workers', methods=['GET'])
def get_workers():
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM workers")
        workers = cursor.fetchall()
        return jsonify(workers), 200

    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()

@app.route('/api/workers/<int:workerId>', methods=['GET'])
def get_worker(workerId):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM workers WHERE workerId = %s", (workerId,))
        workers = cursor.fetchone()
        return jsonify(workers), 200

    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects/<int:projectId>/workedHours', methods=['GET'])
def get_project_worked_hours(projectId):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM workers")
        workers = cursor.fetchall()

        cursor.execute("SELECT * FROM worked_hours WHERE projectId = %s", (projectId,))
        workedHours = cursor.fetchall()

        for worker in workers:
            worker['workedHours'] = 0
            for worked_hour in workedHours:
                if worker['workerId'] == worked_hour['workerId']:
                    worker['workedHours'] += worked_hour['hours']

        # Filter de werknemers die gewerkt hebben
        filtered_workers = [worker for worker in workers if worker['workedHours'] > 0]

        return jsonify(filtered_workers), 200

    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        db.close()


@app.route('/api/projects/<int:projectId>/workedHours/<int:workerId>', methods=['GET'])
def get_project_worker_worked_hours(projectId, workerId):
    db = get_db_connection()
    try:
        # Verbeterde query met een JOIN
        cursor = db.cursor(pymysql.cursors.DictCursor)
        query = """
                SELECT wh.*, w.name, w.surname 
                FROM worked_hours AS wh
                INNER JOIN workers AS w
                ON wh.workerId = w.workerId
                WHERE wh.projectId = %s AND wh.workerId = %s
                """
        cursor.execute(query, (projectId, workerId))
        workedHours = cursor.fetchall()

        return jsonify(workedHours), 200

    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        db.close()
    
@app.route('/api/workedHours/<int:whId>', methods=['DELETE'])
def delete_worked_hours(whId):
    pwd = request.args.get("pwd")
    if pwd != "admin":
        return jsonify({"error": "You are not authorized to delete"}), 401

    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("DELETE FROM worked_hours WHERE whId = %s", (whId, ))
        db.commit()
    
        if cursor.rowcount == 0:
            return jsonify({"message":"Project not found"}), 404
        return jsonify({"message":"Project deleted successfully"}), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()

@app.route('/api/projects/<int:projectId>/workedHours', methods=['POST'])
def create_worked_hours(projectId):
    db = get_db_connection()
    data = request.get_json()

    fullName = data.get('fullName')
    firstName = fullName.split(' ')[0]
    lastName = fullName.split(' ')[1]
    hours = data.get('hours')
    comment = data.get('comment')
    date = data.get('date')

    if not fullName:
        return jsonify({"error": "workerId required"}), 400
    if not hours:
        return jsonify({"error": "hours required"}), 400
    if not date:
        return jsonify({"error": "date required"}), 400
    try:
        cursor = db.cursor()

        worker_sql = "SELECT workerId FROM workers WHERE Name = %s AND surName = %s"
        cursor.execute(worker_sql, (firstName, lastName,))
        worker = cursor.fetchone()
        
        if not worker:
            return jsonify({"error": "Worker not found"}), 404
        workerId = worker['workerId']

        sql = "INSERT INTO worked_hours (projectId, workerId, hours, comment, date) VALUES (%s, %s, %s, %s, %s)"
        cursor.execute(sql, (projectId, workerId, hours, comment, date))
        db.commit()
        return jsonify({"message": "Hours booked succesfully"}), 201
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500
    finally:
        db.close()

if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True)