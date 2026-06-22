<?php
session_start();

$require_config = __DIR__ . '/../../config/database.php';
$require_auth   = __DIR__ . '/../../src/auth.php';

require_once $require_config;
require_once $require_auth;

// Check if a session exists and if the user role is authorized
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php?error=unauthorized_access');
    exit;
}