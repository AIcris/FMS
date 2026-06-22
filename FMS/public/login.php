<?php
$require_config = __DIR__ . '/../config/database.php';
$require_auth   = __DIR__ . '/../src/auth.php';

require_once $require_config;
require_once $require_auth;

$dbInstance = new Database();
$pdo = $dbInstance->connect();
$auth = new Auth($pdo);

// 1. Smart redirect if they are already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: staff/dashboard.php');
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['usernameOremial']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        if ($auth->login($username, $password)) {
            
            // 2. Smart redirect immediately after a successful login
            if ($_SESSION['role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: staff/dashboard.php');
            }
            exit;
            
        } else {
            $error = "Invalid username, password, or inactive account status.";
        }
    } else {
        $error = "Please provide both your username or email and password.";
    }
}

$require_header = __DIR__ . '/../includes/header.php';
$require_footer = __DIR__ . '/../includes/footer.php';
require_once $require_header;
?>

<div class="form-container" style="max-width: 400px; margin: 60px auto;">
    <div class="header">
        <h1>System Log In</h1>
        <p>Access the feedback administration engine</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="instruction-box" style="background-color: #ffebee; border-left-color: #d32f2f; color: #c62828;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="usernameOremial">Username or Email:</label>
            <input type="text" id="usernameOremial" name="usernameOremial" required>
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
         <a href="#" style="color: var(--primary-green); font-weight: 600;">Forgot Password?</a>
        <div class="btn-container">
            <button type="submit" class="nav-btn submit-btn" style="width: 100%;">Log In</button>
        </div>
       
        
    </form>
</div>

<?php require_once $require_footer; ?>