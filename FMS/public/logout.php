<?php
$require_config = __DIR__ . '/../config/database.php';
$require_auth   = __DIR__ . '/../src/auth.php';

require_once $require_config;
require_once $require_auth;

$dbInstance = new Database();
$pdo = $dbInstance->connect();
$auth = new Auth($pdo);

$auth->logout();

header('Location: login.php?logged_out=success');
exit;