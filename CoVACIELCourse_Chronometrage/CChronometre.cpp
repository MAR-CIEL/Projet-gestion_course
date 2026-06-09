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
    //tagsVoitures["30303035383030316163616235323031"] = 1;
    tagsVoitures["058001acab52"] = 1; // Ta 1ère carte
    tagsVoitures["058002340326"] = 2; // Ta 2ème carte
    tagsVoitures["058002f5e9af"] = 3; // Ta 3ème carte
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
    tempsParTours.clear();
    for (int i = 1; i <= nb; i++)
    {
        tempsParTours[i] = vector<string>(4, "--:--:--");
    }
}

bool CChronometre::InitialiserPortSerie(string portName) {
    hSerial = CreateFileA(portName.c_str(), GENERIC_READ | GENERIC_WRITE, 0, NULL, OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
    if (hSerial == INVALID_HANDLE_VALUE) return false;

    DCB dcbSerialParams = { 0 };
    dcbSerialParams.DCBlength = sizeof(dcbSerialParams);
    if (!GetCommState(hSerial, &dcbSerialParams)) return false;
    dcbSerialParams.BaudRate = CBR_38400;
    dcbSerialParams.ByteSize = 8;
    dcbSerialParams.StopBits = ONESTOPBIT;
    dcbSerialParams.Parity = NOPARITY;
    if (!SetCommState(hSerial, &dcbSerialParams)) return false;

    COMMTIMEOUTS timeouts = { 0 };
    timeouts.ReadIntervalTimeout = MAXDWORD;
    timeouts.ReadTotalTimeoutConstant = 0;
    timeouts.ReadTotalTimeoutMultiplier = 0;
    SetCommTimeouts(hSerial, &timeouts);

    return true;
}

string CChronometre::FormaterTemps(double tempsTotal)
{
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
    ofstream fichier("C:/wamp64/www/projet_covaciel/course.txt", ios::trunc);
    if (fichier.is_open()) {
        fichier << "00:00:000|--:--:--,--:--:--,--:--:--,--:--:--;--:--:--,--:--:--,--:--:--,--:--:--;--:--:--,--:--:--,--:--:--,--:--:--";
        fichier.close();
    }
    
    start = high_resolution_clock::now();
    char buffer[128];
    DWORD bytesRead;
    string accumulateur = "";
    auto lastWrite = high_resolution_clock::now();

    // Map statique pour mémoriser le dernier passage de chaque voiture (anti-rebond)
    // On l'initialise une seule fois au début de la course
    map<int, high_resolution_clock::time_point> dernierPassage;

    while (true) {
        if (ReadFile(hSerial, buffer, 127, &bytesRead, NULL) && bytesRead > 0) {

            for (DWORD i = 0; i < bytesRead; i++)
            {
                stringstream ss;
                ss << (unsigned char)buffer[i];
               // ss << hex << setw(2) << setfill('0') << (int)(unsigned char)buffer[i];
                accumulateur += ss.str();
            }

            if (accumulateur.length() > 0)
            {
                cout << "Analyse trame : " << accumulateur << endl;
                cout << "DEBUG - Accumulateur : " << accumulateur << endl;
                for (auto const& pair : tagsVoitures)
                {
                    if (accumulateur.find(pair.first) != string::npos) {
                        cout << "TAG TROUVE : " << pair.first << "Voiture " << pair.second << endl;

                        int idVoiture = pair.second;
                        auto now = high_resolution_clock::now();
                        double tempsCourseDouble = duration_cast<duration<double>>(now - start).count();

                        // Antirebond : 10 secondes minimum entre 2 passages
                        if (dernierPassage.find(idVoiture) == dernierPassage.end() ||
                            duration_cast<milliseconds>(now - dernierPassage[idVoiture]).count() >= 5000) {

                            // Compter les tours déjà enregistrés
                            int tourActuel = 0;
                            for (int t = 0; t < 4; t++) {
                                if (tempsParTours[idVoiture][t] != "--:--:--") tourActuel++;
                            }

                            if (tourActuel < 4) {
                                tempsParTours[idVoiture][tourActuel] = FormaterTemps(tempsCourseDouble);
                                cout << ">>> VICTOIRE : Voiture " << idVoiture << " Tour " << (tourActuel + 1) << " = " << FormaterTemps(tempsCourseDouble) << endl;
                            }

                            dernierPassage[idVoiture] = now;
                        }
                    }
                }

                if (accumulateur.length() > 100) accumulateur = "";
            }
        }

        auto currentTime = high_resolution_clock::now();
        if (duration_cast<milliseconds>(currentTime - lastWrite).count() > 150)
        {
            ofstream fichier("C:/wamp64/www/projet_covaciel/course.txt", ios::trunc);
            if (fichier.is_open())
            {
                fichier << FormaterTemps(duration_cast<duration<double>>(currentTime - start).count()) << "|";
                for (int i = 1; i <= nombreVoitures; i++)
                {
                    for (int t = 0; t < 4; t++)
                    {
                        if (t < tempsParTours[i].size())
                        {
                            fichier << tempsParTours[i][t];
                        } else {
                            fichier << "--:--:--";
                        }
                        if (t < 3) fichier << ",";
                    }
                    if (i < nombreVoitures) fichier << ";";
                }
                fichier.close();
            }
            lastWrite = currentTime;
        }

        bool tousTermines = true;
        for (int i = 1; i <= nombreVoitures; i++) {
            int toursCompletes = 0;
            for (int t = 0; t < 4; t++) {
                if (tempsParTours[i][t] != "--:--:--") toursCompletes++;
            }
            if (toursCompletes < 4) {
                tousTermines = false;
                break;
            }
        }

        if (tousTermines) {
            cout << "\n COURSE TERMINEE !\n";
            cout << "Tous les tours ont ete completes. Arrêt du programme.\n";
            exit(0); // Arrêter le programme
        }

        Sleep(5);
    }
}