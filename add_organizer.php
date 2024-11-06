<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organizer_name = $_POST['organizer_name'];
    $contact_info = $_POST['contact_info'];

    $sql = "INSERT INTO organizers (organizer_name, contact_info) VALUES (:organizer_name, :contact_info)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['organizer_name' => $organizer_name, 'contact_info' => $contact_info]);

    echo "<p>Organizer added successfully!</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Organizer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
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
        input[type="text"],
        input[type="date"],
        select {
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
        .button-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <h1>Add New Organizer</h1>
    <form method="POST">
        <label>Organizer Name:</label>
        <input type="text" name="organizer_name" required>

        <label>Contact Info:</label>
        <input type="text" name="contact_info" required>

        <button type="submit">Add Organizer</button>
    </form>
    <br>
    <div class="button-container">
        <form action="add_event.php">
            <button type="submit">Home</button>
        </form>
    </div>
</body>
</html>
