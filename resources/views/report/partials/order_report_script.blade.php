<script>
    $.get("{{ route('reports.show','order') }}", function (response) {
        $(function () {
            var donutChartCanvas = $('#pieChart').get(0).getContext('2d')
            var donutData = {
                labels: response.data.countOrder.status,
                datasets: [
                    {
                        data: response.data.countOrder.countStatus,
                        borderWidth: 2,
                        weight: 50,
                        backgroundColor: [
                            '#f56954',
                            '#00a65a',
                            '#f39c12',
                            '#00c0ef',
                            '#3c8dbc',
                            '#d2d6de',
                            '#b30000',
                            '#145214',
                            '#cc6600',
                            '#003d99',
                            '#808080',
                        ],
                    }
                ]
            }

            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = donutData;
            var pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    labels: {
                        fontColor: 'black',
                        fontSize: 15
                    }
                }
            }
            var pieChart = new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                options: pieOptions,
            })
        })
    });
</script>
