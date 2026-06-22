<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

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