<?php
include 'dbconnection.php';

$signupSuccess = "";
$signupError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $role = $_POST['role']; // admin or user

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $signupError = "All fields are required.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");

        try {
            $stmt->execute([$username, $email, $hashedPassword, $role]);
            $signupSuccess = "Signup successful! <a href='login.php'>Login here</a>.";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Duplicate entry
                $signupError = "Username or email already exists.";
            } else {
                $signupError = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?> <!-- Optional: If you create a common header -->
<style>
    body{
    background-image: url('images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    }
</style>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Signup</h2>
            <?php if ($signupSuccess): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $signupSuccess; ?>
                </div>
            <?php endif; ?>
            <?php if ($signupError): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $signupError; ?>
                </div>
            <?php endif; ?>
            <form action="signup.php" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" id="role" name="role">
            <option value="user" selected>User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary w-100">Signup</button>
</form>

            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a>.</p>
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
