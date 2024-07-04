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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($animal['name']); ?> - Admin | Pet Adoption</title>
    <link rel="stylesheet" href="admin-animal.css"> <!-- Replace with your admin dashboard stylesheet -->
</head>
<body>
    <header>
        <h1>Admin Dashboard - Animal Details</h1>
        <div class="logout">
            <a href="component/logout.php">
                <button>Log Out</button>
            </a>
            <img class="search" src="Assets/icons/search.png" alt="">
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
            </ul>
        </nav>
    </div>
    <main>
    <div class="container">
            <div class="images">
                <div class="gallery">
                        <img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="Gallery Image 1">
                    <?php if (!empty($animal['gallery_one'])): ?>
                        <img src="<?php echo htmlspecialchars($animal['gallery_one']); ?>" alt="Gallery Image 1">
                    <?php endif; ?>
                    <?php if (!empty($animal['gallery_two'])): ?>
                        <img src="<?php echo htmlspecialchars($animal['gallery_two']); ?>" alt="Gallery Image 2">
                    <?php endif; ?>
                    <?php if (!empty($animal['gallery_three'])): ?>
                        <img src="<?php echo htmlspecialchars($animal['gallery_three']); ?>" alt="Gallery Image 3">
                    <?php endif; ?>
                    <?php if (!empty($animal['gallery_four'])): ?>
                        <img src="<?php echo htmlspecialchars($animal['gallery_four']); ?>" alt="Gallery Image 4">
                    <?php endif; ?>
                </div>
                <div class="main-image">
                    <img id="mainImage" height="800px" width="1000px" src="<?php echo htmlspecialchars($animal['image']); ?>" alt="Main Image">
                </div>
            </div>
            <div class="info">
                <div class="title">
                    <div class="name">
                        <h1><?php echo htmlspecialchars($animal['name']); ?></h1>
                        <?php if ($animal['sex'] === 'Male'): ?>
                            <img src="Assests/icons/male.png" alt="Male icon">
                        <?php elseif ($animal['sex'] === 'Female'): ?>
                            <img src="Assests/icons/female.png" alt="Female icon">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="details">
                    <div class="location detail">
                        <h3>Breed:</h3>
                        <p><?php echo htmlspecialchars($animal['breed']); ?></p>
                    </div>
                    <div class="age detail">
                        <h3>Approximate age:</h3>
                        <p><?php echo htmlspecialchars($animal['age']); ?> months</p>
                    </div>
                </div>
                <div class="description">
                    <p><?php echo htmlspecialchars($animal['description']); ?></p>
                </div>
            </div>  
        </div>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const galleryImages = document.querySelectorAll(".gallery img");
            const mainImage = document.getElementById("mainImage");

            function changeMainImage(src, selectedImg) {
                mainImage.src = src;
                galleryImages.forEach((img) => {
                    img.classList.remove("selected");
                });
                selectedImg.classList.add("selected");
            }

            if (galleryImages.length > 0) {
                changeMainImage(galleryImages[0].src, galleryImages[0]);
            }

            galleryImages.forEach((img) => {
                img.addEventListener("click", function() {
                    changeMainImage(img.src, img);
                });
            });
        });
    </script>
    </main>
</body>
</html>
