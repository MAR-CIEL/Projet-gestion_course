#include "CChronomètre.h" // Fichier d'en tête
#include <chrono> // Pour le temps (high_resolution_clock, duration)
#include <conio.h> // Pour le kbhit et le getch
#include <iomanip> // Pour le setprecision
#include <iostream> // Pour les cin, cout, getline
#include <string> // Pour créer des variables string
#include <windows.h> // Pour Sleep

using namespace std;
using namespace std::chrono;

int CChronomètre::EnregistrementVoiture()
{
	// Demander à l'utilisateur de rentrer le nombre de voitures qui vont participer à la course
	cout << "Combien de voitures vont participer à la course?";
	cin >> nombreVoitures;
	cin.ignore((numeric_limits<streamsize>::max)(), '\n');

	return nombreVoitures;
}

void CChronomètre::CalculerTempsCourse()
{
	// Demander à l'utilisateur d'appuyer sur Entree pour demarrer la course
	cout << "La course va commencer. Appuie sur Entrée pour enclencher le top départ!" << endl;
	_getch();
	
	// Démarrer le chrono
	auto start = high_resolution_clock::now();

	for (int i = 0; i < nombreVoitures; i++) 	// Boucle gérant le chrono en fonction du nombre de voitures participant à la course
	{
		// Demander à l'utilisateur d'appuyer sur Entree à chaque fois qu'une voiture franchie la ligne d'arrivée
		cout << "Course en cours... Appuie sur Entrée à chaque fois qu'une voiture franchie la ligne d'arrivée." << endl; 
		
		while (!_kbhit()) // Tant que aucune touche n'est pressée, le chrono défile
		{
			// Variables servant pour les calculs de temps
			auto actualTimer = high_resolution_clock::now();
			duration<double> diffTimer = actualTimer - start;
			double tempsTotal = diffTimer.count();

			minutes = static_cast<int>(tempsTotal) / 60; // Calcul du nombre de minutes
			secondes = static_cast<int>(tempsTotal) % 60; // Calcul du nombre de secondes
			milliemesSecondes = static_cast<int>((tempsTotal - static_cast<int>(tempsTotal)) * 1000); // Calcul du nombre de millièmes de secondes

			// Affichage du chrono de la couse
			cout << "\rTemps : " << setfill('0') << setw(2) << minutes << " min : " << setfill('0') << setw(2) << secondes << " s : " << setfill('0') << setw(3) << milliemesSecondes << " ms" << flush;
			Sleep(10); // Pause de 10 ms pour éviter de surcharger le processeur
		}
		_getch();

		// Affichage du chrono de chaque voiture
		cout << "\nVoiture " << i + 1 << " : " << setfill('0') << setw(2) << minutes << " min : " << setfill('0') << setw(2) << secondes << " s : " << setfill('0') << setw(3) << milliemesSecondes << " ms" << endl;
	}
	cout << "Fin de la course!" << endl;
}

void CChronomètre::FonctionTest()
{

	/*
		h = heure
		min = minute
		s = seconde
		ms = milliseconde
		us = microseconde
		ns = nanoseconde
	*/

}

