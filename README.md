# COVACIEL 2026 Simulation Solution Finale Télémétrie & UX
## Résumé Général du Processus :
	* Script Simulateur Python remplace la voiture réelle :
		* Crée chaque seconde des données télémétriques aléatoires réalistes
		* Envoie ces données via le réseau vers l'adresse IP de la VM Ubuntu en utilisant les identifiants d'un utilisateur privilégié (pour MySQL).
	* Serveur MySQL de stockage (Ubuntu) :
		* Le service MySQL reçoit la requête et enregistre les données dans la table "telemetrie" de la BDD principale
		* Chaque mesure est stockée avec un identifiant unique, ce qui permet de conserver tout l'historique de la course
	* Serveur WAMP héberge les scripts PHP qui servent d'intermédiaires
	* API PHP de distribution (WAMP) : 
		* Se connecte à la VM Ubuntu pour récupérer uniquement la dernière mesure insérée
		* Renvoie cette mesure au format JSON pour qu'elle soit exploitable par le navigateur web
	* Dashboard d'affichage (Navigateur) :
		* Script JavaScript interroge l'API PHP toutes les secondes
		* Met à jour les indicateurs textuels et dessine la courbe de vitesse en temps réel avec Chart.js
		* Si la vitesse est nulle, le script active une alerte visuelle clignotante rouge définie dans la feuille de style principale
		* Accède et affiche le flux vidéo réel en direct de la caméra embarquée de la voiture via une requête HTTP vers le serveur Raspberry PI de la voiture (pas encore accessible, voir ligne suivante)
		* Affiche une image de secours en cas d'absence de signal vidéo
	* Bonus : Système de gestion des résultats de la course (données de résultat stockées dans une table dédiée de la BDD et appelées par une API, mais pas encore de gestion d'affichage)

## Organisation & Arborescence :
	* Emplacement du code pour exécution avec WAMP : "C:\wamp64\www"
	* Script Python : collecte_telemetrie.py
	* VM Ubuntu : serveur-ubuntu-projet 172.17.50.233
	* User privilégié de gestion initiale : manz
	* User privilégié de gestion des données (script Python) : candidat4 (mdp : "Azerty123#")
	* BDD principale : covaciel_gestion
	* API PHP de distribution : api/api_data.php
	* Script JavaScript : assets/script.js
	* Dashboard d'affichage : index.php
	* Feuille de Style principale : assets/style.css
	* Serveur Raspberry PI voiture : http://192.168.1.50 (à éventuellement adapter au déploiement)
	* Flux vidéo : http://192.168.1.50:8080/stream.mjpg (à éventuellement adapter au déploiement)
	* Image de secours : img/no-signal.jpg 
	* Table de gestion des résultats : resultat
	* API PHP de gestion des résultats : api/get_ranking.php
	
## Protocole de test à suivre :
	* Démarrer Wampserver64 (vérifier son fonctionnement avec l'affichage du logo en vert dans le barre des tâches)
	* Ouvrir une cmd ou autre terminal et se connecter à la VM Ubuntu en SSH avec user privilégié de gestion initiale : ssh manz@172.17.50.233 (vérifier le fonctionnement avec la présence du message de bienvenue)
	* Démarrer le serveur MySQL : sudo systemctl start mysql (vérifer le fonctionnement avec l'absence de message d'erreur)
	* Ouvrir une console PowerShell dans l'emplacement du code et exécuter le script Python : python collecte_telemetrie.py (vérifier le fonctionnement avec l'affichage du message du succès du renvoi des données vers la BDD)
	* Ouvrir un navigateur et taper : localhost/covaciel_simulation_sln_finale
	* Constater le fonctionnement avec l'affichage de chaque valeur télémétrique et leur mise à jour chaque seconde (idem pour la courbe chart), et l'affichage du flux vidéo et de l'image de secours en cas d'absence du signal
	
## Suggestion d'évolutibilité :
	* Stockage de tous les fichiers sources dans le serveur Ubuntu pour exécution à distance via n'importe quel poste