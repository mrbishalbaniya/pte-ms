<?php
session_start();
include 'component/conn.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// Pagination settings
$limit = 6; // Number of animals per page...
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch animals from the database in descending order of ID
$sql = "SELECT * FROM animals ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$total_sql = "SELECT COUNT(*) FROM animals";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_array()[0];
$total_pages = ceil($total_rows / $limit);

function limitWords($text, $limit) {
    $words = explode(' ', $text);
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
    return $text;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Animals</title>
    <link rel="stylesheet" href="admin-dashboard.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard - Manage Animals</h1>
        <div class="logout">
            <a href="component/logout.php">
                <button>Log Out</button>
            </a>
            <img class="search" src="Assests/icons/search.png" alt="">
        </div>
    </header>
    <div class="sidebar">
        <div class="logo">
            
        </div>
        <nav>
            <ul>
                <li><a href="admin-dashboard.php">Animal - All</a></li>
                <li><a href="add-animal.php">Add Animal</a></li>
                <li><a href="user-requests.php">Customer Request</a></li>
                <li><a href="user-messages.php">Customer Messages</a></li>
            </ul>
        </nav>
    </div>
    <main>
        <div class="animals">
            <?php while ($row = $result->fetch_assoc()): ?>
                <a href="admin-animal.php?id=<?php echo $row['id']; ?>">
                <div class="animal">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Animal Image">
                <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                <p><?php echo htmlspecialchars(limitWords($row['description'], 8)); ?></p>
                <div class="actions">
                    <a href="edit-animal.php?id=<?php echo $row['id']; ?>"><button class="edit">EDIT</button></a>
                    <a href="delete-animal.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this animal?');"><button class="delete">DELETE</button></a>
                </div>
            </div>
                </a>
            <?php endwhile; ?>
        </div>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
