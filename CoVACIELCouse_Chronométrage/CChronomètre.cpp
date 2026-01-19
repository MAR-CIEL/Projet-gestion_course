#include "CChronomètre.h"
#include <chrono>
#include <iomanip>
#include <iostream>
#include <string>
#include <thread>

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

int CChronomètre::EnregistrementVoiture()
{
	// Demander à l'utilisateur de rentrer le nombre de voitures qui vont participer à la course
	cout << "Combien de voitures vont participer à la course?";
	cin >> nombreVoitures;
	cin.ignore(numeric_limits<streamsize>::max(), '\n');

	return nombreVoitures;
}

void CChronomètre::CalculerTempsCourse()
{
	string a;
	// Demander à l'utilisateur d'appuyer sur Entree pour demarrer la course
	cout << "La course va commencer. Appuie sur Entree pour enclencher le top depart!" << endl;
	getline(cin, a);
	
	// Démarrer le chrono
	auto start = high_resolution_clock::now();

	// Faire une boucle qui en continue va afficher le temps de la course

	// Demander à l'utilisateur d'appuyer sur Entree à chaque fois qu'une voiture franchie la ligne d'arrivée (faire une boucle en fonction du nombre de voitures)
	cout << "Course en cours... Appuie sur Entree à chaque fois qu'une voiture franchie la ligne d'arrivée." << endl;
	getline(cin, a);
	auto end_car = high_resolution_clock::now();
	duration<double> diff_car = end_car - start;
	cout << "Temps : " << fixed << setprecision(3) << diff_car.count() << " s" << endl;


	// Arrêter le chrono (quand la dernière voiture passe la ligne d'arrivée)
	cout << "Course en cours... Appuie sur Entree quand la dernière voiture atteins la ligne d'arrivée pour arrêter le chrono." << endl;
	getline(cin, a);
	auto end = high_resolution_clock::now();

	// Calculer la durée en secondes
	duration<double> diff = end - start;

	// Afficher le temps
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

