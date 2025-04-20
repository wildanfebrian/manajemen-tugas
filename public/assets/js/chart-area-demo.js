// Chart Area Demo
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('myAreaChart');

    // Only initialize if the canvas element exists and has a context
    if (ctx && ctx.getContext) {
        try {
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Data',
                        data: [],
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.warn('Could not initialize area chart:', error);
        }
    } else {
        console.warn('Area chart canvas element not found');
    }
}); 