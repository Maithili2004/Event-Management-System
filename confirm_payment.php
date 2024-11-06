<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $attendee_id = $_POST['attendee_id'];
    $payment_method = $_POST['payment_method'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
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
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
    <h1>Enter Payment Details</h1>
    <form action="process_payment.php" method="POST">
        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">
        <input type="hidden" name="attendee_id" value="<?= htmlspecialchars($attendee_id) ?>">

        <?php if ($payment_method === 'credit_card'): ?>
            <label>Card Number:</label>
            <input type="text" name="card_number" required>

            <label>Expiration Date:</label>
            <input type="text" name="expiration_date" required>

            <label>CVV:</label>
            <input type="text" name="cvv" required>

        <?php elseif ($payment_method === 'paypal'): ?>
            <label>UPI ID:</label>
            <input type="text" name="paypal_upiId" required>
            
            <?php elseif ($payment_method === 'debit_card'): ?>
            <label>Card Number:</label>
            <input type="text" name="card_number" required>

            <label>Expiration Date:</label>
            <input type="text" name="expiration_date" required>

            <label>CVV:</label>
            <input type="text" name="cvv" required>

            <?php elseif ($payment_method === 'net_banking'): ?>
                <label>Account Holder Name:</label>
                <input type="text" name="acc_holder_name" required>
            <label>Account number:</label>
            <input type="text" name="acc_no" required>
            <label>IFSC code:</label>
            <input type="text" name="ifsc" required>
           
        <?php endif; ?>


        <button type="submit">Confirm Payment</button>
    </form>
</body>
</html>
