from flask import Flask, render_template_string, request, jsonify
from flask_cors import CORS
import pymysql
import os

app = Flask(__name__)
CORS(app)

# Database connection
# We gebruiken hier de ENVIRONMENT variables die we definieren in de docker-compose file om een connectie te kunnen leggen met de database
# gebruik: de tweede parameter van getenv is een default waarde in geval dat de environment variable van de eerste parameter niet bestaat
def get_db_connection():
    return pymysql.connect( 
        host=os.getenv('DB_HOST', 'uurregistratie_db'),
        user=os.getenv('DB_USER', 'uurregistratie'),
        password=os.getenv('DB_PASSWORD', 'uurregistratiePwd'),
        database=os.getenv('DB_NAME', 'uurregistratieAPI'),
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )

@app.route('/api/worked_hours/project/<int:projectId>', methods=['GET'])
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


@app.route('/api/worked_hours', methods=['GET'])
def get_worked_hours():
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM worked_hours")
        users = cursor.fetchall()
        return jsonify(users), 200

    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()    


if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True)