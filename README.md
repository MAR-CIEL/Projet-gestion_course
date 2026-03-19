# COVACIEL 2026 Solution Signalisation 
### Le principe de la signalisation est l'envoie d'une trame grace à un module XBEE avec l'affichage de drapeau grace a deux Raspberry PI 3 connecter a des Sense HAT commander par une IHM en ssh (TCP) sur un serveur Linux 
# Résumé de la solution de signialisation :
-> Configurer un routeur wifi et son réseaux pour accuillir tout les projet et surtout pouvoir travaillé sur des adresse ip local
-> Configurer et codée les Raspberry PI afin qu'il puisse afficher les différent drapeau (vert et rouge)
-> Coder les Raspberry pi pour qu'il puisse communiquer avec le module XBEE et l'IHM
-> Coder sur le serveur linux local pour voir si les Raspberry PI communique avec le serveur
-> Configurer le module XBEE pour qu'il envoie bien la trame du départ et d'arrêt
-> Assister, aider et contribuer à l'IHM avec le Responsable Système
-> Tester l'IHM pour voir si elle communique bien avec les raspberry pi

# Données et outils :
-> Programme sur le premier raspberry pi avec un sense HAT connécter directement dessus : Projet.py
-> Programme sur le deuxieme raspberry pi avec un sense HAT également connecté dessus : Script.py
-> Configuration d'un routeur wifi avec un masque capable d'accueilir 64 hôte : http://192.168.1.1
-> Site internet qui va servir d'IHM
-> Serveur ubuntu lts 24.04 : 172.17.50.233 
-> module XBEE
-> Protocole de communication MQTT

# Protocole de teste :
-> "Ecrire" les rapsberry pi (vérifier si les sens HAT fonctionne bien)
-> Coder sur les deux raspberry pi (coir sir les deux pi peuvent se lancer en même temps)
-> Reconfigurer l'adresse ip des machine et outils (tester la connectiviter avec le routeur et le pc)
-> Coder sur la VM (tester si la connectivité avec la vm marche)
-> Configurer le module XBEE (tester si la trame s'envoie comme il faut)
-> Rajouter des ligne de codes dans les pi (tester si l'envoie de la trame XBEE marche avec les pi)
-> Relier le protocle MQTT aux serveur (vérifier si la connexion entre le mqtt et les pi fonctionne)
-> Relier l'IHM avec le serveur (tester si tout fonctionne ensemble, la connectivité au serveur, l'IHM, les raspberry pi, le protocle MQTT)

# Evolution possible 
-> Configurer le module XBEE pour qu'il puisse envoyer les tram des drapeau (jaune, noir, bleu)
-> Développer la solution pour que les vehicule réagisse aux différent drapeau 
