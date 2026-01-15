#include "CChronomètre.h"
#include <iostream>

using namespace std;

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
