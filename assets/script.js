// --- DONNÉES STATIQUES (Gardées pour les fiches individuelles) ---
const fleetData = [
    { id: 1, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 2, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
    { id: 3, vitesse: "0 km/h", energie: 0, tours: ["--:--:--", "--:--:--", "--:--:--", "--:--:--"] },
];

let currentIndex = 0;

// --- FONCTION D'AFFICHAGE DES FICHES (Rotation toutes les 5s) ---
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

// --- COMMUNICATION AVEC LE C++ (Via data.php) ---
function fetchLiveRaceData() {
    fetch('data.php?nb=4')
        .then(response => response.json())
        .then(data => {
            updateRaceUI(data);
        })
        .catch(err => console.error("Erreur de liaison PHP:", err));
}

function updateRaceUI(data) {
    const chronoElement = document.getElementById("txt-chrono");
    const classementElement = document.querySelector(".classement p");
    const toursSpans = document.querySelectorAll(".donnees-tours span");

    if (data.success && data.chrono) {
        const fragments = data.chrono.split('|');

        if (fragments.length >= 3) {
            const tempsReel = fragments[0];
            const tourReel = parseInt(fragments[1]);
            const ordreIds = fragments[2].split('-');

            chronoElement.textContent = tempsReel;

            if (tourReel >= 1 && tourReel <= 4) {
                fleetData[currentIndex].tours[tourReel - 1] = tempsReel;
            }

            const currentCar = fleetData[currentIndex];
            if (currentCar && currentCar.tours) {
                toursSpans.forEach((span, index) => {
                    span.textContent = currentCar.tours[index] || "--:--:--";
                });
            }

            let html = "";
            ordreIds.forEach((id, index) => {
                html += `${index + 1} - Voiture n°${id}<br>`;
            });
            classementElement.innerHTML = html;
        }
    }
}
// --- INITIALISATION ---
document.addEventListener("DOMContentLoaded", () => {
    refreshCarUI();
    fetchLiveRaceData();

    setInterval(() => {
        currentIndex = (currentIndex + 1) % fleetData.length;
        refreshCarUI();
    }, 5000);

    setInterval(fetchLiveRaceData, 1000);
});