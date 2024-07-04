<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<nav>
    <ul>
        <a href="index.php"><li>Home</li></a>
        <a href="animals.php"><li>Animal</li></a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="request.php"><li>My Request</li></a>
            <a href="component/logout.php"><li>Logout</li></a>
        <?php else: ?>
            <a href="login.php"><li>Login</li></a>
        <?php endif; ?>
        <li><img class="search" src="Assests/icons/search.png" alt=""></li>
    </ul>
</nav>
</body>
</html>