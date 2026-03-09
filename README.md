# COVACIEL 2026 Solution Finale Télémétrie & UX
### Le processus de cette solution est le même pour chaque voiture, cette solution n'est développée que pour l'une des voitures pour le moment
## Résumé Général du Processus :
* Serveur données passif Raspberry PI de la voiture stoque les données télémétriques converties des capteurs physiques de la voiture
* Script Python demande chaque seconde ces données via une requête HTTP directe vers l'API concernée du serveur
* Serveur répond avec un message JSON contenant toutes ces données et leur valeur
* Script Python renvoie ces données dans la BDD principale dans la VM Ubuntu du projet qui les stoque et les historise à son tour (avec serveur MySQL)
* BDD renvoie la dernière ligne de chaque donnée vers API PHP qui les demande constamment 
* API transforme ensuite en JSON à nouveau
* Script Js appelle l'API à la même fréquence que le Python et l'affiche sur le Dashboard PHP/HTML
* Dashboard fait une requête HTTP vers le serveur du Raspberry PI pour l'accès et l'affichage du flux vidéo en direct de la caméra embarquée de la voiture, et affiche une image de secours en cas d'absence du signal
* Bonus : Système de gestion des résultats de la course (données de résultat stockées dans une table dédiée dans la BDD principale et appelées par une API mais pas encore de gestion d'affichage)
	
## Organisation & Arborescence :
* Emplacement du code pour exécution avec WAMP : "C:\wamp64\www"
* Serveur données Raspberry : http://172.17.50.239 (à éventuellement adapter)
* API de données télémétriques du serveur : http://172.17.50.239/api_data.php (à éventuellement adapter)
* Script Python : collecte_telemetrie.py
* Serveur Ubuntu : serveur-ubuntu-projet 172.17.50.233
* User privilégié de gestion initiale : manz
* User privilégié de gestion des données (script python) : candidat4 (mdp : "Azerty123#")
* BDD principale : covaciel_gestion
* API PHP principale : api/api_data.php
* Script Js : assets/script.js
* Dashboard PHP/HTML : index.php
* Feuille de Style principale : assets/style.css
* Flux vidéo : http://172.17.50.239:8000/video_feed (à éventuellement adapter)
* Image de secours : img/no-signal.jpg
* Table de gestion des résultats : resultat
* API PHP de gestion des résultats : api/get_ranking.php
	
## Protocole de test à suivre :
* Attendre que le candidat/technicien 1 démarre la VM (et le service mysql éventuellement, sinon suivre les 2 prochaines lignes)
* Démarrer Wampserver64 (vérifier son fonctionnement avec l'affichage du logo en vert dans le barre des tâches)
* Ouvrir une cmd ou autre terminal et se connecter à la VM Ubuntu en SSH avec user privilégié de gestion initiale : ssh manz@172.17.50.233(vérifier le fonctionnement avec la présence du message de bienvenue)
* Démarrer le serveur MySQL : sudo systemctl start mysql (vérifer le fonctionnement avec l'absence de message d'erreur)
* Ouvrir une console PowerShell dans le répertoire et exécuter le script Python : python collecte_telemetrie.py (vérifier le fonctionnement avec l'affichage du message du succès du renvoi des données vers la BDD)
* Ouvrir le dashboard dans un navigateur : localhost/covaciel_t4_sources/solution_finale
* Constater le fonctionnement avec l'affichage de chaque valeur et indicateur dynamique télémétrique et leur mise à jour chaque seconde (idem pour la courbe chart), et l'affichage du flux vidéo et de l'image de secours en cas d'absence du signal

## Suggestion d'évolutibilité :
* Stockage de tous les fichiers sources dans la VM Ubuntu pour exécution à distance via n'importe quel poste à condition que la gestion des droits soit également réalisée
* Développer la solution pour les autres voitures