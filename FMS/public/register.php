<?php
$require_config = __DIR__ . '/../config/database.php';
$require_auth   = __DIR__ . '/../src/auth.php';

require_once $require_config;
require_once $require_auth;

$dbInstance = new Database();
$pdo = $dbInstance->connect();
$auth = new Auth($pdo);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $role      = $_POST['role'] ?? 'staff'; 

    if (!empty($firstname) && !empty($lastname) && !empty($username) && !empty($email) && !empty($password)) {
        $result = $auth->register($firstname, $lastname, $username, $email, $password, $role);
        if ($result === true) {
            $message = "Registration successful! You can now log in.";
        } else {
            $error = $result; 
        }
    } else {
        $error = "Please fill out all mandatory fields.";
    }
}

$require_header = __DIR__ . '/../includes/header.php';
$require_footer = __DIR__ . '/../includes/footer.php';
require_once $require_header;
?>

<div class="form-container" style="max-width: 450px; margin: 40px auto;">
    <div class="header">
        <h1>Create Account</h1>
        <p>Register administrative or tracking staff profiles</p>
    </div>

    <?php if (!empty($message)): ?>
        <div class="instruction-box" style="background-color: #e8f5e9; border-left-color: var(--clsu-green);"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="instruction-box" style="background-color: #ffebee; border-left-color: #d32f2f; color: #c62828;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="role">Account Role:</label>
            <select id="role" name="role" required>
                <option value="staff">Staff Member</option>
                <option value="admin">System Administrator</option>
            </select>
        </div>
        <div class="btn-container">
            <button type="submit" class="nav-btn submit-btn" style="width: 100%;">Register User</button>
        </div>
        <p style="text-align: center; margin-top: 15px; font-size: 14px;">Already registered? <a href="login.php">Login here</a></p>
    </form>
</div>

<?php require_once $require_footer; ?>