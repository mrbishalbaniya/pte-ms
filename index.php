<?php
// Include database connection
include 'component/conn.php';

// Fetch featured pets from the database that are not approved (max 8)
$query = "SELECT * FROM animals WHERE status != 'approved' LIMIT 8";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Adoption</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include 'component/navbar.php'; ?>
        <div class="hero-section">
            <img src="Assests/images/hero.png" alt="hero">
        </div>
    </header>
    <main>
        <div class="featured-pet">
            <h1>Featured Pets:</h1>
            <div class="pets">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <a href="animal.php?id=<?php echo $row['id']; ?>">
                        <div class="pet">
                            <div class="img">
                                <img height="200px" src="<?php echo $row['image']; ?>" alt="">
                            </div>
                            <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                            <p><?php echo htmlspecialchars(implode(' ', array_slice(explode(' ', $row['description']), 0, 8))); ?>...</p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        <?php include 'about.php'; ?>
        <?php include 'contact.php'; ?>
        <div class="message-btn">
            <a href="message.php"><button>Leave us a message!</button></a>
        </div>
    </main>
    <?php include 'component/footer.php'; ?>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
