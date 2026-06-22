<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/DepartmentManager.php';

$pdo = (new Database())->connect();
$deptManager = new DepartmentManager($pdo);
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    // Only pass name and description
    $deptManager->createDepartment($_POST['name'], $_POST['description']);
    header('Location: departments.php');
    exit;
}
$dept = $deptManager->getDepartmentById($id);
?>