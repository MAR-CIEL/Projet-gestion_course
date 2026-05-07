import mysql.connector # Accès MySQL
import requests # Requêtes de récupération des données
import time # Fréquence d'exécution 
import random # Génération valeurs télémétriques aléatoires cohérente (temporaire)

# BDD destination des valeurs télémétriques (VM Ubuntu)
DB_CONFIG = {
    'host': '172.17.50.233', 
    'user': 'candidat4', 
    'password': 'Azerty123#', 
    'database': 'covaciel_gestion'
}

# Interroge le serveur et demande les données
URL_API = "http://{IP_SERVEUR_RASPBERRY_VOITURE}/{API_PHP_TELEMETRIE}"

# Récupération des données et renvoi vers la BDD
def collecter_reelle():
    print("Connexion API Serveur Voiture : Récupération données...")
    while True:
        try:
            r = requests.get(URL_API, timeout=2)
            if r.status_code == 200:
                data = r.json() # Récupère le contenu JSON (natif au navigateur facilitant la transmission des données)
                
                # Simulation donnée de détection d'obstacle à défaut de sa présence sur l'API de la voiture (temporaire)
                obstacle_sim = random.choice([0, 1]) # 0 : Pas d'obstacle        1 : Obstacle détecté
                
                # Se connecte à la BDD et insert toutes les valeurs dans les champs concernés
                conn = mysql.connector.connect(**DB_CONFIG)
                cursor = conn.cursor()
                # Insertion des données réelles
                sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie, consommation, obstacle, direction, acceleration, distance_parcourue) VALUES (1, %s, %s, %s, %s, %s, %s, %s)"
                cursor.execute(sql, (data['vitesse_kmh'], data['batterie_tension'], data['batterie_courant'], obstacle_sim, data['direction'], data['acceleration'], data['distance_parcourue']))
                conn.commit()
                conn.close()
                print(f"✅ Donnée insérée : {data['vitesse_kmh']} km/h | Accel: {data['acceleration']} | Dist: {data['distance_parcourue']}") # Message de succès à chaque insertion
            
        except Exception as e:
            print(f"❌ Erreur connexion API : {e}") # Message en cas d'echec de connexion à l'API de la voiture
        time.sleep(1) # Nouvelles données chaque seconde

# Exécution de la fonction
if __name__ == "__main__":
    collecter_reelle()