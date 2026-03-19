# COVACIEL 2026 - Solution Signalisation 

### Le principe de la signalisation est l'envoi d'une trame grâce à un module XBEE avec l'affichage de drapeaux grâce à deux Raspberry Pi 3 connectés à des Sense HAT, commandés par une IHM en SSH (TCP) sur un serveur Linux.

# Résumé de la solution de signalisation :
-> Configurer un routeur Wi-Fi et son réseau pour accueillir tous les projets et surtout pouvoir travailler sur des adresses IP locales <br>
-> Configurer et coder les Raspberry Pi afin qu'ils puissent afficher les différents drapeaux (vert et rouge) <br>
-> Coder les Raspberry Pi pour qu'ils puissent communiquer avec le module XBEE et l'IHM <br>
-> Coder sur le serveur Linux local pour vérifier si les Raspberry Pi communiquent avec le serveur <br>
-> Configurer le module XBEE pour qu'il envoie correctement les trames de départ et d'arrêt <br>
-> Assister, aider et contribuer à l'IHM avec le Responsable Système <br>
-> Tester l'IHM pour vérifier si elle communique bien avec les Raspberry Pi 

# Données et outils :
-> Programme sur le premier Raspberry Pi avec un Sense HAT connecté directement dessus : Projet.py <br>
-> Programme sur le deuxième Raspberry Pi avec un Sense HAT également connecté dessus : Script.py <br>
-> Configuration d'un routeur Wi-Fi avec un masque capable d'accueillir 64 hôtes : http://192.168.1.1 <br>
-> Site internet servant d'IHM <br>
-> Serveur Ubuntu LTS 24.04 : 172.17.50.233 <br>
-> Module XBEE <br>
-> Protocole de communication MQTT

# Protocole de test :
-> "Écrire" les Raspberry Pi (vérifier si les Sense HAT fonctionnent bien) <br>
-> Coder sur les deux Raspberry Pi (voir si les deux Pi peuvent se lancer en même temps) <br>
-> Reconfigurer l'adresse IP des machines et des outils (tester la connectivité avec le routeur et le PC) <br>
-> Coder sur la VM (tester si la connectivité avec la VM fonctionne) <br>
-> Configurer le module XBEE (tester si la trame s'envoie correctement) <br>
-> Rajouter des lignes de code dans les Pi (tester si l'envoi de la trame XBEE fonctionne avec les Pi) <br>
-> Relier le protocole MQTT au serveur (vérifier si la connexion entre MQTT et les Pi fonctionne) <br>
-> Relier l'IHM avec le serveur (tester si tout fonctionne ensemble : la connectivité au serveur, l'IHM, les Raspberry Pi, le protocole MQTT) <br>

# Évolutions possibles 
-> Configurer le module XBEE pour qu'il puisse envoyer les trames des drapeaux (jaune, noir, bleu) <br>
-> Développer la solution pour que les véhicules réagissent aux différents drapeaux 
