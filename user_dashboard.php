<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">User Dashboard</h1>
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
        <!-- Add your user functionalities here -->
        <div class="mt-4">
            <a href="genres.php" class="btn btn-primary me-2"><i class="bi bi-calendar-event"></i> View Events</a>
            <a href="my_bookings.php" class="btn btn-info me-2"><i class="bi bi-ticket-detailed"></i> My Bookings</a>
            <a href="logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
