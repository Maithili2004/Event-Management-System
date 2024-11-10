<?php
include 'dbconnection.php';

// Fetch available genres
$sql = "SELECT * FROM genres";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Genre</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
        }
        h1 {
            text-align: center;
        }
        .genre-button {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 10px auto;
            text-align: center;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
        }
        .genre-button:hover {
            background-color: #0056b3;
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
    <h1>Select a Genre</h1>
    <?php foreach ($genres as $genre): ?>
        <a href="events_by_genre.php?genre_id=<?= $genre['genre_id'] ?>" class="genre-button">
            <?= htmlspecialchars($genre['genre_name']) ?>
        </a>
    <?php endforeach; ?>
    <a href="user_dashboard.php" class="home-button">Go To Dashboard</a>
</body>
</html>
