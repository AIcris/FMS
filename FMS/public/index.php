<?php
$require_header = __DIR__ . '/../includes/header.php';
$require_footer = __DIR__ . '/../includes/footer.php';
$require_config = __DIR__ . '/../config/database.php';
$require_auth   = __DIR__ . '/../src/auth.php';

require_once $require_config;
require_once $require_auth;
require_once $require_header;

$dbInstance = new Database();
$pdo = $dbInstance->connect();

$auth = new Auth($pdo);
?>

<main>
    <h1>Welcome to the Application</h1>
    <p>The OOP environment is loaded and running.</p>
</main>

<?php
require_once $require_footer;
?>