@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-around my-1">
            <div class="col-md-4">
                <div class="card">
                    <div>
                        <canvas id="chart_live_status"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <ul class="pagination justify-content-center my-1">
                        <li class="page-item" id="prevDate">
                            <a class="page-link" href="#" id="prevDate_a">Previous</a>
                        </li>
                        <li class="page-item active" id="currentDate">
                            <a class="page-link" href="#" id="currentDate_a"></a>
                        </li>
                        <li class="page-item" id="nextDate">
                            <a class="page-link" href="#" id="nextDate_a">Next</a>
                        </li>
                    </ul>
                    <div>
                        <canvas id="chart_daily_status"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-around my-1">
            <div class="col-md-12">
                <div class="card">
                    <ul class="pagination justify-content-center my-1">
                        <li class="page-item" id="prevMonth">
                            <a class="page-link" href="#" id="prevMonth_a">Previous</a>
                        </li>
                        <li class="page-item active" id="currentMonth">
                            <a class="page-link" href="#" id="currentMonth_a"></a>
                        </li>
                        <li class="page-item" id="nextMonth">
                            <a class="page-link" href="#" id="nextMonth_a">Next</a>
                        </li>
                    </ul>
                    <div>
                        <canvas id="chart_history_status"></canvas>
                    </div>
                </div>
            </div>
        </div>
        {{-- charts --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>


        {{-- running commands --}}
        {{-- <script>
            function runScraper() {
                $(document).ready(function() {
                    $.ajax({
                        url: "{{ route('run.scraper') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response) {
                                console.log('Scraper executed');
                            }
                        },
                        error: function(error) {
                            console.error('Error scraper execution :', error);
                        }
                    });
                });
            }
            runScraper();
            // Set up interval to run the scraper every hour (in milliseconds)
            setInterval(function() {
                runScraper();
            }, 60 * 60 * 1000); // 60 minutes * 60 seconds * 1000 milliseconds
        </script> --}}

        {{-- hourly chart --}}
        <script>
            $(document).ready(function() {
                let currentDate = formatDate(new Date());
                $('#currentDate_a').text(currentDate);
                let hourlyStatusChart; // Variable to store the chart instance
                function fetchDataAndRenderChart(date) {
                    $.ajax({
                        url: `/get/hourly/${date}`,
                        method: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response && response.listHourly) {
                                const sentimentData = response.listHourly;
                                const labels = sentimentData.map(item => item.time);
                                const negativeData = sentimentData.map(item => item.negative);
                                const neutralData = sentimentData.map(item => item.neutral);
                                const positiveData = sentimentData.map(item => item.positive);
                                const veryPositiveData = sentimentData.map(item => item['very-positive']);

                                updateHourlyChart(labels, negativeData, neutralData, positiveData,
                                    veryPositiveData);
                            }
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });

                    function updateHourlyChart(labels, negative, neutral, positive, veryPositive) {
                        // Destroy the existing chart if it exists
                        if (hourlyStatusChart) {
                            hourlyStatusChart.destroy();
                        }

                        const ctxHistoryStatus = document.getElementById('chart_daily_status').getContext('2d');
                        hourlyStatusChart = new Chart(ctxHistoryStatus, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Negative',
                                        data: negative,
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1,
                                        fill: 'false',
                                    },
                                    {
                                        label: 'Neutral',
                                        data: neutral,
                                        borderColor: 'rgba(255, 255, 99, 1)',
                                        borderWidth: 1,
                                        fill: false,
                                    },
                                    {
                                        label: 'Positive',
                                        data: positive,
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1,
                                        fill: false,
                                    },
                                    {
                                        label: 'Very Positive',
                                        data: veryPositive,
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1,
                                        fill: false,
                                    }
                                ],
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    },
                                    title: {
                                        display: true,
                                        text: `Daily Sentiment Sentiment Status`
                                    }
                                },
                                scales: {
                                    x: {
                                        type: 'category',
                                        labels: labels,
                                        title: {
                                            display: true,
                                            text: 'Hour'
                                        }
                                    },
                                    y: {
                                        min: 0,
                                        title: {
                                            display: true,
                                            text: 'Sentiment Status'
                                        }
                                    }
                                }
                            },
                        });
                    }
                }

                // Initial chart rendering
                fetchDataAndRenderChart(currentDate);

                // Click event for the previous date
                $('#prevDate').on('click', function() {
                    const previousDate = new Date(currentDate);
                    previousDate.setDate(previousDate.getDate() - 1);
                    currentDate = formatDate(previousDate);
                    $('#currentDate_a').text(currentDate);
                    fetchDataAndRenderChart(currentDate);
                });

                // Click event for the next date
                $('#nextDate').on('click', function() {
                    const nextDate = new Date(currentDate);
                    nextDate.setDate(nextDate.getDate() + 1);
                    currentDate = formatDate(nextDate);
                    $('#currentDate_a').text(currentDate);
                    fetchDataAndRenderChart(currentDate);
                });

                function formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }
                // Set interval to update the chart every hour
                // setInterval(function() {
                //     const currentHour = new Date().getHours();
                //     // Update the chart only if the current hour is 0 (midnight)
                //     if (currentHour === 0) {
                //         currentDate = formatDate(new Date());
                //         fetchDataAndRenderChart(currentDate);
                //     }
                // }, 3600000); // 3600000 milliseconds = 1 hour
            });
        </script>

        {{-- monthly history chart --}}
        <script>
            $(document).ready(function() {
                let currentDate = new Date();
                let currentMonth = formatDate(currentDate, 'YYYY-MM');
                $('#currentMonth_a').text(currentMonth);
                let historyStatusChart;

                function fetchDataAndRenderChart(month) {
                    $.ajax({
                        url: `/get/monthly/${month}`,
                        method: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response && response.listMonthly) {
                                const sentimentData = response.listMonthly;
                                const dateLabels = sentimentData.map(item => item.date);
                                const negativeData = sentimentData.map(item => item.average_negative);
                                const neutralData = sentimentData.map(item => item.average_neutral);
                                const positiveData = sentimentData.map(item => item.average_positive);
                                const veryPositiveData = sentimentData.map(item => item
                                    .average_very_positive);

                                updateHistoryChart(dateLabels, negativeData, neutralData, positiveData,
                                    veryPositiveData);
                            }
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
                // Function to create or update the chart
                function updateHistoryChart(labels, negative, neutral, positive, veryPositive) {
                    if (historyStatusChart) {
                        historyStatusChart.destroy();
                    }

                    const ctxHistoryStatus = document.getElementById('chart_history_status').getContext('2d');
                    historyStatusChart = new Chart(ctxHistoryStatus, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'Negative',
                                    data: negative,
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1,
                                    fill: false,
                                },
                                {
                                    label: 'Neutral',
                                    data: neutral,
                                    borderColor: 'rgba(255, 255, 99, 1)',
                                    borderWidth: 1,
                                    fill: false,
                                },
                                {
                                    label: 'Positive',
                                    data: positive,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1,
                                    fill: false,
                                },
                                {
                                    label: 'Very Positive',
                                    data: veryPositive,
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1,
                                    fill: false,
                                }
                            ],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: true,
                                    text: 'Monthly Sentiment Status'
                                }
                            },
                            scales: {
                                x: {
                                    type: 'category',
                                    labels: labels,
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                },
                                y: {
                                    min: 0,
                                    title: {
                                        display: true,
                                        text: 'Sentiment Level'
                                    }
                                }
                            }
                        },
                    });
                }
                // Initial chart rendering
                fetchDataAndRenderChart(currentMonth);

                // Click event for the previous month
                $('#prevMonth').on('click', function() {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    const previousMonth = formatDate(currentDate, 'YYYY-MM');
                    $('#currentMonth_a').text(previousMonth);
                    fetchDataAndRenderChart(previousMonth);
                });

                // Click event for the next month
                $('#nextMonth').on('click', function() {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    const nextMonth = formatDate(currentDate, 'YYYY-MM');
                    $('#currentMonth_a').text(nextMonth);
                    fetchDataAndRenderChart(nextMonth);
                });
                // Function to format a date as per the provided format
                function formatDate(date, format) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    if (format === 'YYYY-MM') {
                        return `${year}-${month}`;
                    }
                    // Add more format options as needed
                }
            });
        </script>
        {{-- show live status --}}
        <script>
            $(document).ready(function() {
                $.ajax({
                    url: "{{ route('show.live.hour.sentiments') }}",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response && response.showHourRange) {
                            // Assuming you have the response stored in a variable called 'response'
                            const sentimentData = response.showHourRange;
                            const liveStatusData = [sentimentData.negative, sentimentData.neutral,
                                sentimentData.positive, sentimentData['very-positive']
                            ];
                            // Create or update the doughnut chart with the received data
                            updateLiveDoughnutChart(liveStatusData);
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });

                function updateLiveDoughnutChart(data) {
                    const ctxLiveStatus = document.getElementById('chart_live_status').getContext('2d');
                    new Chart(ctxLiveStatus, {
                        type: 'doughnut',
                        data: {
                            labels: ['Negative', 'Neutral', 'Positive', 'Very positive'],
                            datasets: [{
                                data: data,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(255, 255, 99, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(255, 255, 99, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(54, 162, 235, 1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: true,
                                    text: 'Live Status - Last Hour'
                                }
                            }
                        },
                    });
                }
            });
        </script>
    </div>
@endsection
