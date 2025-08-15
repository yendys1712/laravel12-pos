<!DOCTYPE html>
<html>
<head>
    <title>Real-Time Sales Line Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>📊 Real-Time Sales Line Graph</h2>
    <canvas id="salesChart" width="400" height="200"></canvas>

    <script>
        let salesChart;

        function fetchSalesData() {
            $.get('/api/sales-data', function(data) {
                if (!salesChart) {
                    // Create chart first time
                    const ctx = document.getElementById('salesChart').getContext('2d');
                    salesChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.dates,
                            datasets: [{
                                label: 'Total Sales',
                                data: data.totals,
                                borderColor: 'blue',
                                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                } else {
                    // Update chart
                    salesChart.data.labels = data.dates;
                    salesChart.data.datasets[0].data = data.totals;
                    salesChart.update();
                }
            });
        }

        // Fetch every 5 seconds
        fetchSalesData();
        setInterval(fetchSalesData, 5000);
    </script>
</body>
</html>
