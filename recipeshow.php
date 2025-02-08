<?php
session_start(); // Start the session

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
// Check if the user is logged in and if they are an admin
$isLoggedIn = isset($_SESSION['username']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Check if user is admin
// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the same page
    exit();
}
// Get recipe ID from URL
if (isset($_GET['id'])) {
    $recipe_id = intval($_GET['id']); // Sanitize input
    $sql = "SELECT * FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
    } else {
        $recipe = null; // Recipe not found
    }
} else {
    $recipe = null; // No ID provided
}

$stmt->close();
$conn->close();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title'] ?? 'Recipe Not Found'); ?></title>
    <link rel="stylesheet" href="recipeshowstyles.css">
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
            <button class="sparkle-btn" id="sparkle-btn" title="AI Recipe Detector" onclick="window.location.href='upload.php'">
                <i class="fas fa-robot"></i>
            </button>

            <button class="dark-mode-btn" id="dark-mode-btn">
                <i class="fas fa-moon" id="moon-icon"></i>
            </button>

            <button class="login-btn" id="login-btn" 
                onclick="window.location.href='<?php echo $isLoggedIn ? '?logout=true' : 'login.html'; ?>'">
                <?php echo $isLoggedIn ? 'Logout' : 'Login'; ?>
            </button>

            <?php if ($isLoggedIn && $isAdmin): ?>
                <button class="add-recipe-btn" id="add-recipe-btn" 
                    onclick="window.location.href='add-recipe.php'">
                    Add Recipe
                </button>
            <?php endif; ?>
        </div>
    </header>

    <div class="container" style="display: flex; align-items: flex-start;">
        <?php if ($recipe): ?>
            <div class="image-container" style="margin-right: 20px;">
                <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
            </div>
            <div class="details-container">
                <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
                <?php if ($isLoggedIn && $isAdmin): // Show buttons only for admin ?>
                    <div class="admin-buttons" style="position: relative; top: -30px; right: 10px; z-index: 1000;">
                        <button><a href="editrecipe.php?id=<?php echo $recipe['id']; ?>" class="button edit-button">Edit</a></button>
                        <button><a href="deleterecipe.php?id=<?php echo $recipe['id']; ?>" class="button delete-button">Delete</a></button>
                    </div>
                <?php endif; ?>

                <h2>Ingredients:</h2>
                <div class="ingredients">
                    <ul>
                        <?php foreach (explode(',', $recipe['ingredients']) as $ingredient): ?>
                            <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <h2>Steps:</h2>
                <div class="steps">
                    <p><?php echo nl2br(htmlspecialchars($recipe['steps'])); ?></p>
                </div>
            </div>
        <?php else: ?>
            <h1>Recipe not found!</h1>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>Sujals Cooking Private Limited &copy;</p>
    </div>
    <script src="recipeshowscript.js"></script>
</body>
</html>
