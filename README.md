# COVACIEL 2026 Solution XBEE Télémétrie & UX
### Cette solution deviendra obsolète au vu de sa prochaine évolution vers d'autres technologies plus avancées pour le projet (plus de BDD en local)
### Cette version repose sur une architecture de communication radio XBEE (Protocole 802.15.4) pour le transfert des données télémétriques entre la voiture et le poste de contrôle.

## Résumé Général du processus :
	* La Transmission Radio (Matériel vers Python) :
		* Module XBEE embarqué sur le véhicule envoie des trames de données brutes via les ondes radio
		* Données sont envoyées sous forme de chaînes de caractères structurées
		* Second module XBEE est branché en USB sur l'ordinateur. Script Python "écoute" le port série pour intercepter ces trames
	* Le Traitement et l'Archivage (Python vers BDD) : 
		* Script Python agit comme un traducteur et un archiviste
		* Script découpe la trame reçue pour extraire les valeurs de vitesse (V), de batterie (B) et de consommation (C)
		* Script exécute une requête SQL pour insérer immédiatement ces valeurs dans la BDD locale (WAMP)
	* La Mise à disposition des données (BDD vers API) : 
		* Dashboard Web ne parle pas directement à la base de données pour des raisons de sécurité et de performance
		* API PHP interroge la table telemetrie pour récupérer uniquement la toute dernière mesure enregistrée (la plus récente)
		* API PHP convertit cette mesure au format JSON, ce qui permet au JavaScript de lire facilement les informations
	* L'Affichage Temps Réel (API vers Dashboard) : 
		* Script Js appelle l'API PHP toutes les secondes
		* Dashboard met à jour les indicateurs textuels (vitesse, tension, consommation), la barre d'énergie restante et dessine la courbe de vitesse dynamique avec Chart.js
		* Si la vitesse est nulle, dashboard déclenche une alerte clignotante rouge sur l'écran pour signaler l'immobilisation
	* Gestion de la Vidéo (Indépendant du XBEE) : 
		* Diffusée en direct par le Raspberry Pi via le réseau IP
		* Si la connexion vidéo échoue, le Dashboard affiche automatiquement l'image de secours
	* Bonus : Système de gestion des résultats de la course (données de résultat stockées dans une table dédiée de la BDD et appelées par une API, mais pas encore de gestion d'affichage)

## Arborescence & Organisation :
	* Emplacement du code pour exécution avec WAMP : "C:\wamp64\www"
	* Serial Port du XBEE à éventuellement adapter : COM3
	* Baud Rate à éventuellement adapter : 9600
	* Script Python : collecte_telemetrie.py
	* BDD locale : covaciel_gestion 
	* API PHP principale : api/api_data.php
	* Script Js : assets/script.js
	* Dashboard : index.php
	* IP Raspberry PI flux vidéo à éventuellement adapter : 192.168.1.50
	* Image de secours : no-signal.jpg
	* Feuille de style principale : assets/style.css
	* Table de gestion des résultats : resultat
	* API PHP de gestion des résultats : api/get_ranking.php
	
## Protocole de test à suivre :
	* Préparation du Matériel (Hardware) :
		* Côté Voiture, s'assurer que le module XBEE est alimenté et correctement relié aux broches TX/RX du microcontrôleur
		* Côté PC, brancher le module XBEE récepteur via son adaptateur USB
		* Identifier le port de communication dans le Gestionnaire de périphériques Windows et mettre à jour la ligne serial_port dans le script Python
	* Test de la Liaison Radio (Sniffing) :
		* Ouvrir un terminal série (comme XCTU ou celui d'Arduino IDE)
		* Configurer la vitesse (9600 bauds)
		* Succès : On doit voir défiler des trames brutes comme "ID:1;V:15.2;B:7.1;C:2.1"
		* Fermez impérativement le terminal avant de passer à l'étape suivante pour libérer le port COM
	* Lancement de la Chaîne Logicielle :
		* Démarrer WAMP : S'assurer que l'icône est verte (Services Apache et MySQL actifs)
		* Lancer la Collecte : Exécutez "python collecte_telemetrie.py" :
			* Le message "Réception télémétrie active..." doit s'afficher
			* Dès qu'une trame arrive, le script doit confirmer : "Voiture 1 : 15.2 km/h enregistrée"
		* Ouvrir le Dashboard : Accédez à "http://localhost/covaciel_xbee/index.php"
	* Validation des Fonctionnalités (Recette) :
		* Graphique : La courbe de vitesse doit s'incrémenter chaque seconde avec les données réelles
		* Indicateurs : Les valeurs de Batterie (V) et Consommation (A) doivent correspondre à ce qui est envoyé par le XBEE
		* Alerte Art. 6 : Arrêtez la voiture. Le texte de la vitesse doit clignoter en rouge (critical-alert) dès qu'elle atteint 0
		* Fallback Vidéo : Si la caméra n'est pas branchée, l'image no-signal.jpg doit s'afficher dans le cadre dédié
	* Diagnostic Rapide (Dépannage) :
		* Erreur "Serial port busy" : Un autre logiciel (comme XCTU) utilise déjà le port COM. Le fermer
		* Erreur "Trame corrompue" : Vérifier que les séparateurs ; et : sont bien présents dans le code de la voiture
		* Dashboard figé : Vérifier dans la console du navigateur (F12) si api/api_data.php renvoie bien un JSON valide
		
## Suggestion d'évolutibilité :
	* Passer en HTTP Wi-Fi/JSON