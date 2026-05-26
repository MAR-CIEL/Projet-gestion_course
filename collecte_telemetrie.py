## @file collecte_telemetrie.py
#  @brief Script Python de collecte et d'historisation.
#  @details Interroge le Raspberry Pi du véhicule et enregistre les données dans MySQL.
#  @author Candidat 4
#  @date 2026

import mysql.connector
import requests
import time
import random

## @brief Configuration de la base de données déportée (VM Ubuntu à adapter au déploiement)
DB_CONFIG = {
    'host': '172.17.50.233', 
    'user': 'candidat4',
    'password': 'Azerty123#',
    'database': 'covaciel_gestion'
}

## @brief Point d'entrée de l'API de la voiture (exemple avec un groupe collaboratif de pilotage)
URL_API = "http://192.168.1.7/api_data.php"

def collecter_reelle():
    """! Boucle de récupération et d'insertion des données. """
    print("Connexion API Serveur Voiture : Récupération données...")
    while True:
        try:
            r = requests.get(URL_API, timeout=5)
            if r.status_code == 200:
                data = r.json()
                
                obstacle_sim = random.choice([0, 1])
                
                conn = mysql.connector.connect(**DB_CONFIG)
                cursor = conn.cursor()
                sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie, consommation, obstacle, direction, acceleration, distance_parcourue) VALUES (1, %s, %s, %s, %s, %s, %s, %s)"
                cursor.execute(sql, (data['vitesse_kmh'], data['batterie_tension'], data['batterie_courant'], obstacle_sim, data['angle'], data['acceleration'], data['distance_parcourue']))
                conn.commit()
                conn.close()
                print(f"✅ Donnée insérée : {data['vitesse_kmh']} km/h | Accel: {data['acceleration']} | Dist: {data['distance_parcourue']}")
            
        except Exception as e:
            print(f"❌ Erreur connexion API : {e}")
        time.sleep(1)

if __name__ == "__main__":
    collecter_reelle()