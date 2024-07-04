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

if ($animal_id) {
    // Delete animal from database
    $stmt = $conn->prepare("DELETE FROM animals WHERE id=?");
    $stmt->bind_param("i", $animal_id);

    if ($stmt->execute()) {
        header("Location: admin-dashboard.php?message=Animal deleted successfully");
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
