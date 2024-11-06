<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // First step: Store attendee details
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO attendees (attendee_name, email) VALUES (:name, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'email' => $email]);
    $attendee_id = $pdo->lastInsertId();
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
    </form>
</body>
</html>
