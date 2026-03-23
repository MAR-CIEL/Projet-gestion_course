# Structure du Projet
---
- Partie **chronométrage**
  - ./CoVACIELCourse_Chronometrage
    - **CChronometre.cpp**: Fichier de définition des fonctions liées à la solution de chronométrage
    - **CChronometre.h**: Fichier de prototype des fonctions liées à la solution de chronométrage
    - **main.cpp**: Fichier principal de la solution de chronométrage
    - **Doxyfile**: Fichier avec tous les paramètres de Doxygen
    - **CoVACIELCourse_Chronometrage.sln**: Solution du programme
- Partie **Affichage UI**
  - **index.php**: Fichier de base de l'IHM - l'interface affichant des données pour les spectateurs et la caméra
  - **data.php**: Fichier servant de connecteur entre le code en C++ et l'IHM
  - **style.css**: Fichier servant à styliser l'IHM
  - **script.js**: Fichier communiquant avec le code en C++ via l'intermédiaire de data.php et utilisé pour des animations sur l'IHM
- **.gitignore**: Ignore des fichiers qui ne doivent pas être sur GitHub
- **course.txt**: Fichier texte où sont générées des données par le code en C++ et servant à être affichées sur l'IHM
- **README.md**: Ce fichier même, servant à décrire l'utilisation et le rôle de cette branche sur le projet CoVACIEL

# Installation et configuration
---
Pour faire fonctionner cette solution. Il faut suivre ce protocole:
1. Lancer l'IHM via WAMP
2. Lancer le .exe du programme de chronométrage au lancement de la course
3. Placer les tags sur les voitures concurrentes et à chaque fois qu'une voiture passe la ligne d'arrivée pour finir un tour, son temps est inscrit sur l'IHM.
4. Quand la course est terminée, arrêter le .exe

# Fonctionnalités Implémentées
---
- Partie **chronométrage**
  Un programme en C++ servant à chronométrer une course de voitures autonomes. Le chrono tourne en continu. Le programme détecte quand un tag passe à proximité d'un lecteur et inscrit son temps
  depuis le début de la course.
- Partie **Affichage UI**
  Affiche une IHM via WAMP récupérant des données dans une base de données externe et le code en C++ et affichant ces mêmes données dans une section dédiée. L'IHM affiche 2 caméras, des informations sur les voitures
  concurrentes (vitesse, batterie, temps par tour) avec un balayage d'une voiture à l'autre toutes les 10 secondes et des informations sur la course en elle-même (chronomètre, classement).
