import './bootstrap';
import './validation';

document.addEventListener("DOMContentLoaded", function() {

    //  Graphique Sanguin (Chart.js)
    const ctx = document.getElementById('bloodChart');
    if (ctx) {
        // Récupération des données passées via des attributs 'data-'
        const chartData = JSON.parse(ctx.getAttribute('data-values'));
        const chartLabels = JSON.parse(ctx.getAttribute('data-labels'));

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    //  Rendre les lignes du tableau cliquables
    const rows = document.querySelectorAll(".clickable-row");
    rows.forEach(row => {
        row.addEventListener("click", function() {
            window.location.href = this.dataset.href;
        });
    });
});

