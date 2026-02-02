# COVACIEL 2026 Simulation Full Locale (WAMP) Télémétrie & UX
### Cette solution deviendra obsolète au vu de sa prochaine évolution vers d'autres technologies plus avancées pour le projet (plus de BDD en local)
## Résumé Général du Processus :
	* Script Simulateur Python remplace la voiture réelle :
		* Génère chaque seconde des données télémétriques aléatoires mais cohérentes
		* Se connecte immédiatement au serveur MySQL local (WAMP) pour insérer ces valeurs dans la table "telemetrie" de la BDD créée initialement
	* API PHP de distribution sert de "serveur de données" pour l'interface web :
		* Interroge la BDD pour récupérer uniquement la toute dernière ligne enregistrée (la plus récente)
		* Transforme ces informations brutes en format JSON (facilement compris par le navigateur)
	* Affichage sur le Dashboard web, la partie visible qui valide les tâches ID 15 et 24 (Recette) :
		* Script JavaScript appelle l'API PHP toutes les secondes
		* Actualise les compteurs, la barre d'énergie et dessine la courbe en temps réel avec Chart.js (toutes les secondes)
		* Si la vitesse est à 0, il déclenche l'alerte clignotante rouge définie dans le CSS principal
		* Fait une requête HTTP au serveur du Raspberry PI de la voiture pour l'affiche en direct du flux vidéo de la caméra embarquée
		* Si pas de signal vidéo, affiche une image de secours
	* Doublure de secours :
		* API PHP génère des données aléatoires à chaque fois qu'il est appelé et les renvoie directement en JSON
		* Par rapport au script Python, il attend qu'on lui demande des données et il ne stocke rien, les données sont volatiles
	* Bonus : Gestion des résultats de la course avec une table dédiée dans la BDD et le même processus derrière (pas de gestion d'affichage dans la solution)
	
## Arborescence :
	* Script Python : collecte_telemetrie.py
	* BDD : covaciel_gestion
	* API PHP principale : api/api_data.php
	* Dashboard web : index.php
	* Script JavaScript : assets/script.js
	* CSS principal : assets/style.css
	* Flux vidéo du serveur Raspberry : http://192.168.1.50:8080/stream.mjpg
	* Image de secours : img/no-signal.jpg
	* API PHP doublure de secours : api/simulateur_voiture.php
	* Table BDD de gestion des résultats : resultat
	* API PHP de gestion des résultats : api/get_ranking.php
	
## Protocole de test à suivre :
	* Démarrer Wampserver64 (vérifier son fonctionnement avec l'affichage du logo en vert dans le barre des tâches)
	* Ouvrir une console PowerShell dans le répertoire et exécuter le script Python : python collecte_telemetrie.py (vérifier le fonctionnement avec l'affichage du message du succès du renvoi des données vers la BDD)
	* Ouvrir un navigateur et taper : localhost/covaciel_sln_finale
	* Constater le fonctionnement avec l'affichage de chaque valeur télémétrique et leur mise à jour chaque seconde (idem pour la courbe chart), et l'affichage du flux vidéo et de l'image de secours en cas d'absence du signal
	* Avec doublure de secours, modifier dans script.js la ligne _const URL_LOCALE = 'api/api_data.php';_ par _const URL_LOCALE = 'simulateur_voiture.php';_ avant de commencer les tests
	
## Suggestion d'évolutibilité :
	* Utiliser un serveur Ubuntu sur une VM pour la BDD et le service MySQL pour exécution de la solution à distance depuis n'importe quel poste tant qu'il possède les privilèges admin ainsi que les codes sources