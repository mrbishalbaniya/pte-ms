<?php
session_start();
include 'component/conn.php'; // Database connection file

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php"); // Redirect to admin login page if not logged in
    exit();
}

if (isset($_POST['action']) && isset($_POST['request_id'])) {
    $action = $_POST['action'];
    $request_id = $_POST['request_id'];

    if ($action == 'approve') {
        // Code to approve the request
        // For example, updating a status column in the questionnaire table
        $sql = "UPDATE questionnaire SET status = 'approved' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'delete') {
        // Code to delete the request
        $sql = "DELETE FROM questionnaire WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: user-requests.php"); // Redirect back to the customer requests page
exit();
?>