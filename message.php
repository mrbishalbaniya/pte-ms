<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $email = $message = "";
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (empty($_POST["name"])) {
        $errors[] = "Name is required.";
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        $errors[] = "Email is required.";
    } else {
        $email = htmlspecialchars($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    if (empty($_POST["message"])) {
        $errors[] = "Message is required.";
    } else {
        $message = htmlspecialchars($_POST["message"]);
    }

    // If no errors, insert message into database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO messages (user_id, name, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $name, $email, $message);
        if ($stmt->execute()) {
            header("Location: index.php");
        } else {
            $errors[] = "Error sending message.";
        }
        $stmt->close();
    }
}
session_abort();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="message.css">
</head>
<body>
    <header>
        <!-- include nav section here -->
        <?php include 'component/navbar.php'; ?>
    </header>
    <main>
        <div class="container">
            <div class="message">
                <p>We would love to hear from you!</p>
                <p>Please use the Contact Form to send us a message.</p>
            </div>
            <?php if (!empty($errors)) : ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (isset($success)) : ?>
                <div class="success">
                    <p><?php echo $success; ?></p>
                </div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="7" cols="50"><?php echo htmlspecialchars($message); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="email">Your Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="btn">
                    <button type="submit">Send</button>
                </div>
            </form>
        </div>
    </main>
    <?php include 'component/footer.php'; ?>
</body>
</html>