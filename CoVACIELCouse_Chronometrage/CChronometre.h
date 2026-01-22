#pragma once
/**
* @file CChronometrage.h
* @author Jalil BOUGOFFA
* @date Début : 15/1/2026
* @version 1.0
* @brief Fichier ayant les prototypes des fonctions du programme C++
* @class CChronometre
* Represente la solution de chronometrage
*/
class CChronometre
{
private:
	int minutes;
	int secondes;
	int milliemesSecondes;
	int nombreVoitures;
public:
	void CalculerTempsCourse();
	void FonctionTest();
	int EnregistrementVoiture();
};

