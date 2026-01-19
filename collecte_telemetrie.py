import serial # Pour la lecture XBEE
import mysql.connector # Pour la liaison SGBD 

# Connexion à la base de données créée à l'étape 1
db = mysql.connector.connect(host="localhost", user="root", password="", database="covaciel_gestion") # Nom de la BDD à éventuellement adapter
cursor = db.cursor()

# Configuration du port série pour le module XBEE
ser = serial.Serial('COM3', 9600) # Remplacez COM3 par votre port réel

print("En attente de données télémétriques...")

while True:
    if ser.in_waiting > 0:
        trame = ser.readline().decode('utf-8').strip() # Lecture de la ligne
        # Exemple de trame reçue : "ID:1;V:15.5;B:7.1"
        
        # Parsing (découpage) de la trame
        data = dict(item.split(":") for item in trame.split(";"))
        
        # Insertion dans la table 'telemetrie' [ID 14]
        sql = "INSERT INTO telemetrie (id_voiture, vitesse, tension_batterie) VALUES (%s, %s, %s)"
        val = (data['ID'], data['V'], data['B'])
        cursor.execute(sql, val)
        db.commit()
        print(f"Données reçues et stockées pour voiture {data['ID']}")