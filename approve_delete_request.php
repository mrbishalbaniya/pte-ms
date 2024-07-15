<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php"); // Redirect to admin login page if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        // Update the status of the corresponding animal to 'approved'
        $sql = "UPDATE animals a
                INNER JOIN questionnaire q ON a.id = q.animal_id
                SET a.status = 'approved'
                WHERE q.id = ?";
    } elseif ($action == 'delete') {
        // Delete the adoption request from the questionnaire table
        $sql = "DELETE FROM questionnaire WHERE id = ?";
    }

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $request_id);

        if ($stmt->execute()) {
            if ($action == 'approve') {
                echo "Request approved successfully.";
            } elseif ($action == 'delete') {
                echo "Request deleted successfully.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
header("Location: user-requests.php"); // Redirect back to the customer requests page
exit();
?>
