<div class="max-w-4xl mx-auto mt-10">
    <h2 class="text-xl font-bold mb-4">📦 Items Sold - Live Chart</h2>
    <canvas id="salesChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('/admin/sales-data')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('salesChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Items Sold',
                        data: data.data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loading chart data:', error));
});
</script>