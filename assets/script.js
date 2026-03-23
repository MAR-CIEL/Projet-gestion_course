// --- DONNÉES STATIQUES ---
const fleetData = [
    { id: 1, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 2, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 3, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
];

let currentIndex = 0;

// --- FONCTION D'AFFICHAGE DES FICHES ---
function refreshCarUI() {
    const car = fleetData[currentIndex];
    const animElements = [
        document.querySelector(".titre-voiture"),
        document.querySelector(".vitesse"),
        document.querySelector(".energie"),
        document.querySelector(".donnees-tours")
    ];

    animElements.forEach(el => {
        if (el) {
            el.classList.remove('slide-in-right');
            void el.offsetWidth;
        }
    });

    document.getElementById("titre-voiture").textContent = `Voiture n°${car.id}`;
    
    // Mise à jour de la vitesse et de l'énergie avec les dernières données reçues
    document.getElementById("txt-vitesse").textContent = car.vitesse;

    const energyBar = document.getElementById("barre-energie");
    if (energyBar) {
        energyBar.style.width = `${car.energie}%`;
        energyBar.textContent = `${car.energie}%`;
        energyBar.className = "progress-bar " + (car.energie > 50 ? "bg-success" : car.energie > 20 ? "bg-warning" : "bg-danger");
    }

    const tourSpans = document.querySelectorAll(".donnees-tours span");
    car.tours.forEach((time, i) => {
        if (tourSpans[i]) tourSpans[i].textContent = time;
    });

    setTimeout(() => {
        animElements.forEach(el => {
            if (el) el.classList.add('slide-in-right');
        });
    }, 50);
}

// --- COMMUNICATION AVEC LE C++ ET LA VM (Via data.php fusionné) ---
function fetchLiveRaceData() {
    // Le paramètre ?t= empêche le navigateur de mettre les données en cache
    fetch('data.php?t=' + Date.now())
        .then(response => response.json())
        .then(data => {
            // 1. GESTION DU CHRONO ET DES TOURS (C++)
            if (data.chrono) {
                const parts = data.chrono.split('|');
                if (parts.length >= 2) {
                    document.getElementById("txt-chrono").textContent = parts[0];

                    const allCars = parts[1].split(';');
                    allCars.forEach((carString, index) => {
                        const times = carString.split(',');
                        if (fleetData[index]) fleetData[index].tours = times;
                    });
                }
            }

            // 2. GESTION DE LA TÉLÉMÉTRIE (VM UBUNTU)
            if (data.telemetrie) {
                // On met à jour la vitesse dans l'objet de la voiture actuelle
                // Note : Ici on applique la vitesse de la VM à TOUTES les fiches ou à la fiche en cours
                // Si la VM ne gère qu'une seule voiture (la 1 par exemple) :
                fleetData[currentIndex].vitesse = data.telemetrie.vitesse + " km/h";

                // Calcul du pourcentage batterie (ex: 11V à 12.6V)
                let tension = data.telemetrie.batterie;
                let pourcentage = Math.max(0, Math.min(100, ((tension - 11) / (12.6 - 11)) * 100));
                fleetData[currentIndex].energie = Math.round(pourcentage);
            }

            // 3. RAFRAICHISSEMENT VISUEL IMMÉDIAT (SANS ANIMATION)
            const currentCar = fleetData[currentIndex];
            
            // Update Vitesse / Energie
            document.getElementById("txt-vitesse").textContent = currentCar.vitesse;
            const energyBar = document.getElementById("barre-energie");
            if (energyBar) {
                energyBar.style.width = currentCar.energie + "%";
                energyBar.textContent = currentCar.energie + "%";
            }

            // Update Tours
            const spans = document.querySelectorAll(".donnees-tours span");
            if (spans.length > 0) {
                currentCar.tours.forEach((time, i) => {
                    if (spans[i]) spans[i].textContent = time;
                });
            }
        })
        .catch(err => console.error("Erreur fetch:", err));
}

document.addEventListener("DOMContentLoaded", () => {
    refreshCarUI();
    // Rotation des fiches toutes les 5 secondes
    setInterval(() => {
        currentIndex = (currentIndex + 1) % fleetData.length;
        refreshCarUI();
    }, 5000);
    
    // Lecture des données toutes les 200ms
    setInterval(fetchLiveRaceData, 200);
});