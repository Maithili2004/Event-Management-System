<?php
include 'dbconnection.php';  // Include the PDO connection file
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Prepare the SQL query to get bookings made by the logged-in user
$sql = "SELECT attendees.attendee_id, attendees.attendee_name, events.event_name, events.event_date
        FROM attendees
        JOIN events ON attendees.event_id = events.event_id
        WHERE attendees.user_id = :user_id";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind the user ID parameter
$stmt->execute();

// Fetch results
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 0 auto;
            background-color: white;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            color: #555;
        }
        p {
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>My Bookings</h1>
    
    <?php if (count($bookings) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Attendee Name</th>
                    <th>Event Name</th>
                    <th>Event Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["attendee_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["attendee_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["event_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["event_date"]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</body>
</html>
