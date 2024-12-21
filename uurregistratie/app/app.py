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

# DO REST API STUFF

# READ - vraag een lijst op van alle users
# we gebruiken het endpoint /api/users. Elk http-get request naar www.mijnwebsite.be/api/users zal deze lijst in json formaat terugkrijgen
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

@app.route('/api/project/<int:project_id>/phases', methods=['GET'])
def get_project_phases(project_id):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM phases where project_id = %s", (project_id))
        users = cursor.fetchall()
        return jsonify(users), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()

@app.route('/api/project/<int:project_id>/phase/<int:phase_id>', methods=['GET'])
def get_project_phase(project_id, phase_id):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("SELECT * FROM phases where project_id = %s AND phase_id = %s", (project_id, phase_id))
        users = cursor.fetchone()
        return jsonify(users), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()

# DELETE - delete een specifieke user op basis van de id in de tabel
# we gebruiken het endpoint /api/users/<int>. Elk http-get request naar www.mijnwebsite.be/api/users/<int> zal de gebruiker met id=<int> deleten
@app.route('/api/projects/<int:project_id>', methods=['DELETE'])
# We specifieren dat elke integer als een geldig endpoint beschouwd moet worden en dat we dat integer opslaan in de variabele user_id
def delete_project(project_id):
    db = get_db_connection()
    try:
        cursor = db.cursor(pymysql.cursors.DictCursor)
        cursor.execute("DELETE FROM projects WHERE projectId = %s", project_id)
        # aangezien we niet enkel uitlezen maar de database ook effectief wijzigen, is het belangrijk de wijziging ook te committen
        db.commit()
        # enkel als er effectief iets gewijzigd is, is er een user gedelete
        if cursor.rowcount == 0:
            return jsonify({"message":"Project not found"}), 404
        return jsonify({"message":"Project deleted successfully"}), 200
    except Exception as e:
        return jsonify({"error":str(e)}), 500
    finally:
        db.close()

# CREATE - maak een user aan op basis van het meegegeven JSON object
@app.route('/api/users', methods=['POST'])
def create_user():
    db = get_db_connection()
    # de data die doorgestuurd wordt bevind zich in de body van het POST-request. In dit geval verwachten we een JSON formaat
    data = request.get_json()
    # We gaan ervan uit dat het JSON object deze waarden bevat
    name = data.get('name')
    email = data.get('email')
    # indien het JSON object niet de juiste informatie bevat geven we dit terug in de vorm van een error
    if not name or not email:
        return jsonify({"error": "Name and email required"}), 400
    try:
        # Indien het JSON object de correcte informatie bevat, kunnen we het gebruiken om een user aan te maken en in de database op te slaan
        cursor = db.cursor()
        sql = "INSERT INTO users (name, email) VALUES (%s, %s)"
        cursor.execute(sql, (name, email))
        # aangezien we niet enkel uitlezen maar de database ook effectief wijzigen, is het belangrijk de wijziging ook te committen
        db.commit()
        return jsonify({"message": "User created successfully"}), 201
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500
    finally:
        db.close()

# UPDATE - werk een bestaande user bij op basis van het meegegeven JSON object
@app.route('/api/users/<int:id>', methods=['PUT'])
def update_user(id):
    db = get_db_connection()
    # De data die doorgestuurd wordt bevindt zich in de body van het PUT-request. In dit geval verwachten we een JSON-formaat
    data = request.get_json()
    # We gaan ervan uit dat het JSON-object deze waarden bevat
    name = data.get('name')
    email = data.get('email')
    # Controleer of de juiste gegevens zijn meegegeven in de JSON
    if not name or not email:
        return jsonify({"error": "Name and email required"}), 400
    try:
        # Check of de user met de gegeven id bestaat
        cursor = db.cursor()
        cursor.execute("SELECT * FROM users WHERE id = %s", (id,))
        user = cursor.fetchone()
        if not user:
            return jsonify({"error": "User not found"}), 404
        # Indien de user bestaat, werk de gegevens bij
        sql = "UPDATE users SET name = %s, email = %s WHERE id = %s"
        cursor.execute(sql, (name, email, id))
        # Vergeet niet om de wijziging te committen
        db.commit()
        return jsonify({"message": "User updated successfully"}), 200
    except Exception as e:
        db.rollback()
        return jsonify({"error": str(e)}), 500

    finally:
        db.close()

if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True)