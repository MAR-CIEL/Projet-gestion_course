document.addEventListener("DOMContentLoaded", function() {
    // Initialisation de la courbe de vitesse
	const ctx = document.getElementById('chart-speed').getContext('2d'); // Réglage du "dessin"
    const speedChart = new Chart(ctx, {
        type: 'line', // Ligne continue
        data: { labels: [], datasets: [{ label: 'Vitesse (km/h)', data: [], borderColor: '#007bff', tension: 0.3 }] }, // Valeurs à afficher
        options: { responsive: true, animation: false, scales: { y: { beginAtZero: true, max: 20 } } } // Maximum de données sur le graphique
    });
	
	// Fonction de mise à jour des valeurs sur le dashboard
    async function updateDashboard() {
        try {
            let response = await fetch('api/api_data.php?t=' + Date.now()); // Connexion à l'API (qui récupère la dernière ligne des données insérées dans la BDD)
            const data = await response.json();
            if (data && !data.error) renderData(data, speedChart);
        } catch (e) { console.error("Erreur API", e); }
    }
	
	// Valeurs à afficher
    function renderData(data, chart) {
        document.getElementById('txt-vitesse').innerText = data.vitesse + " km/h";
        document.getElementById('txt-batterie').innerText = data.tension_batterie + " V";
        document.getElementById('txt-conso').innerText = data.consommation + " A";
        document.getElementById('txt-obstacle').innerText = (data.obstacle === 1) ? "DETECTE" : "NUL";
        document.getElementById('txt-direction').innerText = data.direction + "°";
		document.getElementById('txt-accel').innerText = data.acceleration;
		document.getElementById('txt-distance').innerText = data.distance + " m";

        // Logique sens en fonction de l'angle de direction
        const sensBadge = document.getElementById('badge-sens');
        if (Math.abs(data.direction) > 90) {
            sensBadge.innerText = "MARCHE ARRIÈRE";
            sensBadge.className = "badge bg-warning text-dark fs-5";
        } else {
            sensBadge.innerText = "MARCHE AVANT";
            sensBadge.className = "badge bg-success fs-5";
        }

        // Pourcentage d'énergie
		const tension = parseFloat(data.tension_batterie);
		let pct = Math.round(((tension - 6.0) / 1.2) * 100);
		
		// Sécurité pour garder le pourcentage entre 0 et 100
		pct = Math.max(0, Math.min(100, pct));

		const prog = document.getElementById('prog-energie');
		
		// Mise à jour de la barre d'énergie
		prog.style.width = pct + "%";
		
		// Mise à jour du texte
		prog.innerText = pct + "%"; 
		
		// Mise à jour de la couleur
		prog.className = (pct < 20) ? "progress-bar bg-danger" : "progress-bar bg-success";

        // Graphique
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(data.vitesse);
        if (chart.data.labels.length > 15) { chart.data.labels.shift(); chart.data.datasets[0].data.shift(); }
        chart.update('none');
    }
    setInterval(updateDashboard, 1000); // Mise à jour chaque seconde
});