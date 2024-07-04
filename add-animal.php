<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $name = $_POST['name'];
    $type = $_POST['type'];
    $breed = $_POST['breed'];
    $sex = $_POST['sex'];
    $age = $_POST['age']; // This will now handle any string input
    $color = $_POST['color'];
    $size = $_POST['size'];
    $description = $_POST['description'];

    // Handle image upload
    $image_folder = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $image_name;

        move_uploaded_file($image_tmp, $image_folder);
    }

    // Insert animal data into the database
    $stmt = $conn->prepare("INSERT INTO animals (name, type, breed, sex, age, color, size, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $name, $type, $breed, $sex, $age, $color, $size, $description, $image_folder);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
        error_log("Error inserting into animals table: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Animal</title>
    <link rel="stylesheet" href="edit-animal.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard - Add Animal</h1>
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    <div class="image-upload">
                        <img src="Assests/icons/upload.png" alt="upload-image">
                        <label for="image" class="custom-file-upload">Upload Image</label>
                        <input type="file" id="image" name="image" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" name="type" required>
                            <option>Select Type</option>
                            <option>Dog</option>
                            <option>Cat</option>
                            <option>Special Needs</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="breed">Breed</label>
                        <input type="text" id="breed" name="breed" required>
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <select id="sex" name="sex" required>
                            <option>Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="text" id="age" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" id="color" name="color" required>
                    </div>
                    <div class="form-group">
                        <label for="size">Size</label>
                        <select id="size" name="size" required>
                            <option>Select Size</option>
                            <option>Small</option>
                            <option>Medium</option>
                            <option>Large</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>
                    <button class="submit" type="submit">ADD</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
