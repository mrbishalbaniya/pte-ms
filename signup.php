<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Pet Adoption</title>
    <link rel="stylesheet" href="login.css">
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
                
                $name = $_POST['name'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $re_password = $_POST['re_password'];
                $email = $_POST['email'];

                if ($password == $re_password) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $conn->prepare("INSERT INTO users (name, username, password, email) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $name, $username, $hashed_password, $email);

                    if ($stmt->execute()) {
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "<p>Error: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                } else {
                    echo "<p>Passwords do not match.</p>";
                }

                $conn->close();
            }
            ?>
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="re_password" placeholder="Re-Password" required>
                <input type="email" name="email" placeholder="Email" required>
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