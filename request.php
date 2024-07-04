<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Adoption Requests</title>
    <link rel="stylesheet" href="request.css">
</head>
<body>
    <header>
        <!-- include nav section here -->
        <?php include 'component/navbar.php'; ?>
    </header>
    <main>
        <div class="container">
            <h1>My Adoption Requests</h1>
            <div class="requests">
                <?php
                include 'component/conn.php'; // Database connection file

                // Check if user is logged in
                if (!isset($_SESSION['user_id'])) {
                    header("Location: login.php"); // Redirect to login page if not logged in
                    exit();
                }

                $user_id = $_SESSION['user_id'];

                // Fetch adoption requests and animal details
                $sql = "SELECT q.*, a.name AS animal_name, a.image AS animal_image, a.breed AS animal_breed, a.age AS animal_age, a.color AS animal_color, a.sex AS animal_sex
                        FROM questionnaire q
                        INNER JOIN animals a ON q.animal_id = a.id
                        WHERE q.user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there are any adoption requests
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        // Display adoption request details
                        echo '<div class="request">';
                        echo '<img src=' . htmlspecialchars($row['animal_image']) . ' alt="' . htmlspecialchars($row['animal_name']) . '">';
                        echo '<div class="details">';
                        echo '<h2>' . htmlspecialchars($row['animal_name']) . '</h2>';
                        echo '<p><strong>Breed:</strong> ' . htmlspecialchars($row['animal_breed']) . '</p>';
                        echo '<p><strong>Age:</strong> ' . htmlspecialchars($row['animal_age']) . '</p>';
                        echo '<p><strong>Color:</strong> ' . htmlspecialchars($row['animal_color']) . '</p>';
                        echo '<p><strong>Sex:</strong> ' . htmlspecialchars($row['animal_sex']) . '</p>';
                        echo '<p><strong>Status:</strong> ' . htmlspecialchars($row['status']) . '</p>';
                        echo '<form action="delete_request.php" method="post">';
                        echo '<input type="hidden" name="request_id" value="' . htmlspecialchars($row['id']) . '">';
                        echo '<button type="submit" name="delete_request" class="delete-button">Delete Request</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No adoption requests found.</p>';
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>
        </div>
    </main>
    <!-- include footer section here -->
    <?php include 'component/footer.php'; ?>
</body>
</html>