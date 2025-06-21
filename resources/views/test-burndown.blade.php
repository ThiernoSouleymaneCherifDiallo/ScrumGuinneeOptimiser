<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Burndown Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    <style>
        body {
            background-color: #1D2125;
            color: #FFFFFF;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #22272B;
            border: 1px solid #3C3F42;
            border-radius: 8px;
            padding: 20px;
        }
        .chart-container {
            height: 400px;
            margin: 20px 0;
            background-color: #1D2125;
            border: 1px solid #3C3F42;
            border-radius: 8px;
            padding: 20px;
        }
        .debug-info {
            background-color: #1D2125;
            border: 1px solid #3C3F42;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 12px;
        }
        button {
            background-color: #3B82F6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background-color: #2563EB;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Burndown Chart</h1>
        
        <div>
            <button onclick="testChart()">Tester le graphique</button>
            <button onclick="testWithData()">Tester avec données</button>
            <button onclick="clearChart()">Effacer</button>
        </div>
        
        <div class="debug-info" id="debug-info">
            <strong>Informations de debug:</strong><br>
            Chart.js chargé: <span id="chart-loaded">Vérification...</span><br>
            Canvas trouvé: <span id="canvas-found">Vérification...</span><br>
            Contexte 2D: <span id="context-2d">Vérification...</span><br>
        </div>
        
        <div class="chart-container">
            <canvas id="testChart" width="600" height="300"></canvas>
        </div>
        
        <div class="debug-info" id="chart-info">
            <strong>État du graphique:</strong><br>
            Graphique créé: <span id="chart-created">Non</span><br>
            Données: <span id="chart-data">Aucune</span><br>
        </div>
    </div>

    <script>
        let testChart = null;
        
        // Vérifications au chargement
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM chargé');
            
            // Vérifier Chart.js
            if (typeof Chart !== 'undefined') {
                document.getElementById('chart-loaded').textContent = 'Oui';
                console.log('Chart.js chargé:', Chart.version);
            } else {
                document.getElementById('chart-loaded').textContent = 'Non';
                console.error('Chart.js non chargé');
            }
            
            // Vérifier le canvas
            const canvas = document.getElementById('testChart');
            if (canvas) {
                document.getElementById('canvas-found').textContent = 'Oui';
                console.log('Canvas trouvé:', canvas);
                
                // Vérifier le contexte 2D
                const ctx = canvas.getContext('2d');
                if (ctx) {
                    document.getElementById('context-2d').textContent = 'Oui';
                    console.log('Contexte 2D obtenu');
                } else {
                    document.getElementById('context-2d').textContent = 'Non';
                    console.error('Impossible d\'obtenir le contexte 2D');
                }
            } else {
                document.getElementById('canvas-found').textContent = 'Non';
                console.error('Canvas non trouvé');
            }
        });
        
        function testChart() {
            console.log('Test du graphique...');
            
            const canvas = document.getElementById('testChart');
            if (!canvas) {
                console.error('Canvas non trouvé');
                return;
            }
            
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Contexte 2D non disponible');
                return;
            }
            
            // Détruire le graphique existant
            if (testChart) {
                testChart.destroy();
            }
            
            try {
                testChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jour 1', 'Jour 2', 'Jour 3', 'Jour 4', 'Jour 5'],
                        datasets: [{
                            label: 'Test Dataset',
                            data: [10, 8, 6, 4, 2],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#FFFFFF'
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: '#FFFFFF'
                                },
                                grid: {
                                    color: '#3C3F42'
                                }
                            },
                            y: {
                                ticks: {
                                    color: '#FFFFFF'
                                },
                                grid: {
                                    color: '#3C3F42'
                                }
                            }
                        }
                    }
                });
                
                document.getElementById('chart-created').textContent = 'Oui';
                document.getElementById('chart-data').textContent = 'Test simple';
                console.log('Graphique de test créé avec succès');
                
            } catch (error) {
                console.error('Erreur lors de la création du graphique:', error);
                document.getElementById('chart-created').textContent = 'Erreur: ' + error.message;
            }
        }
        
        function testWithData() {
            console.log('Test avec données de burndown...');
            
            const canvas = document.getElementById('testChart');
            if (!canvas) {
                console.error('Canvas non trouvé');
                return;
            }
            
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Contexte 2D non disponible');
                return;
            }
            
            // Détruire le graphique existant
            if (testChart) {
                testChart.destroy();
            }
            
            // Données de test pour burndown
            const burndownData = {
                labels: ['01/01', '02/01', '03/01', '04/01', '05/01', '06/01', '07/01'],
                ideal: [20, 17, 14, 11, 8, 5, 2],
                actual: [20, 18, 15, 12, 9, 6, 3]
            };
            
            try {
                testChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: burndownData.labels,
                        datasets: [
                            {
                                label: 'Burndown idéal',
                                data: burndownData.ideal,
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.1,
                                pointRadius: 4
                            },
                            {
                                label: 'Burndown réel',
                                data: burndownData.actual,
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.1,
                                pointRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#FFFFFF'
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: '#FFFFFF'
                                },
                                grid: {
                                    color: '#3C3F42'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#FFFFFF'
                                },
                                grid: {
                                    color: '#3C3F42'
                                }
                            }
                        }
                    }
                });
                
                document.getElementById('chart-created').textContent = 'Oui';
                document.getElementById('chart-data').textContent = 'Données burndown';
                console.log('Graphique burndown créé avec succès');
                
            } catch (error) {
                console.error('Erreur lors de la création du graphique burndown:', error);
                document.getElementById('chart-created').textContent = 'Erreur: ' + error.message;
            }
        }
        
        function clearChart() {
            if (testChart) {
                testChart.destroy();
                testChart = null;
            }
            
            const canvas = document.getElementById('testChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            
            document.getElementById('chart-created').textContent = 'Non';
            document.getElementById('chart-data').textContent = 'Aucune';
            console.log('Graphique effacé');
        }
    </script>
</body>
</html> 