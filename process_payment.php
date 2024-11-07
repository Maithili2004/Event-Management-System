<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $attendee_id = $_POST['attendee_id'];

    // Simulate a successful payment for demonstration purposes
    $payment_successful = true; // Replace with actual payment processing logic
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Status</title>
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
        .status-message {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
            text-align: center;
        }
        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
        a:hover {
            opacity: 0.9;
        }
        .home-button {
            padding: 10px;
            width: 60%;
            margin-top: 20px;
            margin-left:300px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            text-align: center;
        }
        .home-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="status-message">
        <?php if ($payment_successful): ?>
            <h1>Payment Successful!</h1>
            <p>Thank you for your payment. Your booking for Event ID: <?= htmlspecialchars($event_id) ?> is confirmed.</p>
            <a href="download_ticket.php?attendee_id=<?= htmlspecialchars($attendee_id) ?>&event_id=<?= htmlspecialchars($event_id) ?>" class="success">Download Ticket</a>
        <?php else: ?>
            <h1>Payment Failed!</h1>
            <p>Sorry, your payment could not be processed. Please try again.</p>
            <a href="payment_page.php?event_id=<?= htmlspecialchars($event_id) ?>" class="error">Retry Payment</a>
        <?php endif; ?>
    </div>
    <a href="genres.php" class="home-button">Home</a>

</body>
</html>
