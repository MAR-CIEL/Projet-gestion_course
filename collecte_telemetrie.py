import requests
import mysql.connector
import time
import random 

# Configuration BDD locale
DB_CONFIG = {'host': 'localhost', 'user': 'root', 'password': '', 'database': 'covaciel_gestion'}

def archive_data():
    print("Démarrage du simulateur de télémétrie...")
    while True:
        try:
            # --- BLOC SIMULATION ---
            data = {
                "vitesse": random.randint(0, 45),
                "tension_batterie": round(random.uniform(6.0, 7.2), 2),
                "consommation": round(random.uniform(0.5, 3.5), 2),
                "obstacle": random.choice([0, 1]),
                "sens": random.choice(["AV", "AR"])
            }
            # -----------------------

            # Insertion BDD
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()
            sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie, consommation, obstacle, sens) VALUES (1, %s, %s, %s, %s, %s)"
            cursor.execute(sql, (data['vitesse'], data['tension_batterie'], data['consommation'], data['obstacle'], data['sens']))
            conn.commit()
            conn.close()
            
            print(f"Archivé : {data['vitesse']} km/h | Batt: {data['tension_batterie']}V")

        except Exception as e:
            print(f"Erreur : {e}")
        time.sleep(1)

if __name__ == "__main__":
    archive_data()