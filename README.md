# COVACIEL 2026 - Dashboard de Télémétrie & UX (Candidat 4)
### Ce dépôt contient l'intégralité des sources pour la partie Télémétrie et Expérience Utilisateur (UX), développée sur la branche "Man".
## Présentation du Périmètre
En tant que Candidat 4, je suis responsable de la chaîne de traitement de l'information, de la voiture jusqu'à l'affichage final :
* Collecte (ID 14) : Récupération des données via WiFi (HTTP/JSON) et archivage dans une base MySQL locale.
* Visualisation (ID 15) : Dashboard Web temps réel avec graphiques dynamiques et gestion des alertes règlementaires.
* Missions Vidéo : Intégration du flux caméra IP et gestion du signal de secours (Fallback).
## Structure du Projet (Arborescence)
/
* index.php					 # Interface principale (Dashboard HTML/PHP)
* collecte_telemetrie.py	 # Archiviste & Simulateur (Python)
* README.md					 # Documentation technique finale
* /api
	* api_data.php			 # API locale (Lecture de la dernière mesure en BDD)
	* get_ranking.php		 # API pour le classement des équipes
	* simulation_voiture.php # Simulation données télémétriques
* /assets
	* script.js				 # Logique de récupération (Fetch) & Chart.js
	* style.css				 # Design et animations d'alertes
* /img
	* no-signal.jpg			 # Image de secours si le flux vidéo est coupé
## Installation et Configuration
1. Prérequis Logiciels
	* Serveur Web : WampServer (Apache, PHP 8+, MySQL).
	* Environnement Python : Nécessite l'installation des modules pour la communication BDD : pip install mysql-connector-python
2. Base de Données (covaciel_gestion)
La table telemetrie doit comporter les colonnes suivantes : id_mesure (PK AI), id_voiture, vitesse, tension_batterie, consommation, obstacle, sens.
3. Mode de Test (Simulateur)
Actuellement, le script collecte_telemetrie.py est configuré en mode simulation pour générer des données aléatoires sans nécessiter la voiture physique. Cela permet de valider l'interface indépendamment du matériel.
## Fonctionnalités Implémentées (Conformité CDC)
* Visualisation Dynamique : Mise à jour automatique de la vitesse, batterie, consommation et obstacles via des requêtes AJAX (Fetch).
* Graphique Temps Réel : Utilisation de Chart.js pour tracer l'historique de la vitesse sur les 15 dernières secondes.
* Gestion d'Énergie : Calcul du pourcentage restant basé sur une plage de tension 6.0V - 7.2V.
* Alerte Règlementaire (Art. 6) : Animation clignotante (critical-alert) automatique dès que la vitesse détectée est de 0 km/h.
* Robustesse Vidéo : Basculement automatique sur no-signal.jpg en cas d'erreur de chargement du flux MJPEG.
## Journal de Maintenance
* Adaptation Réseau : Transition réussie du protocole XBEE (Série) vers WiFi (HTTP) pour une meilleure intégration du flux vidéo.
* Débogage Cache : Résolution des problèmes de mise à jour de l'interface par l'utilisation de CTRL + F5 et l'ajout d'un paramètre anti-cache dans les requêtes JavaScript.
* Optimisation SQL : Tri par id_mesure DESC dans l'API PHP pour garantir l'affichage de la donnée la plus récente, même en cas d'envois multiples par seconde.
_Projet réalisé pour l'examen du BTS CIEL - Session 2026._