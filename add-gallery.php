<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch animal details based on ID
$animal_id = $_GET['id'] ?? 1; // Replace with actual animal ID
$query = "SELECT * FROM animals WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $animal = $result->fetch_assoc();
} else {
    // Handle case where animal with given ID is not found
    // Redirect or show an error message
    header('Location: admin-dashboard.php'); // Redirect to admin dashboard or appropriate page
    exit();
}

$stmt->close();

// Handle form submission for adding more gallery images
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = 'uploads/'; // Directory where uploaded images will be stored

    // Validate and process each file upload
    $uploads = [];
    foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['gallery_images']['name'][$key];
        $file_tmp = $_FILES['gallery_images']['tmp_name'][$key];

        // Check if file is not empty
        if (!empty($file_name) && is_uploaded_file($file_tmp)) {
            $target_file = $uploadDir . basename($file_name);

            // Move uploaded file to target directory
            if (move_uploaded_file($file_tmp, $target_file)) {
                $uploads[] = $target_file;
            }
        }
    }

    // Update animal record in the database with new gallery images
    if (!empty($uploads)) {
        // Update gallery images in the database
        $updateQuery = "UPDATE animals SET gallery_one = COALESCE(NULLIF(?, ''), gallery_one), 
                                          gallery_two = COALESCE(NULLIF(?, ''), gallery_two), 
                                          gallery_three = COALESCE(NULLIF(?, ''), gallery_three), 
                                          gallery_four = COALESCE(NULLIF(?, ''), gallery_four) 
                        WHERE id = ?";
        
        // Bind parameters
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssi", $uploads[0], $uploads[1], $uploads[2], $uploads[3], $animal_id);
        $stmt->execute();
        $stmt->close();

        // Redirect back to animal details page with updated images
        header("Location: admin-animal.php?id=$animal_id");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add More Images - Admin | Pet Adoption</title>
    <link rel="stylesheet" href="admin-animal.css"> <!-- Replace with your admin dashboard stylesheet -->
</head>
<body>
    <header>
        <h1>Add More Images</h1>
    </header>
    <main>
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$animal_id"; ?>" method="post" enctype="multipart/form-data">
                <?php if (empty($animal['gallery_one'])): ?>
                    <label for="gallery_image1">Gallery Image 1:</label><br>
                    <input type="file" id="gallery_image1" name="gallery_images[]" accept="image/*"><br>
                <?php endif; ?>
                <?php if (empty($animal['gallery_two'])): ?>
                    <label for="gallery_image2">Gallery Image 2:</label><br>
                    <input type="file" id="gallery_image2" name="gallery_images[]" accept="image/*"><br>
                <?php endif; ?>
                <?php if (empty($animal['gallery_three'])): ?>
                    <label for="gallery_image3">Gallery Image 3:</label><br>
                    <input type="file" id="gallery_image3" name="gallery_images[]" accept="image/*"><br>
                <?php endif; ?>
                <?php if (empty($animal['gallery_four'])): ?>
                    <label for="gallery_image4">Gallery Image 4:</label><br>
                    <input type="file" id="gallery_image4" name="gallery_images[]" accept="image/*"><br>
                <?php endif; ?>
                <button type="submit">Upload</button>
            </form>
        </div>
    </main>
</body>
</html>
