<?php
session_start();
include 'component/conn.php'; // Database connection file

if (isset($_POST['delete_request'])) {
    $request_id = $_POST['request_id'];

    // Prepare SQL to delete from questionnaire table
    $delete_sql = "DELETE FROM questionnaire WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    // Check if deletion was successful
    if ($stmt->affected_rows > 0) {
        // Deletion successful
        header("Location: index.php"); // Redirect to adoption requests page
        exit();
    } else {
        // Deletion failed
        echo "Error deleting request.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php"); // Redirect to adoption requests page if accessed directly
    exit();
}
?>