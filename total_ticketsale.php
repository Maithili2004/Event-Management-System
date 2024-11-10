<?php
include 'dbconnection.php';

// Fetch genres for selection
$sql_genres = "SELECT DISTINCT genre FROM events";
$stmt_genres = $pdo->prepare($sql_genres);
$stmt_genres->execute();
$genres = $stmt_genres->fetchAll(PDO::FETCH_ASSOC);

$events = [];
$sales_data = [];

if (isset($_POST['genre'])) {
    $selected_genre = $_POST['genre'];

    // Fetch events based on selected genre
    $sql_events = "SELECT event_id, event_name FROM events WHERE genre = :genre";
    $stmt_events = $pdo->prepare($sql_events);
    $stmt_events->execute(['genre' => $selected_genre]);
    $events = $stmt_events->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['event_id'])) {
    $selected_event_id = $_POST['event_id'];

    // SQL query to calculate total sales based on the ticket quantity booked by each user
    $sql_total_sales = "
        SELECT e.event_name, e.genre, e.ticket_price,
               SUM(a.ticket_quantity) AS attendee_count,
               (e.ticket_price * SUM(a.ticket_quantity)) AS total_sales
        FROM events e
        LEFT JOIN attendees a ON e.event_id = a.event_id
        WHERE e.event_id = :event_id
        GROUP BY e.event_id, e.event_name, e.genre, e.ticket_price
    ";

    $stmt_total_sales = $pdo->prepare($sql_total_sales);
    $stmt_total_sales->execute(['event_id' => $selected_event_id]);
    $sales_data = $stmt_total_sales->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Ticket Sales for Event</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-image: url('images/background.jpg'); /* Specify the path to your image */
    background-size: cover; /* Ensures the image covers the entire background */
    background-position: center; /* Centers the background image */
    background-attachment: fixed; /* Makes the background fixed while scrolling */
}

        h1, h2 {
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
        label, select, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        select, button {
            padding: 10px;
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
            margin: 20px auto;
            border-collapse: collapse;
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

<h1>View Ticket Sales by Genre and Event</h1>

<!-- Genre and Event selection form -->
<form method="POST">
    <label for="genre">Select Genre:</label>
    <select name="genre" id="genre" required onchange="this.form.submit()">
        <option value="">-- Select Genre --</option>
        <?php foreach ($genres as $genre): ?>
            <option value="<?= htmlspecialchars($genre['genre']) ?>" <?= isset($selected_genre) && $selected_genre == $genre['genre'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($genre['genre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if (!empty($events)): ?>
    <form method="POST">
        <input type="hidden" name="genre" value="<?= htmlspecialchars($selected_genre) ?>">
        <label for="event_id">Select Event:</label>
        <select name="event_id" id="event_id" required>
            <option value="">-- Select Event --</option>
            <?php foreach ($events as $event): ?>
                <option value="<?= htmlspecialchars($event['event_id']) ?>" <?= isset($selected_event_id) && $selected_event_id == $event['event_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($event['event_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">View Ticket Sales</button>
    </form>
<?php endif; ?>

<!-- Display total ticket sales for the selected event -->
<?php if (!empty($sales_data)): ?>
    <h2>Total Ticket Sales for Event</h2>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Genre</th>
            <th>Ticket Price</th>
            <th>Number of Tickets</th>
            <th>Total Sales</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($sales_data['event_name']) ?></td>
            <td><?= htmlspecialchars($sales_data['genre']) ?></td>
            <td><?= htmlspecialchars(number_format($sales_data['ticket_price'], 2)) ?></td>
            <td><?= htmlspecialchars($sales_data['attendee_count']) ?></td>
            <td><?= htmlspecialchars(number_format($sales_data['total_sales'], 2)) ?></td>
        </tr>
    </table>
<?php elseif (isset($selected_event_id)): ?>
    <p style="text-align: center;">No sales data found for this event.</p>
<?php endif; ?>
<a href="admin_dashboard.php" class="home-button">Go To Dashboard</a>
</body>
</html>
