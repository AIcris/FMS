<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav id="sidebar">
    <div class="sidebar-brand">FMS Analytics</div>
    <ul class="sidebar-menu">
        <li class="sidebar-item <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <i class="fa-solid fa-chart-line sidebar-icon"></i>
                <span>Dashboard View</span>
            </a>
        </li>
        <li class="sidebar-item <?php echo $currentPage === 'feedback_dataStore.php' ? 'active' : ''; ?>">
            <a href="feedback_dataStore.php">
                <i class="fa-solid fa-database sidebar-icon"></i>
                <span>Feedback Datastore</span>
            </a>
        </li>
        <li class="sidebar-item <?php echo $currentPage === 'reports.php' ? 'active' : ''; ?>">
            <a href="reports.php">
                <i class="fa-solid fa-file-export sidebar-icon"></i>
                <span>Reports</span>
            </a>
        </li>
        <li class="sidebar-item <?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
            <a href="users.php">
                <i class="fa-solid fa-users-gear sidebar-icon"></i>
                <span>User Management</span>
            </a>
        </li>
        <li class="sidebar-item <?php echo $currentPage === 'departments.php' ? 'active' : ''; ?>">
            <a href="departments.php">
                <i class="fa-solid fa-building sidebar-icon"></i>
                <span>Departments</span>
            </a>
        </li>
        <li class="sidebar-item <?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
            <a href="#">
                <i class="fa-solid fa-sliders sidebar-icon"></i>
                <span>System Settings</span>
            </a>
        </li>
        <li class="sidebar-item" style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.05);">
            <a href="../logout.php">
                <i class="fa-solid fa-arrow-right-from-bracket sidebar-icon" style="color: #d32f2f;"></i>
                <span>Sign Out</span>
            </a>
        </li>
    </ul>
</nav>