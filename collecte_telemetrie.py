import requests
import mysql.connector
import time

# Configuration de la voiture et de la base de données distante
URL_VOITURE = "http://192.168.1.50/api/telemetrie" # à adapter en fonction du choix du groupe collaboratif
DB_CONFIG = {
    'host': '172.17.50.238',  # IP de ta VM Ubuntu
    'user': 'candidat4', 
    'password': 'Azerty123#', 
    'database': 'covaciel_gestion'
} #

def archive_data():
    print("Démarrage de la collecte Wi-Fi vers VM Ubuntu (172.17.50.238)...")
    while True:
        try:
            # Récupération des données en JSON
            response = requests.get(URL_VOITURE, timeout=1) #
            if response.status_code == 200:
                data = response.json() #

                # Connexion et insertion dans la VM Ubuntu
                conn = mysql.connector.connect(**DB_CONFIG)
                cursor = conn.cursor()
                sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie, consommation, obstacle, sens) VALUES (1, %s, %s, %s, %s, %s)" #
                cursor.execute(sql, (data['vitesse'], data['tension_batterie'], data['consommation'], data['obstacle'], data['sens'])) #
                conn.commit()
                conn.close()
                print(f"Donnée archivée sur VM : {data['vitesse']} km/h") #
            
        except Exception as e:
            print(f"Erreur de communication : {e}") #
            
        time.sleep(1) #

if __name__ == "__main__":
    archive_data() #