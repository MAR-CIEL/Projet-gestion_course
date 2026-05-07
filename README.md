# COVACIEL 2026 - Solution Finale - Télémétrie & Vidéo

### Solution finale du dashboard de test télémétrie et vidéo multi-véhicule (s’adapte à la voiture testée grâce à son id). Accès aux données télémétriques converties des capteurs physiques de la voiture, ainsi qu’au flux vidéo de sa caméra embarquée via une connexion Wi-Fi au point d’accès de son Raspberry Pi intégré, qui stocke tout. Dashboard dédié au tests unitaires, et fonctionnels, avant intégration dans le dashboard final du projet, accompagné du chronométrage/tracking, et d’une configuration Wi-Fi adaptée à l’affichage multi-véhicule, lancé au démarrage de la course.

## Résumé Général du Processus :
* Script Python récupère chaque seconde les données télémétriques et leur valeur via une requête HTTP/JSON vers l’API PHP externe du serveur du Raspberry Pi contenant ces données
* Script Python renvoie ces données vers notre BDD dans notre VM (serveur Ubuntu avec MySQL) qui les stocke et les historise (avec un horodatage pour les statistiques de course)
* API PHP interne récupère ces données (en provenance de la BDD) et les renvoie vers le script Js
* Script Js gère l’affichage web dynamique des données (mise à jour en tant automatique chaque seconde en temps réel), ainsi que les éléments dynamiques dépendants de ces données (courbe de vitesse, jauges…)
* Dashboard web sert de résultat visuel (UX) et affiche également le flux vidéo en tant réel de la caméra embarquée de la voiture via une requête HTTP vers le stockage du flux dans le serveur du Raspberry Pi (avec son port dédié)
	
## Organisation & Arborescence :
* Emplacement du code pour exécution avec WAMP : "C:\wamp64\www\covaciel_t4_sources\dashboard_finale_test_voiture_{nom du groupe}" 
* Script Python : collecte_telemetrie.py
* VM (avec serveur Ubuntu) : serveur-ubuntu-projet 172.17.50.233
* User privilégié (démarrage service MySQL avant exécution du code) : manz
* API PHP interne : api/api_data.php
* Script Js : assets/script.js
* Dashboard web : index.php
* Feuille de Style du dashboard : assets/style.css
* Image de remplacement (absence de flux) : img/no-signal.jpg
	
## Protocole d’exécution à suivre pour les tests unitaires (exécution réseau local - connexion directe au Raspberry Pi voiture par voiture) :
* Attendre que le candidat/technicien 1 démarre la VM (+ service MySQL si possible)
* Se connecter à la VM avec user privilégié via une invite de commande : ssh manz@172.17.50.233
* Si T1 n’a pas démarré le service MySQL, le faire soi-même : sudo systemctl start mysql
* Démarrer Wampserver64
* Ouvrir PowerShell dans le répertoire et exécuter le script Python : python collecte_telemetrie.py (vérifier le fonctionnement avec l'affichage du message du succès du renvoi des données vers la BDD)
* Ouvrir le dashboard web dans un navigateur : localhost/covaciel_t4_sources/dashboard_finale_test_voiture_{nom du groupe} (adapter à la voiture à tester)
* Constater le fonctionnement avec l'affichage de chaque valeur et indicateur dynamique télémétrique et leur mise à jour chaque seconde, ainsi que l’affichage du flux vidéo et de l’image de secours en cas d’absence du signal.

## Intégration prochaine : 
* Inclure cette solution dans un dashboard complet spectateur et écurie exécuté au lancement de la course (constitué de la télémétrie, vidéo, tracking et chronométrage en direct multi-véhicule)
* Toutes les voitures (leur Raspberry Pi) sur un réseau Wi-Fi commun et connexion à ce réseau (à la place de chaque voiture indépendante), pour communication avec toutes les voitures simultanément