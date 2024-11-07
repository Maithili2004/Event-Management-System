<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
        <!-- Add your admin functionalities here -->
        <div class="mt-4">
            <a href="add_event.php" class="btn btn-success me-2"><i class="bi bi-plus-circle"></i> Add Event</a>
            <a href="list_events.php" class="btn btn-warning me-2"><i class="bi bi-pencil-square"></i> Manage Events</a>
            <a href="logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
