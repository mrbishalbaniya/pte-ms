<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'component/conn.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $login_error = "Incorrect username or password.";
            }
        } else {
            $login_error = "Username not found.";
        }

        $stmt->close();
    } else {
        $login_error = "Database error: Unable to prepare statement.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pet Adoption</title>
    <link rel="stylesheet" href="login.css">
    <script>
        function validateLoginForm() {
            var username = document.getElementById("username").value.trim();
            var password = document.getElementById("password").value.trim();
            var error = "";

            // Validate username
            if (!username) {
                error += "Username is required.\n";
            }

            // Validate password
            if (!password) {
                error += "Password is required.\n";
            }

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
        <nav>
            <ul>
                <a href="index.php">
                    <li>Home</li>
                </a>
                <a href="animals.php">
                    <li>Animal</li>
                </a>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <a href="request.php">
                        <li>My Request</li>
                    </a>
                    <a href="component/logout.php">
                        <li>Logout</li>
                    </a>
                <?php else : ?>
                    <a href="login.php">
                        <li>Login</li>
                    </a>
                <?php endif; ?>
                <li><img class="search" src="Assests/icons/search.png" alt=""></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container">
            <div class="img">
                <img height="200px" src="Assests/images/cat.jpg" alt="">
            </div>
            <?php
            if (isset($login_error)) {
                echo "<p style='color:red;'>$login_error</p>";
            }
            ?>
            <form method="POST" action="" onsubmit="return validateLoginForm()">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <div class="btn">
                    <button type="submit">Sign In</button>
                </div>
            </form>
            <div class="bottom">
                <p>Forgot your password?</p>
                <a href="signup.php">
                    <p>Do not have an account?</p>
                </a>
            </div>
        </div>
    </main>
    <?php include 'component/footer.php'; ?>
</body>

</html>
