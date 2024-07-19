<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="header.css">
    <title>petnest</title>
</head>
<body>
<nav>
    <div class="logo-container">
        <a href="index.php">
            <img src="Assests/icons/PetNest.png" alt="Petnest Logo" class="logo">
        </a>
    </div>
    <div class="nav-links">
        <ul>
           
            <a href="index.php"><li>Home</li></a>
            <a href="animals.php"><li>Animal</li></a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="request.php"><li>My Request</li></a>
                <a href="component/logout.php"><li>Logout</li></a>
            <?php else: ?>
                <a href="login.php"><li>Login</li></a>
            <?php endif; ?>
            
        </ul>
    </div>
</nav>
</body>
</html>
