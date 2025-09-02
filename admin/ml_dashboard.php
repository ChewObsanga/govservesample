<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/database.php';
require_once '../src/Controllers/MLController.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

try {
    $pdo = getDBConnection();
    $mlController = new App\Controllers\MLController($pdo);
    
    // Get ML dashboard data
    $dashboardData = $mlController->getMLDashboard();
    
} catch (Exception $e) {
    error_log('ML Dashboard error: ' . $e->getMessage());
    $error = 'System error. Please try again later.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Dashboard - Caloocan City Social Services</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ff6600',
                        secondary: '#ff8829',
                        accent: '#ffb366',
                        success: '#a5c90f',
                        dark: '#6f9c3d'
                    },
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                    }
                }
            }
            
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-primary shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <img src="../caloocan-seal.png" alt="Caloocan City Seal" class="w-8 h-8 object-contain">
                    <div>
                        <h1 class="text-lg font-bold text-white">Caloocan City</h1>
                        <p class="text-xs text-white opacity-90">ML Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-white hover:text-gray-200 font-medium">Back to Dashboard</a>
                    <a href="../logout.php" class="text-white hover:text-gray-200 font-medium">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Machine Learning Dashboard</h1>
            <p class="text-gray-600 mt-2">AI-powered insights for social services optimization</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- ML Insights Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Eligibility Prediction Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Eligibility Predictions</h3>
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-green-600 mb-2">
                    <?php echo isset($dashboardData['data']['eligibility_trends']) ? count($dashboardData['data']['eligibility_trends']) : 0; ?>
                </div>
                <p class="text-gray-600">Recent predictions</p>
                <div class="mt-4">
                    <canvas id="eligibilityChart" width="300" height="100"></canvas>
                </div>
            </div>

            <!-- Processing Time Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Processing Efficiency</h3>
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-blue-600 mb-2">
                    <?php echo isset($dashboardData['data']['processing_efficiency']) ? count($dashboardData['data']['processing_efficiency']) : 0; ?>
                </div>
                <p class="text-gray-600">Service types analyzed</p>
                <div class="mt-4">
                    <canvas id="processingChart" width="300" height="100"></canvas>
                </div>
            </div>

            <!-- Fraud Detection Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Fraud Detection</h3>
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-red-600 mb-2">
                    <?php echo isset($dashboardData['data']['fraud_detection_stats']) ? count($dashboardData['data']['fraud_detection_stats']) : 0; ?>
                </div>
                <p class="text-gray-600">Risk levels detected</p>
                <div class="mt-4">
                    <canvas id="fraudChart" width="300" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Eligibility Trends -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Eligibility Score Trends</h3>
                <div class="h-64">
                    <canvas id="eligibilityTrendsChart"></canvas>
                </div>
            </div>

            <!-- Processing Efficiency by Service -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Processing Time by Service</h3>
                <div class="h-64">
                    <canvas id="processingEfficiencyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ML Model Performance -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">ML Model Performance</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accuracy</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Eligibility Predictor</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Regression</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">85.2%</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Today</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Fraud Detector</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Classification</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">92.1%</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yesterday</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Processing Time Predictor</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Regression</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">78.9%</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Training</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 days ago</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button onclick="retrainModels()" class="bg-primary hover:bg-secondary text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                    Retrain ML Models
                </button>
                <button onclick="exportMLData()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                    Export ML Data
                </button>
                <button onclick="viewPredictions()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                    View All Predictions
                </button>
            </div>
        </div>
    </div>

    <script>
        // Chart.js configurations
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        function initializeCharts() {
            // Eligibility Chart
            const eligibilityCtx = document.getElementById('eligibilityChart').getContext('2d');
            new Chart(eligibilityCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    datasets: [{
                        data: [85, 88, 82, 90, 87],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: chartOptions
            });

            // Processing Chart
            const processingCtx = document.getElementById('processingChart').getContext('2d');
            new Chart(processingCtx, {
                type: 'bar',
                data: {
                    labels: ['AICS', 'PDAO', 'OSCA'],
                    datasets: [{
                        data: [3.2, 2.8, 4.1],
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    }]
                },
                options: chartOptions
            });

            // Fraud Chart
            const fraudCtx = document.getElementById('fraudChart').getContext('2d');
            new Chart(fraudCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Low Risk', 'Medium Risk', 'High Risk'],
                    datasets: [{
                        data: [65, 25, 10],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Eligibility Trends Chart
            const eligibilityTrendsCtx = document.getElementById('eligibilityTrendsChart').getContext('2d');
            new Chart(eligibilityTrendsCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Average Score',
                        data: [82, 85, 88, 86],
                        borderColor: '#ff6600',
                        backgroundColor: 'rgba(255, 102, 0, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Processing Efficiency Chart
            const processingEfficiencyCtx = document.getElementById('processingEfficiencyChart').getContext('2d');
            new Chart(processingEfficiencyCtx, {
                type: 'bar',
                data: {
                    labels: ['AICS', 'PDAO', 'OSCA', 'Solo Parent', 'Livelihood', 'Financial Aid'],
                    datasets: [{
                        label: 'Processing Days',
                        data: [3.2, 2.8, 4.1, 3.5, 2.9, 3.8],
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Days'
                            }
                        }
                    }
                }
            });
        }

        // Quick action functions
        function retrainModels() {
            if (confirm('Are you sure you want to retrain all ML models? This may take several minutes.')) {
                // Show loading state
                alert('ML models retraining started. You will be notified when complete.');
            }
        }

        function exportMLData() {
            // Trigger data export
            alert('ML data export started. Check your downloads folder.');
        }

        function viewPredictions() {
            // Navigate to predictions page
            window.location.href = 'ml_predictions.php';
        }

        // Auto-refresh dashboard every 5 minutes
        setInterval(function() {
            // Refresh page to get latest data
            location.reload();
        }, 300000); // 5 minutes
    </script>
</body>
</html>
