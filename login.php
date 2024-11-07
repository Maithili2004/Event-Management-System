<?php
include 'dbconnection.php';
session_start();

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $loginError = "Both fields are required.";
    } else {
        // Prepare SQL statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['password'] = $user['password'];

            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: user_dashboard.php');
            }
            exit;
        } else {
            $loginError = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?> <!-- Optional: If you create a common header -->

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Login</h2>
            <?php if ($loginError): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $loginError; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>

            <p class="mt-3 text-center">Don't have an account? <a href="signup.php">Signup here</a>.</p>
        </div>
    </div>

    <!-- Bootstrap JS (Optional, for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById("username").setAttribute("autocomplete", "new-password");
    document.getElementById("password").setAttribute("autocomplete", "new-password");
</script>

  </body>
</html>
