// Chart Bar Demo
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('myBarChart');

    // Only initialize if the canvas element exists and has a context
    if (ctx && ctx.getContext) {
        try {
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Data',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1
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
            console.warn('Could not initialize bar chart:', error);
        }
    } else {
        console.warn('Bar chart canvas element not found');
    }
}); 