// Module Dashboard - Gestion des fonctionnalités du tableau de bord

export function initBloodChart() {
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
}

export function initDashboard() {
    initBloodChart();
}
