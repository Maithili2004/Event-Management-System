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
    $start_hour = $_POST['start_hour'];
    $start_minute = $_POST['start_minute'];
    $start_am_pm = $_POST['start_am_pm'];
    $end_hour = $_POST['end_hour'];
    $end_minute = $_POST['end_minute'];
    $end_am_pm = $_POST['end_am_pm'];
    $venue = $_POST['venue'];
    $ticket_price = $_POST['ticket_price'];
    $organizer_id = $_POST['organizer_id'];
    $genre = $_POST['genre'];

    // Convert 12-hour format to 24-hour format
    $start_time = ($start_am_pm == 'PM' && $start_hour != 12) ? ($start_hour + 12) : $start_hour;
    $end_time = ($end_am_pm == 'PM' && $end_hour != 12) ? ($end_hour + 12) : $end_hour;
    $start_time = sprintf('%02d:%02d', $start_time, $start_minute);
    $end_time = sprintf('%02d:%02d', $end_time, $end_minute);

    // Update event details
    $sql = "UPDATE events SET event_name = :event_name, event_date = :event_date, start_time = :start_time, end_time = :end_time, 
    venue = :venue, ticket_price = :ticket_price, organizer_id = :organizer_id, genre = :genre WHERE event_id = :event_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'event_name' => $event_name,
        'event_date' => $event_date,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'venue' => $venue,
        'ticket_price' => $ticket_price,
        'organizer_id' => $organizer_id,
        'genre' => $genre,
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
            background-image: url('images/background.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input {
            padding: 8px;
            width: 100%;
            max-width: 400px;
            margin-bottom: 15px;
        }
        select {
    padding: 5px;
    width: 60px; /* Smaller width for the dropdown */
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

        <label for="start_time">Start Time:</label>
        <select name="start_hour" id="start_hour" required>
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= $i == (int)date('g', strtotime($event['start_time'])) ? 'selected' : '' ?>>
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>
        <select name="start_minute" id="start_minute" required>
            <?php for ($i = 0; $i < 60; $i += 5): ?>
                <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= str_pad($i, 2, '0', STR_PAD_LEFT) == date('i', strtotime($event['start_time'])) ? 'selected' : '' ?>>
                    <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                </option>
            <?php endfor; ?>
        </select>
        <select name="start_am_pm" id="start_am_pm" required>
            <option value="AM" <?= date('A', strtotime($event['start_time'])) == 'AM' ? 'selected' : '' ?>>AM</option>
            <option value="PM" <?= date('A', strtotime($event['start_time'])) == 'PM' ? 'selected' : '' ?>>PM</option>
        </select>

        <label for="end_time">End Time:</label>
        <select name="end_hour" id="end_hour" required>
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= $i ?>" <?= $i == (int)date('g', strtotime($event['end_time'])) ? 'selected' : '' ?>>
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>
        <select name="end_minute" id="end_minute" required>
            <?php for ($i = 0; $i < 60; $i += 5): ?>
                <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= str_pad($i, 2, '0', STR_PAD_LEFT) == date('i', strtotime($event['end_time'])) ? 'selected' : '' ?>>
                    <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                </option>
            <?php endfor; ?>
        </select>
        <select name="end_am_pm" id="end_am_pm" required>
            <option value="AM" <?= date('A', strtotime($event['end_time'])) == 'AM' ? 'selected' : '' ?>>AM</option>
            <option value="PM" <?= date('A', strtotime($event['end_time'])) == 'PM' ? 'selected' : '' ?>>PM</option>
        </select>

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
