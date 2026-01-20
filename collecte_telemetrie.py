# Version final du script prototype de collecte des données télémétriques
# Tâche ID 14 : Ecoute le module XBEE (PAN-ID 1234, Canal C) et enregistre les trames en BDD
import serial
import mysql.connector
import time

# Configuration XBEE et BDD
config = {
    'serial_port': 'COM3', # A adapter selon le PC
    'baud': 9600, # A adapter selon le baud rate à utiliser
    'db': {
        'host': 'localhost',
        'user': 'root',
        'password': '',
        'database': 'covaciel_gestion' # A adapter selon le nom de la BDD données par le technicien concerné
    }
}

def listen_telemetry():
    try:
        conn = mysql.connector.connect(**config['db'])
        cursor = conn.cursor()
        ser = serial.Serial(config['serial_port'], config['baud'], timeout=1)
        print("Réception télémétrie active...")

        while True:
            if ser.in_waiting > 0:
                # Lecture de la trame type: ID:1;V:15.2;B:7.1;C:2.1
                raw_line = ser.readline().decode('utf-8').strip()
                try:
                    parts = dict(item.split(":") for item in raw_line.split(";"))
                    
                    sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie, consommation) VALUES (%s, %s, %s, %s)"
                    cursor.execute(sql, (parts['ID'], parts['V'], parts['B'], parts['C']))
                    conn.commit()
                    print(f"Voiture {parts['ID']} : {parts['V']} km/h enregistrée.")
                except Exception as e:
                    print(f"Erreur trame : {e}")
            time.sleep(0.1)
    except Exception as e:
        print(f"Erreur critique : {e}")

if __name__ == "__main__":
    listen_telemetry()
