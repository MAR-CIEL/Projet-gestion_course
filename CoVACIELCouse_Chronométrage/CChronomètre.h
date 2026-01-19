#pragma once
class CChronomètre
{
private:
	int minutes;
	int secondes;
	int dixiemeSecondes;
	int nombreVoitures;
public:
	void AfficherTemps();
	void CalculerTempsCourse();
	void FonctionTest();
	int EnregistrementVoiture();
};

