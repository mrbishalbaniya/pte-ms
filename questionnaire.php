<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve animal_id from URL parameter
    $animal_id = $_GET['id'] ?? null;

    // Ensure animal_id is provided and valid
    if (!$animal_id || !is_numeric($animal_id)) {
        // Handle case where animal_id is missing or invalid
        header("Location: error.php"); // Redirect to error page or handle accordingly
        exit();
    }

    // Retrieve form data
    $adopter = $_POST['adopter'] ?? '';
    $primary_caregiver = $_POST['primary_caregiver'] ?? '';
    $children_count = $_POST['children_count'] ?? '';
    $children_ages = $_POST['children_ages'] ?? '';
    $family_allergies = $_POST['family_allergies'] ?? '';
    $residence_type = $_POST['residence_type'] ?? '';
    $day_stay_location = $_POST['day_stay_location'] ?? '';
    $night_sleep_location = $_POST['night_sleep_location'] ?? '';

    // Insert data into questionnaire table
    $insertQuery = "INSERT INTO questionnaire (user_id, animal_id, adopter, primary_caregiver, children_count, children_ages, pet_allergies, residence_type, day_stay_location, night_sleep_location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iissssssss", $_SESSION['user_id'], $animal_id, $adopter, $primary_caregiver, $children_count, $children_ages, $family_allergies, $residence_type, $day_stay_location, $night_sleep_location);
    $stmt->execute();

    // Check if insertion was successful
    if ($stmt->affected_rows > 0) {
        // Redirect to a success page or handle success message
        header("Location: index.php");
        exit();
    }
    $stmt->close();
}
session_abort();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Adoption Questionnaire</title>
    <link rel="stylesheet" href="questionaire.css">
</head>
<body>
    <header>
        <?php include 'component/navbar.php'; ?>
    </header>
    <main>
        <div class="container">
            <div class="top">
                <div class="logo">
                    <img height="200px" src="Assests/images/cat.jpg" alt="">
                </div>
                <div class="title">
                    <h2>Animal Adoption</h2>
                    <h3>Pet Adoption Questionnaire</h3>
                    <p>Click to select the following options</p>
                </div>
            </div>
            <div class="form">
                <form method="POST">
                    <div class="family">
                        <div class="blue-line"></div>
                        <h2>Your Family</h2>
                        <div class="first">
                            <label for="adopter">Who are you planning to adopt this pet for?</label>
                            <input type="text" id="adopter" name="adopter" placeholder="myself | family | others" required>
                            <label for="primary_caregiver">Who will be the primary caregiver for this pet?</label>
                            <input type="text" id="primary_caregiver" name="primary_caregiver" placeholder="Me | My Partner | My child | other" required>
                            <label for="children_count">Number of children at home:</label>
                            <input type="number" id="children_count" name="children_count" placeholder="Number of children" required>
                            <label for="children_ages">List the ages of the children at home (comma separated):</label>
                            <input type="text" id="children_ages" name="children_ages" placeholder="Ages of children" required>
                            <label for="family_allergies">Any pet allergies in the family? (No/Yes)</label>
                            <input type="text" id="family_allergies" name="family_allergies" placeholder="Yes/No" required>
                        </div>
                    </div>
                    <div class="lifestyle">
                        <div class="blue-line"></div>
                        <h2>Your Lifestyle</h2>
                        <div class="first">
                            <label for="residence_type">Type of Residence</label>
                            <input type="text" id="residence_type" name="residence_type" placeholder="house | condominium | studio | mansion | other" required>
                            <label for="day_stay_location">Where will your pet stay during the day?</label>
                            <input type="text" id="day_stay_location" name="day_stay_location" placeholder="indoors | garage | free roaming | yard" required>
                            <label for="night_sleep_location">Where will your pet sleep at night?</label>
                            <input type="text" id="night_sleep_location" name="night_sleep_location" placeholder="indoors | garage | free roaming | yard" required>
                        </div>
                    </div>
                    <div class="btn">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include 'component/footer.php'; ?>
</body>
</html>