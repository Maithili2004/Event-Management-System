<?php
include 'dbconnection.php';

if (isset($_GET['genre_id'])) {
    $genre_id = $_GET['genre_id'];

    // Fetch the genre name based on genre_id
    $sqlGenre = "SELECT genre_name FROM genres WHERE genre_id = :genre_id";
    $stmtGenre = $pdo->prepare($sqlGenre);
    $stmtGenre->execute(['genre_id' => $genre_id]);
    $genre = $stmtGenre->fetchColumn(); // Get the genre name

    // Check if genre exists
    if (!$genre) {
        die("Invalid genre ID.");
    }

    // Fetch events based on the selected genre
    $sqlEvents = "SELECT * FROM events WHERE genre = :genre";
    $stmtEvents = $pdo->prepare($sqlEvents);
    $stmtEvents->execute(['genre' => $genre]);
    $events = $stmtEvents->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Genre not specified.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Events by Genre</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .back-link, .book-ticket-button {
            padding: 8px 10px;
            font-size: 12px;
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
        .back-link:hover, .book-ticket-button:hover {
            background-color: #0056b3;
        }
        .event-item {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Events in <?= htmlspecialchars($genre) ?></h1>
    <ul>
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <li class="event-item">
                    <strong><?= htmlspecialchars($event['event_name']) ?></strong><br>
                    Date: <?= htmlspecialchars($event['event_date']) ?><br>
                    Time: <?= htmlspecialchars($event['start_time']) ?> - <?= htmlspecialchars($event['end_time']) ?><br>
                    Venue: <?= htmlspecialchars($event['venue']) ?><br>
                    Price: <?="Rs. " . htmlspecialchars($event['ticket_price']) ?><br>
                    <a href="book_ticket.php?event_id=<?= $event['event_id'] ?>" class="book-ticket-button">Book Ticket</a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No events found for this genre.</li>
        <?php endif; ?>
    </ul>
    <a href="genres.php" class="back-link">Back to Genres</a>
</body>
</html>
