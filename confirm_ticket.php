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

    // Insert the attendee details, including the user_id
    $sql = "INSERT INTO attendees (attendee_name, email, user_id, event_id) VALUES (:attendee_name, :email, :user_id, :event_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'attendee_name' => $attendee_name,
        'email' => $email,
        'user_id' => $user_id, // Adding user_id to the insert
        'event_id' => $event_id
    ]);
    
    // Get the last inserted attendee ID
    $attendee_id = $pdo->lastInsertId();
    
    // Redirect or show success message after successful insertion
    //echo "Booking confirmed! Your attendee ID is: " . $attendee_id;
    // You can redirect to the payment page here if required
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
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
        input[type="text"], input[type="radio"] {
            margin-bottom: 15px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: blue;
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
    </style>
</head>
<body>
    <h1>Choose Payment Method</h1>
    <form action="confirm_payment.php" method="POST">
        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">
        <input type="hidden" name="attendee_id" value="<?= htmlspecialchars($attendee_id) ?>">

        <label><input type="radio" name="payment_method" value="credit_card" required> Credit Card</label>
        <label><input type="radio" name="payment_method" value="paypal" required> PayPal</label>
        <label><input type="radio" name="payment_method" value="debit_card" required> Debit Card</label>
        <label><input type="radio" name="payment_method" value="net_banking" required> Net Banking</label>

        <button type="submit">Proceed to Payment</button>
        <a href="genres.php" class="back-link">cancel Payment</a>
</body>
    </form>
</body>
</html>
