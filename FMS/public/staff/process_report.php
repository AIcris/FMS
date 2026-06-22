<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/ReportGenerator.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
    $pdo = (new Database())->connect();
    $generator = new ReportGenerator($pdo);

    $month = $_POST['target_month'];
    $year = $_POST['target_year'];
    $deptId = $_SESSION['department_id'];
    $userId = $_SESSION['user_id'];

    $success = $generator->generateMonthlyDraft($deptId, $userId, $month, $year);

    if ($success) {
        header('Location: reports.php?status=draft_created');
    } else {
        header('Location: reports.php?status=error');
    }
    exit;
}