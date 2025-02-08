<?php
session_start();

// Database credentials
$servername = "localhost";
$username = "root";  // Adjust your MySQL username
$password = "";      // Adjust your MySQL password
$dbname = "webtech"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for filtering
$mealType = isset($_GET['meal']) ? $_GET['meal'] : '';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM recipes WHERE 1=1";

// Add meal type filter if set
if (!empty($mealType)) {
    $sql .= " AND meal_type = ?";
}

// Add search filter if set
if (!empty($searchQuery)) {
    $sql .= " AND title LIKE ?";
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters based on the filters
if (!empty($mealType) && !empty($searchQuery)) {
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $mealType, $searchParam);
} elseif (!empty($mealType)) {
    $stmt->bind_param("s", $mealType);
} elseif (!empty($searchQuery)) {
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchParam);
}

// Execute the statement and fetch results
$stmt->execute();
$result = $stmt->get_result();

// Store recipes for display
$recipes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Recipe Sharing Website</title>
    <link rel="stylesheet" href="recipestyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo-light.png" alt="Logo" id="logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="recipes.php" class="nav-link">Recipes</a></li>
            </ul>
        </nav>
        <div class="actions">
            <button class="dark-mode-btn" id="dark-mode-btn">
                <i class="fas fa-moon" id="moon-icon"></i>
            </button>
        </div>
    </header>

    <div class="banner">
        <div class="search-container">
            <form action="recipes.php" method="GET">
                <input type="search" id="search-bar" name="search" placeholder="Search for a recipe..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="meal-btns">
        <button class="meal-btn" onclick="filterRecipes('breakfast')">Breakfast</button>
        <button class="meal-btn" onclick="filterRecipes('lunch')">Lunch</button>
        <button class="meal-btn" onclick="filterRecipes('dinner')">Dinner</button>
        <button class="meal-btn" onclick="filterRecipes('snack')">Snack</button>
        <button class="meal-btn" onclick="filterRecipes('teatime')">Teatime</button>
        <div class="tab-indicator"></div>
    </div>

    <!-- Image container for recipes -->
    <div id="image-container">
        <?php if (!empty($recipes)): ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
                    <p><?php echo htmlspecialchars($recipe['ingredients']); ?></p>
                    <p><?php echo htmlspecialchars($recipe['steps']); ?></p>
                    <p><strong>Meal Type:</strong> <?php echo htmlspecialchars($recipe['meal_type']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recipes found. Please try a different search or meal type.</p>
        <?php endif; ?>
    </div>

    <script src="recipescript.js"></script>
    <script>
        // Function to filter recipes based on meal type
        function filterRecipes(meal) {
            window.location.href = 'recipes.php?meal=' + meal;
        }
    </script>
</body>
</html>
