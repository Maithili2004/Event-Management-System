<?php
include 'dbconnection.php';

// Start the session to get the logged-in user ID
session_start();
$user_id = $_SESSION['user_id'] ?? null; // Make sure user_id is stored in session when logging in

if (!$user_id) {
    echo "Please log in to view your bookings.";
    exit;
}

// Fetch bookings for the logged-in user
$sql = "
    SELECT e.event_name, e.event_date, e.venue, e.start_time, e.end_time, a.attendee_name, a.email
    FROM attendees a
    INNER JOIN events e ON a.event_id = e.event_id
    WHERE a.user_id = :user_id
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($bookings)) {
    echo "<p>No bookings found.</p>";
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .home-button {
            padding: 5px;
            width: 30%;
            margin-top: 10px;
            margin-left:300px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            text-align: center;
        }
        .home-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>My Bookings</h1>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Your Name</th>
            <th>Email</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?= htmlspecialchars($booking['event_name']) ?></td>
            <td><?= htmlspecialchars($booking['event_date']) ?></td>
            <td><?= htmlspecialchars($booking['venue']) ?></td>
            <td><?= htmlspecialchars($booking['start_time']) ?></td>
            <td><?= htmlspecialchars($booking['end_time']) ?></td>
            <td><?= htmlspecialchars($booking['attendee_name']) ?></td>
            <td><?= htmlspecialchars($booking['email']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table><br>
    <a href="user_dashboard.php" class="home-button">Go To Dashboard</a>
</body>
</html>
<?php
}
?>
