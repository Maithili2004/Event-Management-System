<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $start_time = $_POST['start_time']; // Capture start time
    $end_time = $_POST['end_time']; // Capture end time
    $venue = $_POST['venue'];
    $ticket_price = $_POST['ticket_price'];
    $organizer_id = $_POST['organizer_id'];
    $genre = $_POST['genre']; // Capture genre from the form

    $sql = "INSERT INTO events (event_name, event_date, start_time, end_time, venue, ticket_price, organizer_id, genre) VALUES (:event_name, :event_date, :start_time, :end_time, :venue, :ticket_price, :organizer_id, :genre)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'event_name' => $event_name,
        'event_date' => $event_date,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'venue' => $venue,
        'ticket_price' => $ticket_price,
        'organizer_id' => $organizer_id,
        'genre' => $genre
    ]);

    header('Location: list_events.php');
    exit;
}

$sql = "SELECT * FROM organizers";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$organizers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button, .add-organizer-link {
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            width: 100%;
            display: inline-block;
            text-align: center;
        }
        button:hover, .add-organizer-link:hover {
            background-color: #0056b3;
        }
        .form-inline {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .submit-container {
            margin-top: 20px;
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
        .home-button {
            padding: 10px;
            width: 60%;
            margin-top: 20px;
            margin-left:300px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            text-align: center;
        }
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Add New Event</h1>

    <!-- Main form to add an event -->
    <form method="POST">
        <div class="form-group">
            <label>Event Name:</label>
            <input type="text" name="event_name" required>
        </div>

        <div class="form-group">
            <label>Event Date:</label>
            <input type="date" name="event_date" required>
        </div>

        <div class="form-group">
            <label>Start Time:</label>
            <select name="start_time" required>
                <?php for ($hour = 1; $hour <= 12; $hour++): ?>
                    <?php foreach (['AM', 'PM'] as $period): ?>
                        <option value="<?= sprintf('%02d:00 %s', $hour, $period) ?>"><?= sprintf('%02d:00 %s', $hour, $period) ?></option>
                        <?php for ($minute = 15; $minute < 60; $minute += 15): ?>
                            <option value="<?= sprintf('%02d:%02d %s', $hour, $minute, $period) ?>"><?= sprintf('%02d:%02d %s', $hour, $minute, $period) ?></option>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label>End Time:</label>
            <select name="end_time" required>
                <?php for ($hour = 1; $hour <= 12; $hour++): ?>
                    <?php foreach (['AM', 'PM'] as $period): ?>
                        <option value="<?= sprintf('%02d:00 %s', $hour, $period) ?>"><?= sprintf('%02d:00 %s', $hour, $period) ?></option>
                        <?php for ($minute = 15; $minute < 60; $minute += 15): ?>
                            <option value="<?= sprintf('%02d:%02d %s', $hour, $minute, $period) ?>"><?= sprintf('%02d:%02d %s', $hour, $minute, $period) ?></option>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Venue:</label>
            <input type="text" name="venue" required>
        </div>

        <div class="form-group">
            <label>Ticket Price:</label>
            <input type="number" name="ticket_price" step="0.01" required>
        </div>

        <!-- Genre selection dropdown -->
        <div class="form-group">
            <label>Genre:</label>
            <select name="genre" required>
                <?php
                // Fetch genres from the database
                $genreSql = "SELECT * FROM genres";
                $genreStmt = $pdo->prepare($genreSql);
                $genreStmt->execute();
                $genres = $genreStmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($genres as $genre): ?>
                    <option value="<?= htmlspecialchars($genre['genre_name']) ?>"><?= htmlspecialchars($genre['genre_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Organizer selection with Add Organizer link styled as a button -->
        <div class="form-inline">
            <label>Organizer:</label>
            <select name="organizer_id" required>
                <?php foreach ($organizers as $organizer): ?>
                    <option value="<?= $organizer['organizer_id'] ?>"><?= htmlspecialchars($organizer['organizer_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <a href="add_organizer.php" class="add-organizer-link">Add Organizer</a>
        </div>

        <!-- Submit button to add the event -->
        <div class="submit-container">
            <button type="submit">Submit</button>
        </div>
    </form>
    <a href="list_events.php" class="back-link">Go to Event List</a>
    <a href="admin_dashboard.php" class="home-button">Go To Dashboard</a>
</body>
</html>
