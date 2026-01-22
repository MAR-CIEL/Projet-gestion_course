# COVACIEL 2026 - Dashboard de Télémétrie & UX (Candidat 4)
### Cette branche contient l'intégralité des sources pour la partie Télémétrie et Expérience Utilisateur (UX) du projet COVACIEL

## Présentation du Périmètre
Responsable de la chaîne de traitement de l'information, de la réception radio à la visualisation temps réel :
* Collecte (ID 14) : Script Python pour l'écoute du module XBEE (PAN-ID 1234, Canal C) et le stockage en base de données MySQL.
* Visualisation (ID 15) : Dashboard Web dynamique avec graphiques temps réel et indicateurs conformes au CDC.
* Missions Élargies (Avenant T5) : Intégration du flux vidéo OpenCV et gestion du classement des équipes.

## Structure du Projet
Le projet est organisé de manière modulaire pour séparer la logique métier de la présentation :
* index.php (Interface principale (Dashboard HTML/PHP))
* collecte_telemetrie.py (Script de réception Python)
* /api
	* api_data.php (API de télémétrie (JSON Backend))
	* get_ranking.php (API de classement (Données équipes))
* /assets
	* script.js (Logique AJAX, Chart.js et gestion des alertes)
	* style.css (Mise en forme UX et animations critiques)
* /img
	* no-signal.jpg (Image de secours (Fallback flux vidéo))
	
## Installation et Configuration
1. Prérequis Logiciels
	* Serveur Web : WampServer (Apache 2.4+, PHP 8+, MySQL 5.7+).
	* Environnement Python : Python 3.x avec les bibliothèques suivantes installées :
		* pip install pyserial mysql-connector-python

2. Base de Données
Importer la structure SQL dans une base nommée covaciel_gestion. La table telemetrie doit comporter les colonnes suivantes : vitesse, tension_batterie, consommation, obstacle et sens.

3. Déploiement
	1. Placer le dossier du projet dans C:\wamp64\www\covaciel\.
	2. S'assurer que WampServer est actif (icône verte).
	3. Lancer le script de collecte dans un terminal séparé :
		* python collecte_telemetrie.py
	4. Accéder à l'interface via : http://localhost/covaciel/
	
## Fonctionnalités Implémentées (Conformité CDC)
* Télémétrie complète : Affichage en temps réel de la vitesse, tension NiMH, consommation (Ampères), sens de marche et détection d'obstacles.
* Suivi d'Énergie : Calcul dynamique du pourcentage de batterie restant (étalonnage 6.0V - 7.2V) avec barre de progression interactive.
* Graphique Dynamique : Visualisation de l'historique de vitesse sur les 15 dernières secondes via Chart.js.
* Sécurité Arbitrage : Alerte visuelle clignotante (critical-alert) en cas d'immobilisation du véhicule (Conforme Art. 6 du règlement).
* Vidéo de Secours : Gestion automatique de la perte de signal caméra via une image de fallback (no-signal.jpg).

## Journal de Maintenance et Débogage
Au cours du développement, les problématiques suivantes ont été résolues :
* Gestion des Dépendances : Résolution des erreurs ModuleNotFoundError par l'installation des modules serial et mysql-connector.
* Intégration BDD : Résolution de l'erreur 1049 (Base inconnue) par la création et l'alignement du schéma SQL avec le Candidat 1.
* Optimisation API : Typage forcé des données en PHP (floatval, intval) pour garantir la compatibilité des calculs dans le front-end JavaScript.

## Gestion de Version (ID 26)
Le projet utilise Git pour assurer la traçabilité des modifications :
* Branche Man : Branche de développement isolée pour le candidat 4.
* Commits Documentés : Chaque étape (Ajout API, Correction CSS, Intégration Vidéo) est validée par un message explicite.

_Projet réalisé pour l'examen du BTS CIEL - Session 2026._