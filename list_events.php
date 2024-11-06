<?php
include 'dbconnection.php';

// Handle deletion if requested
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Delete related records in event_attendees first
    $deleteAttendeesSql = "DELETE FROM event_attendees WHERE event_id = :event_id";
    $deleteAttendeesStmt = $pdo->prepare($deleteAttendeesSql);
    $deleteAttendeesStmt->execute(['event_id' => $event_id]);

    // Now delete the event from the events table
    $deleteEventSql = "DELETE FROM events WHERE event_id = :event_id";
    $deleteEventStmt = $pdo->prepare($deleteEventSql);
    $deleteEventStmt->execute(['event_id' => $event_id]);

    // Redirect back to the list after deletion
    header('Location: list_events.php');
    exit;
}


// Fetch events along with their organizers and genres
$sql = "SELECT events.event_id, events.event_name, events.event_date, events.start_time, events.end_time, events.genre, organizers.organizer_name 
        FROM events 
        JOIN organizers ON events.organizer_id = organizers.organizer_id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px auto;
            max-width: 800px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
        }
        .delete-button {
            background-color: #dc3545;
        }
        .home-button {
            padding: 10px;
            width: 100%;
            margin-top: 20px;
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
    <h1>Events</h1>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Genre</th>
            <th>Organizer</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($events as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['event_name']) ?></td>
            <td><?= htmlspecialchars($event['event_date']) ?></td>
            <td><?= htmlspecialchars($event['start_time']) ?></td> <!-- Display start time -->
            <td><?= htmlspecialchars($event['end_time']) ?></td> <!-- Display end time -->
            <td><?= htmlspecialchars($event['genre']) ?></td>
            <td><?= htmlspecialchars($event['organizer_name']) ?></td>
            <td>
                <!-- Update and Delete buttons -->
                <a href="update_event.php?event_id=<?= $event['event_id'] ?>">
                    <button>Update</button>
                </a>
                <a href="list_events.php?action=delete&event_id=<?= $event['event_id'] ?>" onclick="return confirm('Are you sure you want to delete this event?');">
                    <button class="delete-button">Delete</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <!-- Button to go back to add_event.php (Home) -->
    <form action="add_event.php">
        <button type="submit" class="home-button">Home</button>
    </form>
</body>
</html>
