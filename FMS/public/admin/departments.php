<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: ../login.php'); exit; }

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/DepartmentManager.php';

$pdo = (new Database())->connect();
$deptManager = new DepartmentManager($pdo);

// ... after $deptManager = new DepartmentManager($pdo);

// Handle Delete
if (isset($_GET['delete'])) {
    $idToDelete = (int)$_GET['delete'];
    if ($deptManager->deleteDepartment($idToDelete)) {
        header('Location: departments.php?msg=deleted');
        exit;
    } else {
        $message = "Error: Could not delete department.";
        $messageType = "error";
    }
}

// Handle Add
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $deptManager->createDepartment($_POST['name'], $_POST['description']);
    $message = "Department added successfully.";
}

$departments = $deptManager->getAllDepartments();
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
        .workspace-padding { padding: 30px; flex-grow: 1; }

        .layout-grid { display: grid; grid-template-columns: 350px 1fr; gap: 25px; align-items: start; }
        
        @media (max-width: 992px) {
            .layout-grid { grid-template-columns: 1fr; }
        }

        .panel-card { background: #ffffff; border-radius: 8px; border: 1px solid var(--border-color); padding: 25px; }
        .panel-title { font-size: 16px; font-weight: 700; margin: 0 0 20px 0; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; color: #181c32; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px; color: #3f4254; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid var(--border-color); border-radius: 4px; font-family: inherit; font-size: 14px; box-sizing: border-box; }
        .form-control:focus { outline: none; border-color: var(--primary-green); }
        
        .btn-submit { background-color: var(--primary-green); color: white; border: none; padding: 12px; border-radius: 4px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 10px; }
        .btn-submit:hover { background-color: #004d00; }

        .alert { padding: 12px 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background-color: #e8f5e9; color: #2e7d32; border-left: 4px solid #4caf50; }
        .alert-error { background-color: #ffebee; color: #c62828; border-left: 4px solid #f44336; }

        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { background-color: #f9f9f9; text-align: left; padding: 12px 16px; font-weight: 600; color: #181c32; border-bottom: 1px solid var(--border-color); }
        td { padding: 12px 16px; border-bottom: 1px solid var(--border-color); color: #5e6278; }
        tr:hover td { background-color: #fcfcfd; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .badge-admin { background: #e3f2fd; color: #1565c0; }
        .badge-staff { background: #fff3e0; color: #e65100; }
        .badge-active { background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div id="content-wrapper">
    <?php 
    
        require_once __DIR__ . '/../../includes/admin_header.php'; 
    ?>

    <main class="workspace-padding">
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="layout-grid">
            <div class="panel-card">
                <h2 class="panel-title">Add New Department</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Department Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                   
                    <button type="submit" class="btn-submit">Add Department</button>
                </form>
            </div>

            <div class="panel-card" style="padding: 0;">
                <h2 class="panel-title" style="margin: 25px 25px 0 25px;">Department Directory</h2>
                <div style="overflow-x: auto; padding: 25px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Department Name</th>
                                <th>Description</th>

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php foreach ($departments as $dept): ?>
    <tr>
        <td><strong><?php echo htmlspecialchars($dept['department_name'] ?? ''); ?></strong></td>
        <td><?php echo htmlspecialchars($dept['description'] ?? ''); ?></td>
        
        <td>
            <a href="edit_department.php?id=<?php echo $dept['department_id']; ?>" style="color: #1976d2; margin-right: 15px;"><i class="fa-solid fa-edit"></i></a>
            <a href="departments.php?delete=<?php echo $dept['department_id']; ?>" style="color: #d32f2f;" onclick="return confirm('Are you sure?');"><i class="fa-solid fa-trash"></i></a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
<script>
 

        // Role Selection Logic
        const roleSelect = document.getElementById('roleSelect');
        const deptGroup = document.getElementById('deptGroup');
        const deptSelect = deptGroup ? deptGroup.querySelector('select') : null;

        if (roleSelect && deptSelect) {
            roleSelect.addEventListener('change', function() {
                if (this.value === 'admin') {
                    deptGroup.style.display = 'none';
                    deptSelect.removeAttribute('required');
                    deptSelect.value = '';
                } else {
                    deptGroup.style.display = 'block';
                    deptSelect.setAttribute('required', 'required');
                }
            });
        }

</script>
</html>