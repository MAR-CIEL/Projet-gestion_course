/**
 * @file CChronometrage.cpp
 * @author Jalil BOUGOFFA
 * @date D�but: 15/1/2026
 * @version 1.0
 * @brief Fichier ayant les d�finitions des fonctions du programme C++
*/

#include "CChronometre.h" // Fichier d'en t�te
#include <iomanip>
#include <sstream>
#include <fstream>
#include <iostream>

using namespace std;
using namespace std::chrono;

CChronometre::CChronometre() {
    hSerial = INVALID_HANDLE_VALUE;
    minutes = 0;
    secondes = 0;
    milliemesSecondes = 0;
    nombreVoitures = 0;

    tagsVoitures["0004E2A1B2C3"] = 1;
    tagsVoitures["0004E2A1B2D4"] = 2;
    tagsVoitures["0004E2A1B2E5"] = 3;
}

CChronometre::~CChronometre() {
    if (hSerial != INVALID_HANDLE_VALUE) CloseHandle(hSerial);
}

/**
* @fn int CChronometre::EnregistrementVoiture()
* @brief Compter le nombre de voitures qui vont participer a la course
*/
void CChronometre::EnregistrementVoiture(int nb) {
    nombreVoitures = nb;
}

bool CChronometre::InitialiserPortSerie(string portName) {
    hSerial = CreateFileA(portName.c_str(), GENERIC_READ | GENERIC_WRITE, 0, NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
    if (hSerial == INVALID_HANDLE_VALUE) return false;

    DCB dcbSerialParams = { 0 };
    dcbSerialParams.DCBlength = sizeof(dcbSerialParams);
    GetCommState(hSerial, &dcbSerialParams);
    dcbSerialParams.BaudRate = CBR_9600;
    dcbSerialParams.ByteSize = 8;
    dcbSerialParams.StopBits = ONESTOPBIT;
    dcbSerialParams.Parity = NOPARITY;
    SetCommState(hSerial, &dcbSerialParams);
    return true;
}

string CChronometre::FormaterTemps(double tempsTotal) {
    // Ta logique de calcul originale
    minutes = static_cast<int>(tempsTotal) / 60;
    secondes = static_cast<int>(tempsTotal) % 60;
    milliemesSecondes = static_cast<int>((tempsTotal - static_cast<int>(tempsTotal)) * 1000);

    ostringstream oss;
    oss << setfill('0') << setw(2) << minutes << ":"
        << setw(2) << secondes << ":" << setw(3) << milliemesSecondes;
    return oss.str();
}

/**
* @fn void CChronometre::CalculerTempsCourse()
* @brief Chronometrer la course et afficher le classement des vehicules
* @warning Attention, pour le moment la fonction fonctionne en appuyant sur le clavier, par la suite, elle fonctionnera grace au lecteur RFID et aux barrieres infrarouges
*/

void CChronometre::CalculerTempsCourse() {
    start = high_resolution_clock::now();
    char buffer[128];
    DWORD bytesRead;
    bool courseFinie = false;

    while (!courseFinie) {
        if (ReadFile(hSerial, buffer, 127, &bytesRead, NULL) && bytesRead > 0) {
            string trame(buffer, bytesRead);
            if (trame.length() >= 24) {
                string idTag = trame.substr(1, 12);
                if (tagsVoitures.count(idTag)) {
                    int idV = tagsVoitures[idTag];
                    auto actualTimer = high_resolution_clock::now();
                    duration<double> diffTimer = actualTimer - start;

                    if (tempsParTours[idV].size() < 4) {
                        tempsParTours[idV].push_back(FormaterTemps(diffTimer.count()));
                    }

                    int voituresFinies = 0;
                    for (int i = 1; i <= 3; i++) {
                        if (tempsParTours[i].size() >= 4) voituresFinies++;
                    }
                    if (voituresFinies >= 3) courseFinie = true;
                }
            }
        }

        auto actualTimer = high_resolution_clock::now();
        duration<double> diffTimer = actualTimer - start;
        double tempsTotal = diffTimer.count();

        int tourMax = 1;
        for (auto const& [id, tours] : tempsParTours) {
            if ((int)tours.size() > tourMax) tourMax = (int)tours.size();
        }

        // --- ÉCRITURE DANS LE FICHIER TEXTE ---
        // Utilisation de std::ofstream::trunc pour être ultra-spécifique
        ofstream fichier("C:/wamp64/www/projet_covaciel/course.txt", std::ofstream::out | std::ofstream::trunc);
        if (fichier.is_open()) {
            fichier << FormaterTemps(tempsTotal) << "|" << tourMax << "|1-2-3" << endl;
            fichier.close();
        }

        // Debug console
        cout << FormaterTemps(tempsTotal) << "|" << tourMax << "|1-2-3" << endl;

        if (!courseFinie) Sleep(1000);
    }
}