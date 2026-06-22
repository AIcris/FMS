<?if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header('Location: ../login.php');
    exit;
}

$require_config  = __DIR__ . '/../../config/database.php';
$require_metrics = __DIR__ . '/../../src/StaffMetrics.php';

require_once $require_config;
require_once $require_metrics;

$dbInstance = new Database();
$pdo = $dbInstance->connect();

$departmentId = $_SESSION['department_id'] ?? 1; 
$staffMetrics = new StaffMetrics($pdo, $departmentId);

$deptName = $staffMetrics->getDepartmentName();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
</head>
<style>
     :root {
            --sidebar-width: 260px;
            --primary-green: #006400;
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
            z-index: 1000;
            transition: all 0.3s ease;
        }

        #sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-brand {
            padding: 24px;
            font-size: 18px;
            font-weight: 700;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-menu { 
            list-style: none; 
            padding: 0; 
            margin:0; }

        .sidebar-item a {
            display: flex; 
            align-items: center; 
            padding: 14px 24px;
            color: #e3f2fd; 
            text-decoration: none; 
            transition: all 0.2s;
        }
        .sidebar-item a:hover,
        .sidebar-item.active a {
            color: #ffffff;
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid #FFD700;
        }

        .sidebar-icon {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        #content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
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
            margin-right: 15px;
        }

        .workspace-padding {
            padding: 30px;
            flex-grow: 1;
        }
</style>
<body>
    <nav id="sidebar">
    <div class="sidebar-brand">FMS Staff Portal</div>
    <ul class="sidebar-menu">
        <li class="sidebar-item active">
            <a href="dashboard.php">
                <i class="fa-solid fa-chart-line sidebar-icon"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="../staff/reports.php">
                <i class="fa-solid fa-folder-open sidebar-icon"></i>
                <span>Reports</span>
            </a>
        </li>
        <li class="sidebar-item" style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1);">
            <a href="../logout.php">
                <i class="fa-solid fa-arrow-right-from-bracket sidebar-icon" style="color: #ffcdd2;"></i>
                <span>Sign Out</span>
            </a>
        </li>
    </ul>
</nav>

    <div id="content-wrapper">
        <header class="top-navbar">
        <div style="font-weight: 600; font-size: 18px; display: flex; align-items: center;">
            <button class="hamburger-trigger" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
            <i class="fa-solid fa-building" style="color: var(--primary-blue); margin-right: 10px;"></i>
             <?php echo htmlspecialchars($deptName); ?>
        </div>
        <div>
            User: <?php echo htmlspecialchars($_SESSION['firstname']); ?> (Staff)
        </div>
    </header>
<div class="chart-card">
    <h2>Generate Monthly Report</h2>
    <form action="process_report.php" method="POST" style="display: flex; gap: 15px; align-items: flex-end;">
        <input type="hidden" name="action" value="generate">
        
        <div style="display: flex; flex-direction: column;">
            <label style="margin-bottom: 5px; font-size: 12px; font-weight: 600;">Month</label>
            <select name="target_month" required style="padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label style="margin-bottom: 5px; font-size: 12px; font-weight: 600;">Year</label>
            <input type="number" name="target_year" value="2026" required style="padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; width: 80px;">
        </div>

        <button type="submit" class="btn-submit">Compile Draft</button>
    </form>
</div>
    </div>  


</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const wrapper = document.getElementById('content-wrapper');

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            wrapper.classList.toggle('expanded');
        });
    });


</script>



