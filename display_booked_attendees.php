<?php
include 'dbconnection.php';

// Fetch unique genres from the events table
$sql_genres = "SELECT DISTINCT genre FROM events";
$stmt_genres = $pdo->prepare($sql_genres);
$stmt_genres->execute();
$genres = $stmt_genres->fetchAll(PDO::FETCH_ASSOC);

// Handle genre selection and fetch events
if (isset($_POST['genre'])) {
    $selected_genre = $_POST['genre'];

    // Fetch events based on the selected genre
    $sql_events = "SELECT event_id, event_name FROM events WHERE genre = :genre";
    $stmt_events = $pdo->prepare($sql_events);
    $stmt_events->execute(['genre' => $selected_genre]);
    $events = $stmt_events->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch attendees for the selected event
if (isset($_POST['event_id'])) {
    $selected_event_id = $_POST['event_id'];

    // Use UNION to fetch attendees directly based on event and genre
    $sql_attendees = "
       SELECT a.attendee_id, a.attendee_name, a.email, e.event_name, e.genre
       FROM attendees a
       JOIN events e ON a.event_id = e.event_id
       WHERE e.event_id = :event_id AND e.genre = :genre
       ORDER BY a.attendee_name
    ";
    $stmt_attendees = $pdo->prepare($sql_attendees);
    $stmt_attendees->execute(['event_id' => $selected_event_id, 'genre' => $selected_genre]);
    $attendees = $stmt_attendees->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Display Booked Attendees</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: blue;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>View Attendees by Genre and Event</h1>

    <!-- Genre selection form -->
    <form method="POST">
        <label for="genre">Select Genre:</label>
        <select name="genre" id="genre" required>
            <option value="">-- Select Genre --</option>
            <?php foreach ($genres as $genre): ?>
                <option value="<?= htmlspecialchars($genre['genre']) ?>" <?= isset($selected_genre) && $selected_genre == $genre['genre'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($genre['genre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Show Events</button>
    </form>

    <!-- Event selection form -->
    <?php if (isset($events) && count($events) > 0): ?>
        <form method="POST">
            <input type="hidden" name="genre" value="<?= htmlspecialchars($selected_genre) ?>">
            <label for="event_id">Select Event:</label>
            <select name="event_id" id="event_id" required>
                <option value="">-- Select Event --</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event['event_id'] ?>" <?= isset($selected_event_id) && $selected_event_id == $event['event_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($event['event_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Show Attendees</button>
        </form>
    <?php elseif (isset($selected_genre)): ?>
        <p>No events found for this genre.</p>
    <?php endif; ?>

    <!-- Display attendees for the selected event -->
    <?php if (isset($attendees) && count($attendees) > 0): ?>
        <h2 style="text-align: center;">Attendees for Selected Event and Genre</h2>
        <table>
            <tr>
                <th>Attendee ID</th>
                <th>Attendee Name</th>
                <th>Email</th>
                <th>Event Name</th>
                <th>Genre</th>
            </tr>
            <?php foreach ($attendees as $attendee): ?>
                <tr>
                    <td><?= htmlspecialchars($attendee['attendee_id']) ?></td>
                    <td><?= htmlspecialchars($attendee['attendee_name']) ?></td>
                    <td><?= htmlspecialchars($attendee['email']) ?></td>
                    <td><?= htmlspecialchars($attendee['event_name']) ?></td>
                    <td><?= htmlspecialchars($attendee['genre']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (isset($selected_event_id)): ?>
        <p style="text-align: center;">No attendees found for this event and genre.</p>
    <?php endif; ?>
</body>
</html>
