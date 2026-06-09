// --- DONNÉES DES VOITURES ---
const fleetData = [
    { id: 1, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 2, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 3, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
];

let currentIndex = 0;
let courseTerminee = false;

window.addEventListener('load', () => {
    fleetData.forEach(car => {
        car.tours = ["--:--:--", "--:--:--", "--:--:--", "--:--:--"];
    });
    refreshCarUI(false);
});

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

function verifierCourseFinie() {
    // Vérifier si toutes les voitures ont terminé leurs 4 tours
    const toutesTerminees = fleetData.every(car => 
        car.tours.filter(t => t !== "--:--:--").length === 4
    );
    
    if (toutesTerminees && !courseTerminee) {
        courseTerminee = true;
        const txtChrono = document.getElementById("txt-chrono");
        if (txtChrono) {
            txtChrono.textContent = "COURSE TERMINÉE ! 🏁";
            txtChrono.style.color = "#4ade80";
        }
        
        // Afficher un message de fin
        const classementEl = document.querySelector(".classement p");
        if (classementEl) {
            classementEl.innerHTML = "🏆 TOUS LES TOURS COMPLÉTÉS ! 🏆";
        }
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
            if (txtChrono && !courseTerminee) txtChrono.textContent = chronoPart;

            // ===== EXTRACTION DES TEMPS (TOUS LES TEMPS) =====
            // Splitter par ; pour obtenir les 3 voitures
            if (toursPart) {
                const toutesLesVoitures = toursPart.split(';');

                toutesLesVoitures.forEach((v, carIndex) => {
                    if (carIndex >= fleetData.length) return;
                    if (!v || v.trim() === "") return;

                    // Splitter par virgule pour obtenir les 4 tours
                    const tempsTours = v.trim().split(',').map(t => t.trim());

                    // On doit avoir exactement 4 temps
                    if (tempsTours.length === 4) {
                        for (let tourIndex = 0; tourIndex < 4; tourIndex++) {
                            const nouveauTemps = tempsTours[tourIndex];
                            // Figeage : on enregistre définitivement les vrais temps
                            if (nouveauTemps && nouveauTemps !== "--:--:--") {
                                fleetData[carIndex].tours[tourIndex] = nouveauTemps;
                            }
                        }
                    }
                });

                updateClassement();
                verifierCourseFinie();
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

            // ===== RAFRAÎCHIR L'AFFICHAGE =====
            refreshCarUI(false);
        })
        .catch(err => {
            const txtChrono = document.getElementById("txt-chrono");
            if (txtChrono) txtChrono.textContent = "Erreur: " + err.message;
        });
}

document.addEventListener("DOMContentLoaded", () => {
    refreshCarUI(false);

    // Rotation automatique entre les 3 voitures toutes les 5 secondes
    setInterval(() => {
        currentIndex = (currentIndex + 1) % fleetData.length;
        refreshCarUI(true);
    }, 500);

    // Rafraîchissement des données toutes les 100ms
    setInterval(fetchLiveRaceData, 100);
});