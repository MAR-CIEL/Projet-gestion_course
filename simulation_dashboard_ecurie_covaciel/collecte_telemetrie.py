## @file collecte_telemetrie.py
#  @brief Simulateur de données voitures et course en temps réel
#  @details A utiliser uniquement en démonstration sur le réseau du lycée Branly Lyon - Simule de manière réaliste et cohérente toutes les données/valeurs affichées en temps réel sur le dashboard (télémétrie, tracking/classement et chronométrage)
#  @author Candidat 4 - Responsable télémétrie & UX
#  @date 2026

import mysql.connector
import time
import random
import math

## @brief Configuration de la destination des données (serveur MySQL WAMP sur PC de gestion) - 
DB_CONFIG = {
    'host': '172.17.50.142',
    'user': 'user_telemetrie', ## Utilisateur privilégié
    'password': 'Azerty123#',
    'database': 'covaciel_gestion'
}

class CourseSimulateur:
    def __init__(self):
        # Variables de physique glissante pour la Voiture 1
        self.vitesse_actuelle = 0.0
        self.distance_cumulee = 0.0
        self.angle_direction = 0
        self.ticks = 0
        
        # Variables système de chronométrage du T3 (3 Voitures)
        self.start_time = time.time()
        self.tours_voitures = {1: 0, 2: 0, 3: 0}
        self.derniers_passages = {1: 0.0, 2: 0.0, 3: 0.0}
        self.course_terminee = False
        
        # Longueur d'un tour de piste (en mètres)
        self.distance_tour = 80.0 

    def formater_temps(self, secondes_total):
        """! Formate un float de secondes en chaîne MM:SS:MMM standard T3 """
        minutes = int(secondes_total) // 60
        secondes = int(secondes_total) % 60
        milli = int((secondes_total - int(secondes_total)) * 1000)
        return f"{minutes:02d}:{secondes:02d}:{milli:03d}"

    def generer_physique_voiture1(self):
        """! Calcule des profils de capteurs réalistes et différenciés """
        self.ticks += 1
        
        if self.course_terminee:
            # Phase d'arrêt complet après la ligne d'arrivée
            acceleration = -2.5 if self.vitesse_actuelle > 0 else 0
            self.vitesse_actuelle = max(0.0, self.vitesse_actuelle + acceleration)
            self.angle_direction = 0
            obstacle = 0
            consommation = 0.2 # Consommation résiduelle à l'arrêt
            tension_batterie = round(random.uniform(6.8, 6.9), 2) # Remontée de la tension à vide
            sens = "AV"
            return self.vitesse_actuelle, tension_batterie, consommation, obstacle, sens, self.angle_direction, acceleration, self.distance_cumulee

        # 1. Simulation Vitesse & Accélération via un profil sinusoïdal de circuit (Virages / Lignes droites)
        profil_piste = math.sin(self.ticks * 0.15)
        if profil_piste > 0.3: # Ligne droite
            acceleration = round(random.uniform(0.8, 2.0), 2)
            vitesse_cible = 45.0
        elif profil_piste < -0.3: # Zone de freinage / Courbe serrée
            acceleration = round(random.uniform(-1.5, -0.5), 2)
            vitesse_cible = 15.0
        else: # Vitesse stabilisée
            acceleration = round(random.uniform(-0.1, 0.1), 2)
            vitesse_cible = 30.0
            
        self.vitesse_actuelle += acceleration
        self.vitesse_actuelle = max(5.0, min(vitesse_cible + random.uniform(-2, 2), self.vitesse_actuelle))
        
        # 2. Distance cumulée
        self.distance_cumulee += (self.vitesse_actuelle / 3.6) # Conversion km/h vers m/s
        
        # 3. Consommation (Directement proportionnelle au carré de la vitesse + bruit)
        consommation = round((0.005 * (self.vitesse_actuelle ** 2)) + random.uniform(0.1, 0.4), 2)
        
        # 4. Tension Batterie (Modèle de décharge linéaire avec chute ohmique liée à la conso)
        tension_nominale = 7.2 - (self.distance_cumulee * 0.0002) # Usure de la course
        tension_batterie = round(tension_nominale - (consommation * 0.08), 2)
        tension_batterie = max(5.8, min(7.2, tension_batterie))
        
        # 5. Direction (Angle d'asservissement oscillant selon les virages)
        self.angle_direction = int(profil_piste * 45 + random.randint(-5, 5))
        
        # Marche arrière simulée si l'angle dépasse une dérive critique ou en fin de manœuvre
        sens = "AR" if self.ticks % 60 >= 56 else "AV"
        if sens == "AR":
            self.angle_direction = 105 # Forçage de l'angle pour valider ton script IHM
            self.vitesse_actuelle = 8.0
            
        # 6. Obstacle LIDAR (Déclenchement ponctuel lors des passages proches des murs en courbe)
        obstacle = 1 if (profil_piste < -0.85 and random.random() > 0.4) else 0
        
        return round(self.vitesse_actuelle, 2), tension_batterie, consommation, obstacle, sens, self.angle_direction, acceleration, round(self.distance_cumulee, 2)

    def actualiser_tours_et_classement(self):
        """! Algorithme d'historisation des tours et du classement des 3 véhicules """
        temps_ecoule = time.time() - self.start_time
        
        if self.course_terminee:
            return
            
        # Avancement asynchrone des concurrents simulés (Voitures 2 et 3)
        vitesse_moyen_v2 = 28.0 + random.uniform(-3, 3)
        vitesse_moyen_v3 = 27.5 + random.uniform(-2, 4)
        
        dist_v2 = (vitesse_moyen_v2 / 3.6) * temps_ecoule
        dist_v3 = (vitesse_moyen_v3 / 3.6) * temps_ecoule
        
        # Calcul des tours franchis
        self.tours_voitures[1] = int(self.distance_cumulee // self.distance_tour)
        self.tours_voitures[2] = int(dist_v2 // self.distance_tour)
        self.tours_voitures[3] = int(dist_v3 // self.distance_tour)
        
        # Borner le nombre de tours à 4 maximum
        for i in [1, 2, 3]:
            if self.tours_voitures[i] >= 4:
                self.tours_voitures[i] = 4
                if self.derniers_passages[i] == 0.0:
                    self.derniers_passages[i] = temps_ecoule

        # Détermination du classement instantané en fonction de la distance parcourue
        distances = {1: self.distance_cumulee, 2: dist_v2, 3: dist_v3}
        ordre_classement = sorted(distances, key=distances.get, reverse=True)
        
        # Récupération du nombre de tours maximum sur la piste
        tour_max_global = max(self.tours_voitures.values())
        if tour_max_global < 1: tour_max_global = 1
        
        # Génération du fichier d'échange course.txt au format attendu par le T3
        str_classement = "-".join(map(str, ordre_classement))
        try:
            with open("course.txt", "w") as f:
                f.write(f"{self.formater_temps(temps_ecoule)}|{tour_max_global}|{str_classement}\n")
        except IOError:
            pass
            
        # Fin de course ?
        if self.tours_voitures[1] == 4 and self.tours_voitures[2] == 4 and self.tours_voitures[3] == 4:
            self.course_terminee = True
            print("COURSE TERMINÉE ! Toutes les voitures ont franchi les 4 tours.")

    def executer(self):
        print(f"Connexion au serveur WAMP")
        while True:
            try:
                # 1. Calculs métiers
                vit, tens, conso, obst, sens, ang, acc, dist = self.generer_physique_voiture1()
                self.actualiser_tours_et_classement()
                
                # 2. Poussée réseau dans le SGBD
                conn = mysql.connector.connect(**DB_CONFIG)
                cursor = conn.cursor()
                
                sql = """INSERT INTO telemetrie 
                         (id_voiture, vitesse, tension_batterie, consommation, obstacle, sens, direction, acceleration, distance_parcourue) 
                         VALUES (1, %s, %s, %s, %s, %s, %s, %s, %s)"""
                cursor.execute(sql, (vit, tens, conso, obst, sens, ang, acc, dist))
                
                conn.commit()
                conn.close()
                
                print(f"Renvoi télémétrique vers la BDD -> {vit} km/h | {tens}V | {conso}A | Obstacle: {obst} | Sens: {sens} | {ang}° | {acc} m/s² | {dist} m")
                
            except Exception as e:
                print(f"Erreur de communication réseau ou SQL : {e}")
                
            time.sleep(1)

if __name__ == "__main__":
    sim = CourseSimulateur()
    sim.executer()