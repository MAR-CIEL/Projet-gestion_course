## @file collecte_telemetrie.py
#  @brief Script Python de collecte et d'historisation temps réel.
#  @details Interroge le Raspberry Pi du véhicule, convertit la chaîne LIDAR ("Oui"/"Non") en booléen et enregistre les données réelles dans MySQL.
#  @author Candidat 4 - Télémétrie & UX
#  @date 2026

import mysql.connector
import requests
import time

## @brief Configuration de la destination des données (serveur MySQL WAMP sur PC de gestion)
## @details Au déploiement, toutes les solutions du projet seront sur un réseau Wi-Fi en 192.168 - Adapter l'IP actuelle à sa nouvelle le jour du déploiement (celle du PC de gestion)
DB_CONFIG = {
    'host': '172.17.50.142',
    'user': 'user_telemetrie',
    'password': 'Azerty123#',
    'database': 'covaciel_gestion'
}

## @brief Point d'entrée de l'API de la voiture
URL_API = "http://192.168.1.4/api_data.php" ## "http://172.17.50.147:3000/api/capteurs" Pour la voiture du groupe d'Adel

def collecter_reelle():
    """! Boucle de récupération et d'insertion des données réelles. """
    print("Connexion API Serveur Voiture : Récupération données réelles...")
    while True:
        try:
            # Requête réseau avec un timeout de 5 secondes pour la stabilité Wi-Fi
            r = requests.get(URL_API, timeout=5)
            if r.status_code == 200:
                data = r.json()
                
                # --- INTERPRÉTATION DE LA DONNÉE RÉELLE DU LIDAR ---
                # L'API renvoyant "Oui" ou "Non", on traduit en format booléen SQL (1 ou 0)
                if data.get('lidar') == "Oui":
                    obstacle_reel = 1
                else:
                    obstacle_reel = 0
                
                # --- SÉCURISATION DES ENTRÉES FACE AUX VALEURS NULL ---
                # Si l'API renvoie null (ex: batterie ou accélération), on applique une valeur par défaut
                vitesse = data.get('vitesse_kmh') if data.get('vitesse_kmh') is not None else 0
                tension = data.get('batterie_tension') if data.get('batterie_tension') is not None else 0.0
                courant = data.get('batterie_courant') if data.get('batterie_courant') is not None else 0.0
                angle   = data.get('angle') if data.get('angle') is not None else 0
                accel   = data.get('acceleration') if data.get('acceleration') is not None else 0.0
                dist    = data.get('distance_parcourue') if data.get('distance_parcourue') is not None else 0.0

                # Déduction dynamique du sens de marche en fonction de l'angle
                if abs(angle) > 90:
                    sens_marche = "AR"
                else:
                    sens_marche = "AV"

                # Connexion au SGBDR et écriture transactionnelle
                conn = mysql.connector.connect(**DB_CONFIG)
                cursor = conn.cursor()
                
                sql = """INSERT INTO telemetrie 
                         (id_voiture, vitesse, tension_batterie, consommation, obstacle, sens, direction, acceleration, distance_parcourue) 
                         VALUES (1, %s, %s, %s, %s, %s, %s, %s, %s)"""
                
                cursor.execute(sql, (
                    vitesse, 
                    tension, 
                    courant, 
                    obstacle_reel,  # Envoi du statut du capteur réel de la voiture
                    sens_marche, 
                    angle, 
                    accel, 
                    dist
                ))
                
                conn.commit()
                conn.close()
                print(f"✅ Synchro OK - LIDAR: {data.get('lidar')} ({obstacle_reel}) | Vitesse: {vitesse} km/h")
            
        except KeyError as e_key:
            print(f"❌ Erreur de structure JSON (clé manquante) : {e_key}")
        except Exception as e:
            print(f"❌ Erreur connexion ou insertion BDD : {e}")
            
        # Cadencement strict à 1 Hz
        time.sleep(1)

if __name__ == "__main__":
    collecter_reelle()