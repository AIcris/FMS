<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$require_config  = __DIR__ . '/../../config/database.php';
$require_metrics = __DIR__ . '/../../src/AdminMetrics.php';

require_once $require_config;
require_once $require_metrics;

$dbInstance = new Database();
$pdo = $dbInstance->connect();
$metrics = new AdminMetrics($pdo);

$cards = $metrics->getCardTelemetry();
$demo = $metrics->getDemographics();
$sqdScores = $metrics->getSqdStackedData();
$ccData = $metrics->getCcBreakdown();
$allRecords = $metrics->getDetailedRows();
$officePerformance = $metrics->getOfficeSatisfactionAverages();

$officeLabels = [];
$officeScores = [];
foreach ($officePerformance as $row) {
    $officeLabels[] = $row['name_of_office'];
    $officeScores[] = (float)$row['avg_score'];
}
$staffCount = $metrics->getStaffCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Executive Feedback Analytics Panel</title>
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-green: #006400;
            --accent-gold: #FFD700;
            --dark-surface: #1e1e2d;
            --light-bg: #f5f7fa;
            --border-color: #e4e6ef;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: var(--light-bg);
            color: #3f4254;
            display: flex;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--primary-green);
            color: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-x: hidden;
        }

        #sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-brand {
            padding: 24px;
            font-size: 18px;
            font-weight: 700;
            background: rgba(0,0,0,0.2);
            white-space: nowrap;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin:0;
        }

        .sidebar-item a {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: #a2a3b7;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .sidebar-item a:hover, .sidebar-item.active a {
            color: #ffffff;
            background-color: rgba(255,255,255,0.04);
            border-left: 4px solid var(--accent-gold);
        }

        .sidebar-icon {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        #content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #content-wrapper.expanded {
            margin-left: 0;
            width: 100%;
        }

        .top-navbar {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 30px;
            justify-content: space-between;
        }

        .hamburger-trigger {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            color: #5e6278;
        }

        .workspace-padding {
            padding: 30px;
            flex-grow: 1;
        }

        .metric-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .kpi-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }

        .kpi-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #b5b5c3;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: 700;
            color: #181c32;
            margin: 0;
        }

        .analytics-block-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        @media (max-width: 600px) {
            .analytics-block-row { grid-template-columns: 1fr; }
        }

        .chart-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 24px;
            border: 1px solid var(--border-color);
        }

        .chart-card h2 {
            margin: 0 0 20px 0;
            font-size: 15px;
            color: #181c32;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .data-table-container {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow-x: auto;
            margin-top: 30px;
        }

        .data-table-title {
            padding: 20px 24px;
            font-size: 16px;
            font-weight: 700;
            border-bottom: 1px solid var(--border-color);
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background-color: #f9f9f9;
            text-align: left;
            padding: 12px 16px;
            font-weight: 600;
            color: #181c32;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            color: #5e6278;
            white-space: nowrap;
        }

        tr:hover td {
            background-color: #fcfcfd;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-red { background: #ffebee; color: #c62828; }
        .badge-green { background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div id="content-wrapper">
  <?php require_once __DIR__ . '/../../includes/admin_header.php'; ?>

    <main class="workspace-padding">
        <div class="metric-cards-grid">
            <div class="kpi-card">
                <p class="kpi-title">Gross Records</p>
                <p class="kpi-value"><?php echo $cards['total']; ?></p>
            </div>
            <div class="kpi-card">
                <p class="kpi-title">Submitted Reports</p>
                <p class="kpi-value"><?php echo round($cards['recommend_rate'], 1); ?>%</p>
            </div>
            <div class="kpi-card">
                <p class="kpi-title">Harassment Alerts</p>
                <p class="kpi-value" style="color: #d32f2f;"><?php echo $cards['harassment']; ?></p>
            </div>
            <div class="kpi-card">
                <p class="kpi-title">Overall Accounts</p>
                <p class="kpi-value"><?php echo $staffCount; ?></p>
            </div>
        </div>

        <div class="analytics-block-row">
            <div class="chart-card">
                <h2>Top Office Performance Rankings (SQD Average Out of 5.00)</h2>
                <div style="position:relative; height:320px;"><canvas id="officeChart"></canvas></div>
            </div>
            <div class="chart-card">
                <h2>Service Quality Dimensions Summary (SQD Stacked Distribution Matrix)</h2>
                <div style="position:relative; height:320px;"><canvas id="sqdRadarChart"></canvas></div>
            </div>
        </div>

        <div class="analytics-block-row">
            
            <div class="chart-card">
                <h2>Citizen's Charter Performance Index Matrix (CC1, CC2, & CC3 Correlation)</h2>
                <div style="position:relative; height:320px;"><canvas id="combinedCcMatrixChart"></canvas></div>
            </div>

            <div class="chart-card">
            <h2>Sentiment Analysis Chart</h2>
        </div>
        </div>
<div class="chart-card">
                <h2>Demographic Profile Segmentation Matrix</h2>
                <div style="position:relative; height:320px;"><canvas id="demographicsChart"></canvas></div>
            </div>
        
        
    </main>
</div>

<script>
   
        new Chart(document.getElementById('officeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($officeLabels); ?>,
                datasets: [{
                    label: 'Average Score',
                    data: <?php echo json_encode($officeScores); ?>,
                    backgroundColor: 'rgba(0, 100, 0, 0.75)',
                    borderColor: 'rgb(0, 100, 0)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: { x: { min: 0, max: 5, grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });

        const sqdData = <?php echo json_encode($metrics->getSqdStackedData()); ?>;
        const labels = ['SQD0', 'SQD1', 'SQD2', 'SQD3', 'SQD4', 'SQD5', 'SQD6', 'SQD7', 'SQD8'];

        new Chart(document.getElementById('sqdRadarChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Strongly Disagree', data: labels.map(l => sqdData[l.toLowerCase()]['1']), backgroundColor: '#d1d1d1' },
                    { label: 'Disagree', data: labels.map(l => sqdData[l.toLowerCase()]['2']), backgroundColor: '#f1948a' },
                    { label: 'Neither', data: labels.map(l => sqdData[l.toLowerCase()]['3']), backgroundColor: '#f7dc6f' },
                    { label: 'Agree', data: labels.map(l => sqdData[l.toLowerCase()]['4']), backgroundColor: '#76d7c4' },
                    { label: 'Strongly Agree', data: labels.map(l => sqdData[l.toLowerCase()]['5']), backgroundColor: '#e91e63' },
                    { label: 'N/A', data: labels.map(l => sqdData[l.toLowerCase()]['NA']), backgroundColor: '#f4d03f' }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true, grid: { display: false } },
                    y: { stacked: true }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        const demoData = <?php echo json_encode($demo); ?>;
        new Chart(document.getElementById('demographicsChart'), {
            type: 'bar',
            data: {
                labels: ['Citizen', 'Business', 'Government', 'Student', 'Faculty', 'Staff', 'Internal', 'External'],
                datasets: [{
                    label: 'Submissions Count Cluster',
                    data: [
                        demoData.client_type.find(i=>i.label==='Citizen')?.value || 0,
                        demoData.client_type.find(i=>i.label==='Business')?.value || 0,
                        demoData.client_type.find(i=>i.label==='Government')?.value || 0,
                        demoData.client_classification.find(i=>i.label==='Student')?.value || 0,
                        demoData.client_classification.find(i=>i.label==='Faculty Member')?.value || 0,
                        demoData.client_classification.find(i=>i.label==='Non-Academic Staff')?.value || 0,
                        demoData.transaction_type.find(i=>i.label==='Internal')?.value || 0,
                        demoData.transaction_type.find(i=>i.label==='External')?.value || 0
                    ],
                    backgroundColor: ['#0288d1','#0288d1','#0288d1','#2e7d32','#2e7d32','#2e7d32','#e65100','#e65100']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        const cc1RawData = <?php echo json_encode($ccData['cc1']); ?>;
        const cc2RawData = <?php echo json_encode($ccData['cc2']); ?>;
        const cc3RawData = <?php echo json_encode($ccData['cc3']); ?>;

        new Chart(document.getElementById('combinedCcMatrixChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: [
                    'Tier 1: Excellent / High engagement', 
                    'Tier 2: Moderate / Good engagement', 
                    'Tier 3: Low / Poor engagement', 
                    'Tier 4: Non-Existent / Unaware'
                ],
                datasets: [
                    {
                        label: 'CC1: Charter Awareness Profile',
                        data: [
                            cc1RawData.find(i => i.label === '1')?.value || 0,
                            cc1RawData.find(i => i.label === '2')?.value || 0,
                            cc1RawData.find(i => i.label === '3')?.value || 0,
                            cc1RawData.find(i => i.label === '4')?.value || 0
                        ],
                        backgroundColor: 'rgba(27, 94, 32, 0.85)',
                        borderColor: '#1b5e20',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'CC2: Physical Visibility Grade',
                        data: [
                            cc2RawData.find(i => i.label === 'Easy to see')?.value || 0,
                            cc2RawData.find(i => i.label === 'Somewhat easy to see')?.value || 0,
                            cc2RawData.find(i => i.label === 'Difficult to see')?.value || 0,
                            cc2RawData.find(i => i.label === 'Not visible at all')?.value || 0
                        ],
                        backgroundColor: 'rgba(2, 136, 209, 0.85)',
                        borderColor: '#0288d1',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'CC3: Transaction Helpfulness Utility',
                        data: [
                            cc3RawData.find(i => i.label === 'Helped very much')?.value || 0,
                            cc3RawData.find(i => i.label === 'Somewhat helped')?.value || 0,
                            cc3RawData.find(i => i.label === 'Did not help')?.value || 0,
                            0
                        ],
                        backgroundColor: 'rgba(255, 179, 0, 0.85)',
                        borderColor: '#ffb300',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { boxWidth: 15 }
                    }
                }
            }
        });

</script>
</body>
</html>