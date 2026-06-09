#pragma once
#include <iostream>
#include <string>
#include <vector>
#include <map>
#include <chrono>
#include <windows.h>
#include <fstream>

class CChronometre
{
private:
    int minutes;
    int secondes;
    int milliemesSecondes;
    int nombreVoitures;

    HANDLE hSerial;
    std::chrono::high_resolution_clock::time_point start;
    std::map<std::string, int> tagsVoitures;
    std::map<int, std::vector<std::string> > tempsParTours; // Espace entre les > pour les vieux compilateurs
    std::map<int, std::chrono::high_resolution_clock::time_point> dernierPassage;

public:
    CChronometre();
    ~CChronometre();
    void EnregistrementVoiture(int nb);
    void CalculerTempsCourse();
    bool InitialiserPortSerie(std::string portName);
    std::string FormaterTemps(double tempsTotal);
};