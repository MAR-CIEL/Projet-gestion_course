// --- DONNÉES DES VOITURES ---
const fleetData = [
    { id: 1, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 2, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 3, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
];

let currentIndex = 0;

function refreshCarUI(triggerAnim = false) {
    const car = fleetData[currentIndex];
    if (!car) return;

    if (triggerAnim) {
        [".titre-voiture", ".vitesse", ".energie", ".temps-tours"].forEach(sel => {
            const el = document.querySelector(sel);
            if (el) { 
                el.classList.remove('slide-in-right'); 
                void el.offsetWidth; 
                el.classList.add('slide-in-right'); 
            }
        });
    }

    const titreVoiture = document.querySelector(".titre-voiture");
    if (titreVoiture) titreVoiture.textContent = `Voiture n°${car.id}`;

    const txtVitesse = document.getElementById("txt-vitesse");
    if (txtVitesse) txtVitesse.textContent = car.vitesse;

    const energyBar = document.getElementById("barre-energie");
    if (energyBar) {
        const pct = car.energie;
        energyBar.style.width = `${pct}%`;
        energyBar.textContent = `${pct}%`;
        energyBar.classList.remove("bg-success", "bg-warning", "bg-danger");
        if (pct > 50) energyBar.classList.add("bg-success");
        else if (pct > 20) energyBar.classList.add("bg-warning");
        else energyBar.classList.add("bg-danger");
    }

    const tourSpans = document.querySelectorAll(".temps-tours .donnees-tours span");
    if (tourSpans.length >= 4) {
        for (let i = 0; i < 4; i++) {
            tourSpans[i].textContent = car.tours[i];
            tourSpans[i].classList.toggle("tour-complete", car.tours[i] !== "--:--:--");
        }
    }
}

function updateClassement() {
    const scores = fleetData
        .map(car => ({ id: car.id, tours: car.tours.filter(t => t !== "--:--:--").length }))
        .sort((a, b) => b.tours - a.tours);

    const classementEl = document.querySelector(".classement p");
    if (classementEl) {
        classementEl.innerHTML = scores
            .map((s, i) => `${i + 1}. Voiture ${s.id} — ${s.tours} tour${s.tours > 1 ? "s" : ""}`)
            .join("<br/>");
    }
}

function fetchLiveRaceData() {
    fetch('data.php')
        .then(r => r.json())
        .then(data => {
            if (!data || !data.chrono) return;

            // ===== EXTRACTION DU CHRONO =====
            const sepIndex = data.chrono.indexOf('|');
            if (sepIndex === -1) return;

            const chronoPart = data.chrono.substring(0, sepIndex).trim();
            const toursPart = data.chrono.substring(sepIndex + 1).trim();

            const txtChrono = document.getElementById("txt-chrono");
            if (txtChrono) txtChrono.textContent = chronoPart;

            // ===== EXTRACTION DES TEMPS (ULTRA ROBUSTE) =====
            // On cherche TOUS les temps au format HH:MM:SS ou --:--:--
            // Peu importe comment ils sont séparés
            if (toursPart) {
                // Chercher tous les temps avec une regex
                const allTimes = toursPart.match(/\d{2}:\d{2}:\d{3}|--:--:--/g) || [];
                
                // On devrait avoir 12 temps (4 tours × 3 voitures)
                if (allTimes.length >= 12) {
                    // Distribuer les temps aux voitures
                    for (let voitureIdx = 0; voitureIdx < 3; voitureIdx++) {
                        for (let tourIdx = 0; tourIdx < 4; tourIdx++) {
                            const timeIdx = voitureIdx * 4 + tourIdx;
                            const nouveauTemps = allTimes[timeIdx];
                            
                            if (nouveauTemps && nouveauTemps !== "--:--:--") {
                                fleetData[voitureIdx].tours[tourIdx] = nouveauTemps;
                            }
                        }
                    }
                }

                updateClassement();
            }

            // ===== TÉLÉMÉTRIE =====
            if (data.telemetrie) {
                const vitesse = data.telemetrie.vitesse || 0;
                fleetData[currentIndex].vitesse = vitesse + " km/h";

                const tension = data.telemetrie.tension_batterie || 0;
                if (tension > 0) {
                    const pct = Math.max(0, Math.min(100, ((tension - 11) / (12.6 - 11)) * 100));
                    fleetData[currentIndex].energie = Math.round(pct);
                }
            }

            refreshCarUI(false);
        })
        .catch(err => {
            const txtChrono = document.getElementById("txt-chrono");
            if (txtChrono) txtChrono.textContent = "Erreur: " + err.message;
        });
}

document.addEventListener("DOMContentLoaded", () => {
    refreshCarUI(false);

    setInterval(() => {
        currentIndex = (currentIndex + 1) % fleetData.length;
        refreshCarUI(true);
    }, 5000);

    setInterval(fetchLiveRaceData, 100);
});
