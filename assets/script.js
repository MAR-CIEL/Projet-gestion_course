document.addEventListener("DOMContentLoaded", function() {
    const URL_LOCALE = 'api/api_data.php'; 

    const ctx = document.getElementById('chart-speed').getContext('2d');
    const speedChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Vitesse (km/h)',
                data: [],
                borderColor: '#007bff',
                tension: 0.3,
                fill: true
            }]
        },
        options: { responsive: true, animation: false }
    });

    async function updateDashboard() {
        try {
            // Lecture de la VM via l'API PHP
            let response = await fetch(URL_LOCALE + '?t=' + Date.now()); 
            const data = await response.json();
            
            if (data && !data.error) {
                renderData(data, speedChart);
            }
        } catch (error) {
            console.error("Erreur API :", error);
        }
    }

    function renderData(data, chart) {
        // Mise à jour des textes
        document.getElementById('txt-vitesse').innerText = data.vitesse + " km/h";
        document.getElementById('txt-batterie').innerText = data.tension_batterie + " V";
        document.getElementById('txt-conso').innerText = data.consommation + " A";
        document.getElementById('txt-obstacle').innerText = (data.obstacle === 1) ? "DÉTECTÉ" : "Néant";

        // Alerte clignotante Art. 6
        const vElem = document.getElementById('txt-vitesse');
        if (parseFloat(data.vitesse) === 0) {
            vElem.classList.add('critical-alert');
        } else {
            vElem.classList.remove('critical-alert');
        }

        // Mise à jour du graphique
        const now = new Date().toLocaleTimeString();
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(data.vitesse);
        if (chart.data.labels.length > 15) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
        chart.update('none');
    }

    setInterval(updateDashboard, 1000);
});