import mysql.connector
import time
import random 

# Configuration de la VM Ubuntu distante
DB_CONFIG = {
    'host': '172.17.50.238', 
    'user': 'candidat4', 
    'password': 'Azerty123#', 
    'database': 'covaciel_gestion'
}

def simulation_collecte():
    print("Simulateur actif : Envoi des données vers la VM Ubuntu (172.17.50.238)...")
    while True:
        try:
            # Génération de données aléatoires réalistes
            data = {
                "vitesse": random.randint(0, 45),
                "tension_batterie": round(random.uniform(6.0, 7.2), 2),
                "consommation": round(random.uniform(0.5, 3.5), 2),
                "obstacle": random.choice([0, 1]),
                "sens": random.choice(["AV", "AR"])
            }

            # Connexion à la base de données sur la VM Linux
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()
            
            sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie, consommation, obstacle, sens) VALUES (1, %s, %s, %s, %s, %s)"
            cursor.execute(sql, (data['vitesse'], data['tension_batterie'], data['consommation'], data['obstacle'], data['sens']))
            
            conn.commit()
            conn.close()
            
            print(f"✅ Donnée envoyée à la VM : {data['vitesse']} km/h | {data['tension_batterie']} V")

        except Exception as e:
            print(f"❌ Erreur de connexion à la VM Ubuntu : {e}")
        
        time.sleep(1) # Fréquence de 1Hz

if __name__ == "__main__":
    simulation_collecte()