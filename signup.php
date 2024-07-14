<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Pet Adoption</title>
    <link rel="stylesheet" href="login.css">
    <script>
        function validateForm() {
            // Get form elements
            var name = document.getElementById("name").value.trim();
            var username = document.getElementById("username").value.trim();
            var password = document.getElementById("password").value.trim();
            var re_password = document.getElementById("re_password").value.trim();
            var email = document.getElementById("email").value.trim();
            var address = document.getElementById("address").value.trim();
            var phone = document.getElementById("phone").value.trim();
            var error = "";

            // Validate name
            if (!/^[a-zA-Z-' ]*$/.test(name)) {
                error += "Only letters and white space allowed in name.\n";
            }

            // Validate username
            if (!/^[a-zA-Z0-9]*$/.test(username)) {
                error += "Only letters and numbers allowed in username.\n";
            }

            // Validate email
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                error += "Invalid email format.\n";
            }

            // Validate phone
            if (!/^[0-9]{10,15}$/.test(phone)) {
                error += "Invalid phone number format.\n";
            }

            // Validate passwords match
            if (password !== re_password) {
                error += "Passwords do not match.\n";
            }

            // Validate password length
            if (password.length < 6) {
                error += "Password must be at least 6 characters long.\n";
            }

            if (error) {
                alert(error);
                return false;
            }

            // AJAX to check if username, email, or phone exists
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "check_existence.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.exists) {
                        alert(response.message);
                        return false;
                    } else {
                        document.getElementById("signup-form").submit();
                    }
                }
            };
            xhr.send("username=" + encodeURIComponent(username) + "&email=" + encodeURIComponent(email) + "&phone=" + encodeURIComponent(phone));

            return false;
        }
    </script>
</head>
<body>
    <header>
    <?php include 'component/navbar.php'; ?>
    </header>
    <main>
        <div class="container">
            <h2>Sign Up</h2>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include 'component/conn.php';

                // Function to sanitize user input
                function sanitize_input($data) {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }

                $name = sanitize_input($_POST['name']);
                $username = sanitize_input($_POST['username']);
                $password = sanitize_input($_POST['password']);
                $re_password = sanitize_input($_POST['re_password']);
                $email = sanitize_input($_POST['email']);
                $address = sanitize_input($_POST['address']);
                $phone = sanitize_input($_POST['phone']);

                // Basic validation
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<p>Invalid email format.</p>";
                } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                    echo "<p>Only letters and white space allowed in name.</p>";
                } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                    echo "<p>Only letters and numbers allowed in username.</p>";
                } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
                    echo "<p>Invalid phone number format.</p>";
                } elseif ($password !== $re_password) {
                    echo "<p>Passwords do not match.</p>";
                } else {
                    // Check if username, email, or phone already exists
                    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
                    $stmt->bind_param("sss", $username, $email, $phone);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        echo "<p>Username, Email, or Phone already taken.</p>";
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        $stmt = $conn->prepare("INSERT INTO users (name, username, password, email, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssss", $name, $username, $hashed_password, $email, $address, $phone);

                        if ($stmt->execute()) {
                            header("Location: login.php");
                            exit();
                        } else {
                            echo "<p>Error: " . $stmt->error . "</p>";
                        }

                        $stmt->close();
                    }

                    $stmt->close();
                }

                $conn->close();
            }
            ?>
            <form method="POST" action="" id="signup-form" onsubmit="return validateForm()">
                <input type="text" id="name" name="name" placeholder="Your Name" required>
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="password" id="re_password" name="re_password" placeholder="Re-Password" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="text" id="address" name="address" placeholder="Address" required>
                <input type="text" id="phone" name="phone" placeholder="Phone" required>
                <div class="btn">
                    <button type="submit">Sign Up</button>
                </div>
            </form>
            <div class="bottom">
                <p>By signing up you agree to our</p>
                <p>Terms & Conditions</p>
            </div>
        </div>
    </main>
    <?php include 'component/footer.php'; ?>
</body>
</html>
