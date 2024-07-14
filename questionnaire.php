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
    <script>
        function validateChildrenAges() {
            const childrenAges = document.getElementById('children_ages').value;
            const agePattern = /^(\d+)(,\d+)*$/;

            if (!agePattern.test(childrenAges)) {
                alert('Please enter valid ages separated by commas (e.g., 3,5,7). Only non-negative numbers and a single comma between numbers are allowed.');
                return false;
            }

            return true;
        }
    </script>
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
                            <select id="adopter" name="adopter" required>
                                <option value="" disabled selected>Choose an option</option>
                                <option value="myself">Myself</option>
                                <option value="family">Family</option>
                                <option value="others">Others</option>
                            </select>
                            <label for="primary_caregiver">Who will be the primary caregiver for this pet?</label>
                            <select id="primary_caregiver" name="primary_caregiver" required>
                                <option value="" disabled selected>Choose an option</option>
                                <option value="Me">Me</option>
                                <option value="My Partner">My Partner</option>
                                <option value="My child">My child</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="children_count">Number of children at home:</label>
                            <select id="children_count" name="children_count" required>
                                <option value="" disabled selected>Choose an option</option>
                                <?php for ($i = 0; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <label for="children_ages">List the ages of the children at home (comma separated):</label>
                            <input type="text" id="children_ages" name="children_ages" placeholder="Ages of children" required>
                            <label for="family_allergies">Any pet allergies in the family? (No/Yes)</label>
                            <select id="family_allergies" name="family_allergies" required>
                                <option value="" disabled selected>Choose an option</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="lifestyle">
                        <div class="blue-line"></div>
                        <h2>Your Lifestyle</h2>
                        <div class="first">
                            <label for="residence_type">Type of Residence</label>
                            <select id="residence_type" name="residence_type" required>
                                <option value="" disabled selected>Choose an option</option>
                                <option value="house">House</option>
                                <option value="condominium">Condominium</option>
                                <option value="studio">Studio</option>
                                <option value="mansion">Mansion</option>
                                <option value="other">Other</option>
                            </select>
                            <label for="day_stay_location">Where will your pet stay during the day?</label>
                            <select id="day_stay_location" name="day_stay_location" required>
                                <option value="" disabled selected>Choose an option</option>
                                <option value="indoors">Indoors</option>
                                <option value="garage">Garage</option>
                                <option value="free roaming">Free roaming</option>
                                <option value="yard">Yard</option>
                            </select>
                            <label for="night_sleep_location">Where will your pet sleep at night?</label>
                            <select id="night_sleep_location" name="night_sleep_location" required>
                                <option value="" disabled selected>Choose an option</option>
                                <option value="indoors">Indoors</option>
                                <option value="garage">Garage</option>
                                <option value="free roaming">Free roaming</option>
                                <option value="yard">Yard</option>
                            </select>
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