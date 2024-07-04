<?php
include 'component/conn.php'; // Include database connection file

// Initialize variables
$name = $username = $password = $re_password = $email = "";
$signup_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $re_password = mysqli_real_escape_string($conn, $_POST['re_password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if passwords match
    if ($password !== $re_password) {
        $signup_error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute SQL statement to insert admin data
        $stmt = $conn->prepare("INSERT INTO admins (name, username, password, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $username, $hashed_password, $email);

        if ($stmt->execute()) {
            // Redirect to a success page or login page
            header("Location: admin-login.php");
            exit();
        } else {
            $signup_error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Pet Adoption</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <main>
        <div class="container">
            <h2>Admin - Sign Up</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="text" name="name" placeholder="Your Name" value="<?php echo htmlspecialchars($name); ?>" required>
                <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="re_password" placeholder="Re-Password" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="btn">
                    <button type="submit" name="signup">Sign Up</button>
                </div>
                <?php if (!empty($signup_error)): ?>
                    <p style="color: red;"><?php echo $signup_error; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </main>
    <?php include 'component/footer.php'; ?>
</body>
</html>
