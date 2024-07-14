<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php"); // Redirect to admin login page if not logged in
    exit();
}

// Fetch all adoption requests
$sql = "SELECT q.*, u.name AS user_name, a.name AS animal_name, a.image AS animal_image, a.breed AS animal_breed, a.age AS animal_age, a.color AS animal_color, a.sex AS animal_sex, a.status AS animal_status
        FROM questionnaire q
        INNER JOIN users u ON q.user_id = u.id
        INNER JOIN animals a ON q.animal_id = a.id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Customer Requests</title>
    <link rel="stylesheet" href="user-requests.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard - Customer Requests</h1>
        <div class="logout">
            <button onclick="location.href='logout.php'">Log Out</button>
            <img class="search" src="Assests/icons/search.png" alt="">
        </div>
    </header>
    <div class="sidebar">
        <div class="logo">
            <!-- <img src="assets/logo.png" alt="Animal Adoption Logo"> -->
        </div>
        <nav>
            <ul>
                <li><a href="admin-dashboard.php">Animal - All</a></li>
                <li><a href="add-animal.php">Add Animal</a></li>
                <li><a href="user-requests.php">Customer Request</a></li>
                <li><a href="user-messages.php">Customer Messages</a></li>
            </ul>
        </nav>
    </div>
    <main>
        <div class="requests">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="request">';
                    echo '<a href="user-questionnaire.php?request_id=' . htmlspecialchars($row['id']) . '">';
                    echo '<div class="user">';
                    echo '<h2>' . htmlspecialchars($row['user_name']) . '</h2>';
                    echo '<p>' . htmlspecialchars($row['adopter']) . '</p>';
                    echo '</div>';
                    echo '</a>';
                    echo '<img src="' . htmlspecialchars($row['animal_image']) . '" alt="' . htmlspecialchars($row['animal_name']) . '">';
                    echo '<div class="details">';
                    echo '<h2>' . htmlspecialchars($row['animal_name']) . '</h2>';
                    echo '<p><strong>Breed:</strong> ' . htmlspecialchars($row['animal_breed']) . '</p>';
                    echo '<p><strong>Age:</strong> ' . htmlspecialchars($row['animal_age']) . '</p>';
                    echo '<p><strong>Color:</strong> ' . htmlspecialchars($row['animal_color']) . '</p>';
                    echo '<p>                       <strong>Sex:</strong> ' . htmlspecialchars($row['animal_sex']) . '</p>';
                    if ($row['animal_status'] == 'approved') {
                        echo '<p><strong>Status:</strong> Approved</p>';
                    } else {
                        echo '<form action="approve_delete_request.php" method="post">';
                        echo '<input type="hidden" name="request_id" value="' . htmlspecialchars($row['id']) . '">';
                        echo '<button type="submit" name="action" value="approve" class="approve-btn">Approve</button>';
                        echo '<button type="submit" name="action" value="delete" class="delete-btn">Delete</button>';
                        echo '</form>';
                        
                    }
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No customer requests found.</p>';
            }

            $conn->close();
            ?>
        </div>
    </main>
</body>
</html>