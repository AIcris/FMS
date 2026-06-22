<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$require_config  = __DIR__ . '/../../config/database.php';


require_once $require_config;


$dbInstance = new Database();
$pdo = $dbInstance->connect();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Exact same core styles as your dashboard for UI consistency */
        :root {
            --sidebar-width: 260px;
            --primary-green: #006400;
            --accent-gold: #FFD700;
            --dark-surface: #1e1e2d;
            --light-bg: #f5f7fa;
            --border-color: #e4e6ef;
        }

        body { margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background-color: var(--light-bg); color: #3f4254; display: flex; }
        
       /* Sidebar Navigation */
        #sidebar { 
            width: var(--sidebar-width); 
            height: 100vh; 
            background-color: var(--primary-green); 
            color: #ffffff; 
            position: fixed; 
            top: 0; 
            left: 0; 
            z-index: 1000; 
            transition: transform 0.3s ease; /* Ensure transition is on transform */
        }

        #sidebar.collapsed { 
            transform: translateX(-100%); 
        }

        /* Content Wrapper */
        #content-wrapper { 
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width)); 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            transition: margin-left 0.3s ease, width 0.3s ease; 
        }

        #content-wrapper.expanded { 
            margin-left: 0; 
            width: 100%; 
        }
        .sidebar-brand { padding: 24px; font-size: 18px; font-weight: 700; background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-menu { list-style: none; padding: 0; margin:0; }
        .sidebar-item a { display: flex; align-items: center; padding: 14px 24px; color: #a2a3b7; text-decoration: none; transition: all 0.2s; white-space: nowrap; }
        .sidebar-item a:hover, .sidebar-item.active a { color: #ffffff; background-color: rgba(255,255,255,0.04); border-left: 4px solid var(--accent-gold); }
        .sidebar-icon { margin-right: 15px; width: 20px; text-align: center; }

        #content-wrapper { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); min-height: 100vh; display: flex; flex-direction: column; }
        .top-navbar { height: 70px; background-color: #ffffff; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; padding: 0 30px; justify-content: space-between; }
       
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div id="content-wrapper">
    <?php 
    
        require_once __DIR__ . '/../../includes/admin_header.php'; 
    ?>

    <main class="workspace-padding">
        <h1>Feedback Datastore</h1>
        <p>This section is under development. Please check back later for comprehensive feedback management features.</p>
    </main>
</div>
</body>
<script>
 

    
</script>
</html>