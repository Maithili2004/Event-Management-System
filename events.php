<?php
// Start the session (if you are handling login sessions)
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Page</title>

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="top-nav">
        <div class="logo-container">
            <a href="events.php">
                <img src="images\Eventsy.jpg" alt="Eventsy Logo" class="logo">
            </a>
        </div>
        <div class="nav-buttons">
            <a href="login.php" class="button">Sign In</a>
            <a href="signup.php" class="button">Sign Up</a>
        </div>
    </nav>
    
    <header>
        <h1>Explore Events Near You</h1>
        <p>Discover a variety of events to inspire, connect, and engage with your community.</p>
    </header>
    
    <main class="events-container">
        <p class="catchy-description">Whether you're an art lover, a music enthusiast, or looking to network, our curated list of events has something for everyone. Join us in exploring memorable experiences that bring people together!</p>
        
        <!-- Additional Sign In and Sign Up Buttons Below Description -->
        <div class="extra-buttons">
            <a href="login.php" class="button">Sign In</a>
            <a href="signup.php" class="button">Sign Up</a>
        </div>

        <div class="event-details" id="event2">
            <h2>Art Exhibition</h2>
            <p>Explore inspiring artworks from emerging artists.</p>
            <button onclick="bookNow()">Book Now</button>
        </div>
    </main>

    <!-- Link to External JavaScript File -->
    <script src="script.js"></script>
</body>

</html>
