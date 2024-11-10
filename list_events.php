<?php
include 'dbconnection.php';

$searchTerm = '';
$events = [];

// Handle deletion if requested
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Delete the event from the events table
    $deleteEventSql = "DELETE FROM events WHERE event_id = :event_id";
    $deleteEventStmt = $pdo->prepare($deleteEventSql);
    $deleteEventStmt->execute(['event_id' => $event_id]);

    // Redirect back to the list after deletion
    header('Location: list_events.php');
    exit;
}

// Check if a search term is provided
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql = "SELECT events.event_id, events.event_name, events.event_date, events.start_time, events.end_time, 
                   events.genre, events.ticket_price, organizers.organizer_name 
            FROM events 
            JOIN organizers ON events.organizer_id = organizers.organizer_id
            WHERE events.event_name LIKE :searchTerm";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['searchTerm' => "%" . $searchTerm . "%"]);
} else {
    // Fetch events with their organizers and genres
    $sql = "SELECT events.event_id, events.event_name, events.event_date, events.start_time, events.end_time, 
                   events.genre, events.ticket_price, organizers.organizer_name 
            FROM events 
            JOIN organizers ON events.organizer_id = organizers.organizer_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

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
    padding: 20px;
    background-image: url('images/background.jpg'); /* Specify the path to your image */
    background-size: cover; /* Ensures the image covers the entire background */
    background-position: center; /* Centers the background image */
    background-attachment: fixed; /* Makes the background fixed while scrolling */

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
            background-color: #f4f4f4;
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
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar {
            padding: 8px;
            font-size: 14px;
            width: 50%;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .search-button {
            padding: 8px 10px;
            font-size: 12px;
            cursor: pointer;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .search-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Events</h1>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="">
            <input type="text" name="search" class="search-bar" placeholder="Search events by name..." value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Genre</th>
            <th>Ticket Price</th> <!-- New column for Ticket Price -->
            <th>Organizer</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($events as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['event_name']) ?></td>
            <td><?= htmlspecialchars($event['event_date']) ?></td>
            <td><?= htmlspecialchars($event['start_time']) ?></td>
            <td><?= htmlspecialchars($event['end_time']) ?></td>
            <td><?= htmlspecialchars($event['genre']) ?></td>
            <td><?= htmlspecialchars($event['ticket_price']) ?></td> <!-- Display Ticket Price -->
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
        <button type="submit" class="home-button">Add More Events</button>
    </form>
</body>
</html>
