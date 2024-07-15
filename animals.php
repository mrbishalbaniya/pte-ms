<?php
// Include database connection
include 'component/conn.php';

// Fetch animals based on filters
$animal_type = $_GET['type'] ?? 'All';
$age_range = $_GET['age'] ?? 'All';
$gender = $_GET['gender'] ?? 'All';
$color = $_GET['color'] ?? 'All';

$query = "SELECT * FROM animals WHERE status = 'pending'";

// Apply filters
if ($animal_type !== 'All') {
    $query .= " AND type = '$animal_type'";
}
if ($age_range !== 'All') {
    if ($age_range == '0-12 months') {
        $query .= " AND age BETWEEN 0 AND 12";
    } elseif ($age_range == '13-24 months') {
        $query .= " AND age BETWEEN 13 AND 24";
    } elseif ($age_range == '25-36 months') {
        $query .= " AND age BETWEEN 25 AND 36";
    } elseif ($age_range == '36+ months') {
        $query .= " AND age > 36";
    }
}
if ($gender !== 'All') {
    $query .= " AND sex = '$gender'";
}
if ($color !== 'All') {
    $query .= " AND color = '$color'";
}

// Pagination setup
$page = $_GET['page'] ?? 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$query .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

// Total count for pagination
$total_query = "SELECT COUNT(*) as total FROM animals WHERE 1=1";
$total_result = $conn->query($total_query);
$total_count = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_count / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="animals.css">
    <title>Animal - Pet Adoption</title>
</head>

<body>
    <header>
        <?php include 'component/navbar.php'; ?>
    </header>
    <main>
        <aside>
            <div class="logo">
                <img src="Assests/icons/animals.png" alt="Animal Adoption Logo">
                <h1>Animal Adoption</h1>
            </div>
            <form method="GET" class="filters">
                <div class="filter-section">
                    <div class="title" onclick="toggleDropdown('animal')">
                        <h2>Animal</h2>
                        <img src="Assets/icons/animals.png" alt="">
                    </div>
                    <div class="dropdown-menu" id="dropdown-animal">
                        <a href="?type=All">All</a>
                        <a href="?type=dog">Dogs</a>
                        <a href="?type=cat">Cats</a>
                        <a href="?type=Special%20Needs">Pet with Special Needs</a>
                    </div>
                </div>
                <div class="filter-section">
                    <div class="title" onclick="toggleDropdown('age')">
                        <h2>Age</h2>
                        <img src="Assets/icons/age.jpg" alt="">
                    </div>
                    <div class="dropdown-menu" id="dropdown-age">
                        <a href="?age=All">All</a>
                        <a href="?age=0-12%20months">0-12 months</a>
                        <a href="?age=13-24%20months">13-24 months</a>
                        <a href="?age=25-36%20months">25-36 months</a>
                        <a href="?age=36+%20months">36+ months</a>
                    </div>
                </div>
                <div class="filter-section">
                    <div class="title" onclick="toggleDropdown('gender')">
                        <h2>Gender</h2>
                        <img src="Assets/icons/gender.png" alt="">
                    </div>
                    <div class="dropdown-menu" id="dropdown-gender">
                        <a href="?gender=All">All</a>
                        <a href="?gender=Male">Male</a>
                        <a href="?gender=Female">Female</a>
                    </div>
                </div>
                <div class="filter-section">
                    <div class="title" onclick="toggleDropdown('color')">
                        <h2>Coat Color</h2>
                        <img src="Assets/icons/coat.png" alt="">
                    </div>
                    <div class="dropdown-menu" id="dropdown-color">
                        <a href="?color=All">All</a>
                        <a href="?color=black">Black</a>
                        <a href="?color=brown">Brown</a>
                        <a href="?color=white">White</a>
                        <a href="?color=red">Red</a>
                    </div>
                </div>
                <button type="submit">Search</button>
            </form>
        </aside>
        <section class="content">
            <h2>View Animals - All</h2>
            <div class="animal-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="animal">
                        <a href="animal.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p><?php echo htmlspecialchars(implode(' ', array_slice(explode(' ', $row['description']), 0, 11))); ?>...</p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </section>
    </main>
    <?php include 'component/footer.php'; ?>

    <script>
        function toggleDropdown(section) {
            var dropdown = document.getElementById('dropdown-' + section);
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
