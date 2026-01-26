// Vérifie que le DOM est chargé avant de lancer le graphique
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('chart-speed').getContext('2d');
    const speedChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Vitesse (km/h)',
                data: [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { 
            responsive: true, 
            animation: false,
            scales: { y: { beginAtZero: true, max: 60 } } 
        }
    });

    const URL_LOCALE = 'api/api_data.php'; 

    async function updateDashboard() {
        try {
            // Ajout d'un paramètre anti-cache (?t=...) pour forcer la mise à jour
            let response = await fetch(URL_LOCALE + '?t=' + Date.now()); 
            if (!response.ok) throw new Error("Erreur réseau");
            
            const data = await response.json();
            
            if (data && !data.error) {
                renderData(data, speedChart);
            }
        } catch (error) {
            console.error("Erreur de lecture API :", error);
        }
    }

    function renderData(data, chart) {
        // Mise à jour des textes
        document.getElementById('txt-vitesse').innerText = data.vitesse + " km/h";
        document.getElementById('txt-batterie').innerText = data.tension_batterie + " V";
        document.getElementById('txt-conso').innerText = data.consommation + " A";
        document.getElementById('txt-obstacle').innerText = (parseInt(data.obstacle) === 1) ? "DÉTECTÉ" : "Néant";

        // Alerte clignotante Art. 6
        const vElem = document.getElementById('txt-vitesse');
        if (parseFloat(data.vitesse) === 0) {
            vElem.classList.add('critical-alert');
        } else {
            vElem.classList.remove('critical-alert');
        }

        // Badge de sens
        const sensBadge = document.getElementById('badge-sens');
        sensBadge.innerText = (data.sens === "AV") ? "MARCHE AVANT" : "MARCHE ARRIÈRE";
        sensBadge.className = (data.sens === "AV") ? "badge bg-success" : "badge bg-warning text-dark";

        // Énergie
        let p = Math.max(0, Math.min(100, ((parseFloat(data.tension_batterie) - 6) / 1.2) * 100));
        const bar = document.getElementById('barre-energie');
        bar.style.width = p + "%";
        bar.innerText = Math.round(p) + "%";
        bar.className = (p < 20) ? "progress-bar bg-danger" : "progress-bar bg-success";

        // Graphique
        const now = new Date().toLocaleTimeString();
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(data.vitesse);
        if (chart.data.labels.length > 15) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
        chart.update('none');
    }

    // Lancement du cycle
    setInterval(updateDashboard, 1000);
});