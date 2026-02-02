document.addEventListener("DOMContentLoaded", function() {
    const URL_VOITURE = "http://192.168.1.50/api/telemetrie";
    const URL_LOCALE = "api/api_data.php"; // Pointe vers le PHP qui interroge la VM

    const ctx = document.getElementById('chart-speed').getContext('2d');
    const speedChart = new Chart(ctx, {
        type: 'line',
        data: { labels: [], datasets: [{ label: 'Vitesse (km/h)', data: [], borderColor: '#007bff', tension: 0.3 }] },
        options: { responsive: true, animation: false }
    });

    async function updateDashboard() {
        try {
            // Tentative Wi-Fi direct
            let response = await fetch(URL_VOITURE, { signal: AbortSignal.timeout(800) });
            let data = await response.json();
            renderData(data);
        } catch (e) {
            // Repli sur l'API (VM Ubuntu)
            try {
                let response = await fetch(URL_LOCALE);
                let data = await response.json();
                renderData(data);
            } catch (err) { console.error("Sources indisponibles"); }
        }
    }

    function renderData(data) {
        document.getElementById('txt-vitesse').innerText = data.vitesse + " km/h";
        document.getElementById('txt-batterie').innerText = data.tension_batterie + " V";
        document.getElementById('txt-conso').innerText = data.consommation + " A";
        document.getElementById('txt-obstacle').innerText = (parseInt(data.obstacle) === 1) ? "DÉTECTÉ" : "Néant";

        // Alerte clignotante Art. 6
        const vElem = document.getElementById('txt-vitesse');
        if (parseFloat(data.vitesse) === 0) vElem.classList.add('critical-alert');
        else vElem.classList.remove('critical-alert');

        // Mise à jour graphique
        const now = new Date().toLocaleTimeString();
        speedChart.data.labels.push(now);
        speedChart.data.datasets[0].data.push(data.vitesse);
        if (speedChart.data.labels.length > 15) { speedChart.data.labels.shift(); speedChart.data.datasets[0].data.shift(); }
        speedChart.update('none');
    }

    setInterval(updateDashboard, 1000);
});