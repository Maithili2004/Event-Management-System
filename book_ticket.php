<?php
include 'dbconnection.php';

$event_id = $_GET['event_id'];
$sql = "SELECT * FROM events WHERE event_id = :event_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['event_id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Ticket</title>
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
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .total-amount {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
            color: #333;
        }
        button {
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            width: 100%;
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
    <script>
        function calculateTotal() {
            const pricePerTicket = <?= json_encode($event['ticket_price'] ?? 0) ?>; // Get price from PHP
            const numTickets = document.getElementById("numTickets").value;

            // Ensure the value is a valid number before calculation
            const totalAmount = pricePerTicket * (parseInt(numTickets) || 0);

            // Update the displayed total
            document.getElementById("totalAmount").innerText = "Total Amount: Rs. " + totalAmount;
        }
    </script>
</head>
<body>
    <h1>Book Ticket for <?= htmlspecialchars($event['event_name']) ?></h1>
    <form action="confirm_ticket.php" method="POST">
        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Number of Tickets:</label>
        <input type="number" name="no_of_tickets" id="numTickets" min="1" required oninput="calculateTotal()">
        
        <!-- Display total amount dynamically -->
        <div class="total-amount" id="totalAmount">Total Amount: Rs. 0</div>
        
        <button type="submit">Do Payment</button>
    </form>
    <a href="events_by_genre.php?genre_id=<?= $event['genre_id'] ?>" class="back-link">Back to Events</a>
</body>
</html>
