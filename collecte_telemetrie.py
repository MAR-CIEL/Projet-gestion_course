import mysql.connector
import requests
import time
import random

# CONFIGURATION VM UBUNTU (Destination)
DB_CONFIG = {
    'host': '172.17.50.233', 
    'user': 'candidat4', 
    'password': 'Azerty123#', 
    'database': 'covaciel_gestion'
}

# IP de l'API du serveur Raspberry Pi Voiture
URL_API = "http://172.17.50.239/api_data.php" 

# Fonction de gestion des données télémétriques
def collecter_reelle():
    print("Connexion API Serveur Voiture : Récupération données...")
    while True:
        try:
            r = requests.get(URL_API, timeout=2)
            if r.status_code == 200:
                data = r.json() 
                
                # Simulation obstacle locale (en attente du serveur voiture)
                obstacle_sim = random.choice([0, 1]) 
                
                conn = mysql.connector.connect(**DB_CONFIG)
                cursor = conn.cursor()
                # Insertion des données réelles
                sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie, consommation, obstacle, direction, acceleration, distance_parcourue) VALUES (1, %s, %s, %s, %s, %s, %s, %s)"
                cursor.execute(sql, (data['vitesse_kmh'], data['batterie_tension'], data['batterie_courant'], obstacle_sim, data['direction'], data['acceleration'], data['distance_parcourue']))
                conn.commit()
                conn.close()
                print(f"✅ Donnée insérée : {data['vitesse_kmh']} km/h | Accel: {data['acceleration']} | Dist: {data['distance_parcourue']}")
            
        except Exception as e:
            print(f"❌ Erreur connexion API : {e}")
        time.sleep(1) # Fréquence 1Hz

if __name__ == "__main__":
    collecter_reelle()