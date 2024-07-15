<?php
include 'component/conn.php';

$response = array('exists' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $response['exists'] = true;
        $response['message'] = 'Username already taken.';
    }
    $stmt->close();

    // Check if email exists
    if (!$response['exists']) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $response['exists'] = true;
            $response['message'] = 'Email already taken.';
        }
        $stmt->close();
    }

    // Check if phone exists
    if (!$response['exists']) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $response['exists'] = true;
            $response['message'] = 'Phone already taken.';
        }
        $stmt->close();
    }
}

echo json_encode($response);
$conn->close();
?>
