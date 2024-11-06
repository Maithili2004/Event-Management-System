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
    </style>
</head>
<body>
    <h1>Select a Genre</h1>
    <?php foreach ($genres as $genre): ?>
        <a href="events_by_genre.php?genre_id=<?= $genre['genre_id'] ?>" class="genre-button">
            <?= htmlspecialchars($genre['genre_name']) ?>
        </a>
    <?php endforeach; ?>
</body>
</html>
