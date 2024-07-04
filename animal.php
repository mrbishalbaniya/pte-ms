<?php
// Include database connection
include 'component/conn.php';

// Initialize variables
$animal = [];

// Fetch animal details based on ID
$animal_id = $_GET['id'] ?? 1; // Replace with actual animal ID
$query = "SELECT * FROM animals WHERE id = $animal_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $animal = $result->fetch_assoc();
} else {
    // Handle case where animal with given ID is not found
    // Redirect or show an error message
    header('Location: index.php'); // Redirect to homepage or appropriate page
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($animal['name']); ?> - Pet Adoption</title>
    <link rel="stylesheet" href="animal.css">
</head>
<body>
    <header>
        <?php include 'component/navbar.php'; ?>
    </header>
    <main>
        <div class="container">
            <div class="images">
                <div class="gallery">
                    <?php if (!empty($animal['image'])): ?>
                        <img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="Gallery Image 1">
                    <?php endif; ?>
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
                    <div class="btn">
                    <a href="questionnaire.php?id=<?php echo $animal_id; ?>">
                        <button>Adopt Me!</button>
                    </a>
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
    </main>
    <?php include 'component/footer.php'; ?>
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
</body>
</html>