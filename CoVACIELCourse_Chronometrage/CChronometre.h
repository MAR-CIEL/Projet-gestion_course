#pragma once
#include <iostream> // Pour les cin, cout, getline
#include <string> // Pour creer des variables string
#include <vector>
#include <map>
#include <chrono> // Pour le temps (high_resolution_clock, duration)
#include <windows.h> // Pour Sleep
#include <fstream>

using namespace std;
using namespace std::chrono;
/**
* @file CChronometrage.h
* @author Jalil BOUGOFFA
* @date Debut : 15/1/2026
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

    HANDLE hSerial; 
    high_resolution_clock::time_point start; 
    map<string, int> tagsVoitures;
    map<int, vector<string>> tempsParTours;

public:
    CChronometre();
    ~CChronometre();
    void EnregistrementVoiture(int nb); 
    void CalculerTempsCourse();
    bool InitialiserPortSerie(string portName);
    string FormaterTemps(double tempsTotal);
};