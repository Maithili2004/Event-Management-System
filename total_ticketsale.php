<?php
include 'dbconnection.php';

// Fetch events for the selection dropdown
$sql_events = "SELECT event_id, event_name FROM events";
$stmt_events = $pdo->prepare($sql_events);
$stmt_events->execute();
$events = $stmt_events->fetchAll(PDO::FETCH_ASSOC);

$sales_data = [];

if (isset($_POST['event_id'])) {
    $selected_event_id = $_POST['event_id'];

    // SQL query to calculate total sales for the selected event
    $sql_total_sales = "
        SELECT e.event_name, e.genre, e.ticket_price, 
               COUNT(a.attendee_id) AS attendee_count,
               (e.ticket_price * COUNT(a.attendee_id)) AS total_sales
        FROM events e
        LEFT JOIN attendees a ON e.event_id = a.event_id
        WHERE e.event_id = :event_id
        GROUP BY e.event_id, e.event_name, e.genre, e.ticket_price
    ";

    // Execute the query to get total ticket sales
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
            background-color:#f4f4f4;
            padding: 20px;
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

<h1>View Ticket Sales for an Event</h1>

<!-- Event selection form -->
<form method="POST">
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

<!-- Display total ticket sales for the selected event -->
<?php if (!empty($sales_data)): ?>
    <h2>Total Ticket Sales for Event</h2>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Genre</th>
            <th>Ticket Price</th>
            <th>Number of Attendees</th>
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
