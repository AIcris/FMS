<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') header('Location: ../login.php');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/UserManager.php';

$dbInstance = new Database();
$pdo = $dbInstance->connect();
$userManager = new UserManager($pdo);

$userId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userManager->updateUser($userId, $_POST);
    header('Location: users.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM account WHERE user_id = ?");
$stmt->execute([$userId]);
$u = $stmt->fetch();
$departments = $userManager->getDepartments();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
<body>
    <div class="workspace-padding" style="max-width: 500px; margin: auto;">
        <div class="panel-card">
            <h2 class="panel-title">Edit Account: <?php echo $u['firstname']; ?></h2>
            <form method="POST">
                <div class="form-group"><label>First Name</label><input type="text" name="firstname" class="form-control" value="<?php echo $u['firstname']; ?>" required></div>
                <div class="form-group"><label>Last Name</label><input type="text" name="lastname" class="form-control" value="<?php echo $u['lastname']; ?>" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo $u['email']; ?>" required></div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="staff" <?php if($u['role']=='staff') echo 'selected'; ?>>Staff</option>
                        <option value="admin" <?php if($u['role']=='admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" class="form-control">
                        <?php foreach ($departments as $d): ?>
                            <option value="<?php echo $d['department_id']; ?>" <?php if($u['department_id']==$d['department_id']) echo 'selected'; ?>>
                                <?php echo $d['department_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Update Account</button>
            </form>
        </div>
    </div>
</body>
</html>