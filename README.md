# CoVACIEL 2026 - Chef Chronométrage et UX (Candidat 3)
### Cette branche contient tout le travail lié à la gestion du chronométrage, à l'affichage des temps et des informations spectateurs du projet CoVACIEL 2026

## Structure du Projet
- Partie **chronométrage**
  - ./CoVACIELCourse_Chronometrage
    - **CChronometre.cpp**: Fichier de définition des fonctions liées à la solution de chronométrage
    - **CChronometre.h**: Fichier de prototype des fonctions liées à la solution de chronométrage
    - **main.cpp**: Fichier principal de la solution de chronométrage
    - **Doxyfile**: Fichier avec tous les paramètres de Doxygen
    - **COVACIELCourse_Chronométrage.sln**: Fichier solution du programme
    - **COVACIELCourse_Chronométrage.vcxproj(.filters)**: Fichiers projets du programme
    - **vc140.pdb**: Je sais pas (à ne pas toucher)
  - Partie **Affichage UI**
    - **index.php**: Fichier servant à afficher les données liées au temps et aux infos spectateurs
  - **.gitignore**: Ignore des fichiers qui ne doivent pas être commités
  - **README.md**: Ce fichier même, servant à décrire l'utilisation de cette branche du projet CoVACIEL
  - **workspace.code-workspace**: Je sais pas (à ne pas toucher)
 
## Installation et configuration
- Partie **chronométrage**
  - Etant une partie d'un logiciel plus grand, la méthode ne sera pas notée ici.

## Fonctionnalités Implémentées
- Partie **chronométrage**
  - Permet de chronométrer une course de voitures autonomes. On rentre le nombre de voitures participants à la course et quand une voiture passe la ligne d'arrivée, son chrono est affichée. Le chrono tourne en continu.

## Gestion de version
Ce projet utilise **Git** et le site **GitHub** pour assurer la traçabilité des modifications:
  - **Branche Bou** (cette branche): Branche de développement isolée pour le candidat 3
  - **Commits Documentés**: Chaque étape est validée par un message explicite.

*Projet réalisé pour l'examen du BTS CIEL - Session 2026*
