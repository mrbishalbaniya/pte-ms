<?php
session_start(); // Start the session

include 'component/conn.php'; // Include database connection file

// Initialize variables
$username = $password = "";
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check admin credentials
    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            // Admin found, verify password
            $admin = mysqli_fetch_assoc($result);
            if (password_verify($password, $admin['password'])) {
                // Password is correct, set session variables
                $_SESSION['admin_id'] = $admin['id']; // Replace with your admin_id field name
                $_SESSION['username'] = $admin['username']; // Replace with your username field name

                // Redirect to admin dashboard or any other admin page
                header("Location: admin-dashboard.php");
                exit();
            } else {
                $login_error = "Incorrect password.";
            }
        } else {
            $login_error = "Admin not found.";
        }
    } else {
        $login_error = "Database query error: " . mysqli_error($conn);
    }

    // Close result set
    mysqli_free_result($result);
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pet Adoption</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <main>
        <div class="container">
            <div class="img">
                <h1>Admin Login</h1>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="btn">
                    <button type="submit">Sign In</button>
                </div>
                <?php if (!empty($login_error)): ?>
                    <p style="color: red;"><?php echo $login_error; ?></p>
                <?php endif; ?>
            </form>
            <div class="bottom">
                <p>Forgot your password?</p>
            </div>
        </div>
    </main>

</body>
</html>