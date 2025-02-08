<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Check if user is admin

// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the same page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Recipe Sharing Website</title>
    <link rel="stylesheet" href="indexstyles.css">
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

    <div class="banner">
        <img src="cook.io/assets/images/hero-banner-large.jpg" alt="Hero Banner">
        <div class="search-container">
            <h2 class="search-question">Your desired dish?</h2>
            <form id="search-form" action="recipes.php" method="GET"> <!-- Updated action to recipes.php -->
                <input type="search" id="search-bar" name="query" placeholder="Search for a recipe..." required> <!-- Added name attribute -->
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <p class="search-comment">Search any recipe e.g: burger, pizza, sandwich, toast.</p>
        </div>
    </div>

    <div class="meal-btns">
        <button class="meal-btn" data-tab="breakfast">Breakfast</button>
        <button class="meal-btn" data-tab="lunch">Lunch</button>
        <button class="meal-btn" data-tab="dinner">Dinner</button>
        <button class="meal-btn" data-tab="snack">Snack</button>
        <button class="meal-btn" data-tab="teatime">Teatime</button>
        <div class="tab-indicator"></div>
    </div>

    <div id="image-container" class="hidden"></div>


    <div class="footer">
        <p> Sujals Cooking Private Limited &copy;</p>
    </div>

    <script src="script2.js"></script>
</body>
</html>
