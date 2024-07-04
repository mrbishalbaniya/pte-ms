<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php"); // Redirect to admin login page if not logged in
    exit();
}

// Get the request_id from the URL
$request_id = isset($_GET['request_id']) ? intval($_GET['request_id']) : 0;

if ($request_id <= 0) {
    echo "Invalid request ID.";
    exit();
}

// Fetch the questionnaire details based on the request_id
$sql = "SELECT * FROM questionnaire WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No questionnaire found for the given request ID.";
    exit();
}

$row = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Questionnaire</title>
    <link rel="stylesheet" href="user-questionnaire.css">
</head>

<body>
    <header>
        <h1>Admin Dashboard - User Questionnaire</h1>
        <div class="logout">
            <button onclick="location.href='logout.php'">Log Out</button>
            <img class="search" src="Assests/icons/search.png" alt="">
        </div>
    </header>
    <div class="sidebar">
        <div class="logo">
            <img src="assets/logo.png" alt="Animal Adoption Logo">
        </div>
        <nav>
            <ul>
                <li><a href="admin-dashboard.php">Animal - All</a></li>
                <li><a href="add-animal.php">Add Animal</a></li>
                <li><a href="user-requests.php">Customer Request</a></li>
                <li><a href="user-messages.php">Customer Messages</a></li>
                <li><a href="#">History</a></li>
            </ul>
        </nav>
    </div>
    <main>
        <div class="container">
            <div class="answers">
                <div class="section">
                    <h2>Your Family</h2>
                    <p><strong>Who are you planning to adopt this pet for?:</strong> <br> <?php echo htmlspecialchars($row['adopter']); ?></p>
                    <p><strong>Who will be the primary caregiver for this pet?:</strong> <br> <?php echo htmlspecialchars($row['primary_caregiver']); ?></p>
                    <p><strong>Number of children at home:</strong> <br> <?php echo htmlspecialchars($row['children_count']); ?></p>
                    <p><strong>List the ages of the children at home:</strong> <br> <?php echo htmlspecialchars($row['children_ages']); ?></p>
                    <p><strong>Pet Allergies in the family:</strong> <br> <?php echo htmlspecialchars($row['pet_allergies']); ?></p>
                </div>
                <div class="section">
                    <h2>Your Lifestyle</h2>
                    <p><strong>Type of Residence:</strong> <br> <?php echo htmlspecialchars($row['residence_type']); ?></p>
                    <p><strong>Where will your pet stay during the day:</strong> <br> <?php echo htmlspecialchars($row['day_stay_location']); ?></p>
                    <p><strong>Where will your pet sleep at night:</strong> <br> <?php echo htmlspecialchars($row['night_sleep_location']); ?></p>
                    <p><strong>Pet Allergies in the family:</strong> <br> <?php echo htmlspecialchars($row['pet_allergies']); ?></p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>