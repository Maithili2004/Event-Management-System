<?php
include 'dbconnection.php';

// Start the session to get the logged-in user ID
session_start();
$user_id = $_SESSION['user_id'] ?? null; // Make sure user_id is stored in session when logging in

if (!$user_id) {
    echo "Please log in to confirm your booking.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetching data from the form
    $event_id = $_POST['event_id'];
    $attendee_name = $_POST['name'];
    $email = $_POST['email'];
    $ticket_quantity = $_POST['no_of_tickets']; // Capture ticket quantity

    // Fetch event price for calculating total amount
    $sql_event = "SELECT ticket_price FROM events WHERE event_id = :event_id";
    $stmt_event = $pdo->prepare($sql_event);
    $stmt_event->execute(['event_id' => $event_id]);
    $event = $stmt_event->fetch(PDO::FETCH_ASSOC);
    $ticket_price = $event['ticket_price'] ?? 0;
    $total_amount = $ticket_price * $ticket_quantity;

    // Insert the attendee details, including the user_id
    $sql = "INSERT INTO attendees (attendee_name, email, user_id, event_id, ticket_quantity) VALUES (:attendee_name, :email, :user_id, :event_id, :ticket_quantity)";
     $stmt = $pdo->prepare($sql);
    $stmt->execute([
    'attendee_name' => $attendee_name,
    'email' => $email,
    'user_id' => $user_id,
    'event_id' => $event_id,
    'ticket_quantity' => $ticket_quantity
]);


    // Get the last inserted attendee ID
    $attendee_id = $pdo->lastInsertId();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Ticket</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            background-image: url('images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="radio"] {
            margin-bottom: 15px;
        }
        button, .back-link {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }
        button:hover, .back-link:hover {
            background-color: #0056b3;
        }
        .total-amount {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Choose Payment Method</h1>
    <div class="container">
        <!-- Display total amount dynamically -->
        <div class="total-amount">Total Amount to Pay: Rs. <?= htmlspecialchars($total_amount) ?></div>
        
        <form action="confirm_payment.php" method="POST">
            <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">
            <input type="hidden" name="attendee_id" value="<?= htmlspecialchars($attendee_id) ?>">
            <input type="hidden" name="total_amount" value="<?= htmlspecialchars($total_amount) ?>">

            <label><input type="radio" name="payment_method" value="credit_card" required> Credit Card</label>
            <label><input type="radio" name="payment_method" value="paypal" required> PayPal</label>
            <label><input type="radio" name="payment_method" value="debit_card" required> Debit Card</label>
            <label><input type="radio" name="payment_method" value="net_banking" required> Net Banking</label>

            <button type="submit">Proceed to Payment</button>
            <a href="genres.php" class="back-link">Cancel Payment</a>
        </form>
    </div>
</body>
</html>
