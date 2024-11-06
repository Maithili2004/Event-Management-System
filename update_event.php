<?php
include 'dbconnection.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch event details
    $sql = "SELECT * FROM events WHERE event_id = :event_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['event_id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found.");
    }
} else {
    die("Event ID not specified.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $venue = $_POST['venue'];
    $ticket_price = $_POST['ticket_price'];
    $organizer_id = $_POST['organizer_id'];
    $genre = $_POST['genre'];  // Capture genre from form

    // Update event details
    $sql = "UPDATE events SET event_name = :event_name, event_date = :event_date, event_time = :event_time, venue = :venue, ticket_price = :ticket_price, organizer_id = :organizer_id, genre = :genre WHERE event_id = :event_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'event_name' => $event_name,
        'event_date' => $event_date,
        'event_time' => $event_time,
        'venue' => $venue,
        'ticket_price' => $ticket_price,
        'organizer_id' => $organizer_id,
        'genre' => $genre,  // Bind genre parameter
        'event_id' => $event_id
    ]);
    header("Location: list_events.php");
    exit;
}

// Fetch organizers for the dropdown
$sql = "SELECT * FROM organizers";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$organizers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            padding: 8px;
            width: 100%;
            max-width: 400px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Update Event</h1>
    <form method="POST">
        <label for="event_name">Event Name:</label>
        <input type="text" name="event_name" id="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>

        <label for="event_date">Event Date:</label>
        <input type="date" name="event_date" id="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required>

        <label for="event_time">Event Time:</label>
        <input type="time" name="event_time" id="event_time" value="<?= htmlspecialchars($event['event_time']) ?>" required>

        <label for="venue">Venue:</label>
        <input type="text" name="venue" id="venue" value="<?= htmlspecialchars($event['venue']) ?>" required>

        <label for="ticket_price">Ticket Price:</label>
        <input type="number" name="ticket_price" id="ticket_price" step="0.01" value="<?= htmlspecialchars($event['ticket_price']) ?>" required>

        <label for="organizer_id">Organizer:</label>
        <select name="organizer_id" id="organizer_id" required>
            <?php foreach ($organizers as $organizer): ?>
                <option value="<?= $organizer['organizer_id'] ?>" <?= $organizer['organizer_id'] == $event['organizer_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($organizer['organizer_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre" value="<?= htmlspecialchars($event['genre']) ?>" required>

        <button type="submit">Update Event</button>
    </form>

    <a href="list_events.php" class="back-link">Back to Event List</a>
</body>
</html>
