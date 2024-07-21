<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission and sanitize inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $type = htmlspecialchars(trim($_POST['type']));
    $breed = htmlspecialchars(trim($_POST['breed']));
    $sex = htmlspecialchars(trim($_POST['sex']));
    $age = htmlspecialchars(trim($_POST['age']));
    $color = htmlspecialchars(trim($_POST['color']));
    $size = htmlspecialchars(trim($_POST['size']));
    $description = htmlspecialchars(trim($_POST['description']));

    // Validate inputs
    if (empty($name) || empty($type) || empty($breed) || empty($sex) || empty($age) || empty($color) || empty($size) || empty($description)) {
        $error = 'Please fill in all the required fields.';
    } else {
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
            $error = 'Error: ' . $stmt->error;
            error_log("Error inserting into animals table: " . $stmt->error);
        }

        $stmt->close();
    }

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
    <script>
        function validateForm() {
            var name = document.getElementById("name").value.trim();
            var type = document.getElementById("type").value;
            var breed = document.getElementById("breed").value.trim();
            var sex = document.getElementById("sex").value;
            var age = document.getElementById("age").value.trim();
            var color = document.getElementById("color").value.trim();
            var size = document.getElementById("size").value;
            var description = document.getElementById("description").value.trim();
            var image = document.getElementById("image").value;

            var error = "";

            if (name === "") error += "Name is required.\n";
            if (type === "Select Type") error += "Type is required.\n";
            if (breed === "") error += "Breed is required.\n";
            if (sex === "Select Gender") error += "Sex is required.\n";
            if (age === "") error += "Age is required.\n";
            if (color === "") error += "Color is required.\n";
            if (size === "Select Size") error += "Size is required.\n";
            if (description === "") error += "Description is required.\n";
            if (image === "") error += "Image is required.\n";

            if (error) {
                alert(error);
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <header>
        <h1>Admin Dashboard - Add Animal</h1>
        <div class="logout">
            <a href="component/logout.php"><button>Log Out</button></a>
            <!-- <img class="search" src="Assests/icons/search.png" alt="Search Icon"> -->
        </div>
    </header>
    <div class="sidebar">
        <div class="logo">
            <!-- Add your logo here -->
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
                <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="image-upload">
                        <img src="Assests/icons/upload.png" alt="Upload Image">
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
                            <option value="">Select Type</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Special Needs">Special Needs</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="breed">Breed</label>
                        <input type="text" id="breed" name="breed" required>
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <select id="sex" name="sex" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
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
                            <option value="">Select Size</option>
                            <option value="Small">Small</option>
                            <option value="Medium">Medium</option>
                            <option value="Large">Large</option>
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
