#include "CChronomètre.h"
#include <iostream>
#include <chrono>
#include <iomanip>

using namespace std;
using namespace std::chrono;

void CChronomètre::AfficherTemps()
{
	cout << "Nombre de minutes: ";
	cin >> minutes;
	cout << "Nombre de secondes: ";
	cin >> secondes;
	cout << "Nombre de dixièmes de secondes: ";
	cin >> dixiemeSecondes;
	cout << "Temps: " << minutes << " : " << secondes << " : " << dixiemeSecondes << endl;
}

void CChronomètre::CalculerTempsCourse()
{
	// 1. Démarrer le chrono
	auto start = high_resolution_clock::now();

	cout << "Course en cours... Appuyez sur Entree pour finir." << endl;
	cin.get();

	// 2. Arrêter le chrono
	auto end = high_resolution_clock::now();

	// 3. Calculer la durée en secondes (le "secret" pour la simplicité)
	duration<double> diff = end - start;

	// 4. Afficher le résultat
	cout << "Temps : " << fixed << setprecision(3) << diff.count() << " s" << endl;

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
