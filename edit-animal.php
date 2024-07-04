<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// Get animal ID from query parameter
$animal_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $name = $_POST['name'];
    $type = $_POST['type'];
    $breed = $_POST['breed'];
    $sex = $_POST['sex'];
    $age = $_POST['age'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $description = $_POST['description'];

    // Handle image upload
    $image_folder = $_POST['current_image']; // Default to current image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_folder);
    }

    // Update animal data in the database
    $stmt = $conn->prepare("UPDATE animals SET name=?, type=?, breed=?, sex=?, age=?, color=?, size=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $name, $type, $breed, $sex, $age, $color, $size, $description, $image_folder, $animal_id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch animal details
$stmt = $conn->prepare("SELECT * FROM animals WHERE id=?");
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit Animal</title>
    <link rel="stylesheet" href="edit-animal.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard - Edit Animal</h1>
        <div class="logout">
            <a href="component/logout.php"><button>Log Out</button></a>
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
            </ul>
        </nav>
    </div>
    <main>
    <div class="container">
        <div id="dogs" class="tab-content active">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="image-upload">
                    <img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                    <label for="image" class="custom-file-upload">Upload Image</label>
                    <input type="file" id="image" name="image" value=""<?php echo htmlspecialchars($animal['image']); ?>">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($animal['image']); ?>">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($animal['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type" required>
                        <option value="Dog" <?php echo $animal['type'] == 'Dog' ? 'selected' : ''; ?>>Dog</option>
                        <option value="Cat" <?php echo $animal['type'] == 'Cat' ? 'selected' : ''; ?>>Cat</option>
                        <option value="Special Needs" <?php echo $animal['type'] == 'Special Needs' ? 'selected' : ''; ?>>Special Needs</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="breed">Breed</label>
                    <input type="text" id="breed" name="breed" value="<?php echo htmlspecialchars($animal['breed']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sex">Sex</label>
                    <select id="sex" name="sex" required>
                        <option value="Male" <?php echo $animal['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $animal['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($animal['age']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($animal['color']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="size">Size</label>
                    <select id="size" name="size" required>
                        <option value="Small" <?php echo $animal['size'] == 'Small' ? 'selected' : ''; ?>>Small</option>
                        <option value="Medium" <?php echo $animal['size'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="Large" <?php echo $animal['size'] == 'Large' ? 'selected' : ''; ?>>Large</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($animal['description']); ?></textarea>
                </div>
                <button class="submit" type="submit">SAVE</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>