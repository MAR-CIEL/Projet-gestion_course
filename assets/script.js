/**
 * @file script.js
 * @brief Gestionnaire de mise à jour dynamique de l'IHM (JavaScript/AJAX).
 * @details Ce script assure le rafraîchissement des données télémétriques via l'API, 
 * gère les alertes visuelles et met à jour le graphique temps réel.
 */

document.addEventListener("DOMContentLoaded", function() {
	/** @brief Initialisation du graphique de vitesse via Chart.js */	
	const ctx = document.getElementById('chart-speed').getContext('2d');
    const speedChart = new Chart(ctx, {
        type: 'line', // Ligne continue
        data: { labels: [], datasets: [{ label: 'Vitesse (km/h)', data: [], borderColor: '#007bff', tension: 0.3 }] }, // Valeurs à afficher
        options: { responsive: true, animation: false, scales: { y: { beginAtZero: true, max: 20 } } } // Maximum de données sur le graphique
    });
	
		/**
     * @brief Récupère les dernières valeurs via l'API locale.
     * @async
     */    
	async function updateDashboard() {
        try {
            let response = await fetch('api/api_data.php?t=' + Date.now()); // Connexion à l'API (qui récupère la dernière ligne des données insérées dans la BDD)
            const data = await response.json();
            if (data && !data.error) renderData(data, speedChart);
        } catch (e) { console.error("Erreur API", e); }
    }
	
		/**
     * @brief Injecte les données dans le DOM et traite la logique métier.
     * @param {Object} data Données télémétriques reçues (JSON).
     * @param {Chart} chart Instance du graphique de vitesse.
     */    
	function renderData(data, chart) {
        document.getElementById('txt-vitesse').innerText = data.vitesse + " km/h";
        document.getElementById('txt-batterie').innerText = data.tension_batterie + " V";
        document.getElementById('txt-conso').innerText = data.consommation + " A";
        document.getElementById('txt-obstacle').innerText = (data.obstacle === 1) ? "DETECTE" : "NUL";
        document.getElementById('txt-direction').innerText = data.direction + "°";
		document.getElementById('txt-accel').innerText = data.acceleration;
		document.getElementById('txt-distance').innerText = data.distance + " m";

		/** @section Logic_Sens Déduction du sens de marche (Article 6) */
        const sensBadge = document.getElementById('badge-sens');
        if (Math.abs(data.direction) > 90) {
            sensBadge.innerText = "MARCHE ARRIÈRE";
            sensBadge.className = "badge bg-warning text-dark fs-5";
        } else {
            sensBadge.innerText = "MARCHE AVANT";
            sensBadge.className = "badge bg-success fs-5";
        }

		/** @section Logic_Energy Calcul du % batterie (6V - 7.2V) */
		const tension = parseFloat(data.tension_batterie);
		let pct = Math.round(((tension - 6.0) / 1.2) * 100);
		
		pct = Math.max(0, Math.min(100, pct));

		const prog = document.getElementById('prog-energie');
		
		prog.style.width = pct + "%";
		
		prog.innerText = pct + "%"; 
		
		prog.className = (pct < 20) ? "progress-bar bg-danger" : "progress-bar bg-success";

		/** @section Graph_Update Ajout du point au graphique */
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(data.vitesse);
        if (chart.data.labels.length > 15) { chart.data.labels.shift(); chart.data.datasets[0].data.shift(); }
        chart.update('none');
    }
	/** @brief Lancement du cycle de rafraîchissement (1 seconde) */
    setInterval(updateDashboard, 1000);
});