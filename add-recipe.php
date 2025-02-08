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
    <link rel="stylesheet" href="add-recipe-styles.css">
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
            <!-- AI Recipe Detector Button with Robot Icon -->
            <button class="sparkle-btn" id="sparkle-btn" title="AI Recipe Detector" onclick="window.location.href='upload.php'">
                <i class="fas fa-robot"></i> <!-- Robot Icon -->
            </button>

            <!-- Dark Mode Toggle Button with Moon Icon -->
            <button class="dark-mode-btn" id="dark-mode-btn" title="Toggle Dark Mode">
                <i class="fas fa-moon" id="moon-icon"></i> <!-- Moon Icon -->
            </button>

            <!-- Login/Logout Button -->
            <button class="login-btn" id="login-btn" 
                onclick="window.location.href='<?php echo $isLoggedIn ? '?logout=true' : 'login.html'; ?>'">
                <?php echo $isLoggedIn ? 'Logout' : 'Login'; ?>
            </button>

            <!-- Add Recipe Button for Admin Users -->
            <?php if ($isLoggedIn && $isAdmin): ?>
                <button class="add-recipe-btn" id="add-recipe-btn" 
                    onclick="window.location.href='add-recipe.php'">
                    Add Recipe
                </button>
            <?php endif; ?>
        </div>
    </header>

<div class="container">
    <h1>Add Your Recipe</h1>

    <form action="submit.php" method="POST" class="recipe-form" enctype="multipart/form-data">
        <label for="recipe-name">Recipe Name:</label>
        <input type="text" id="recipe-name" name="recipe-name" required>

        <label for="ingredients">Ingredients:</label>
        <textarea id="ingredients" name="ingredients" rows="5" required></textarea>

        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" rows="5" required></textarea>

        <label for="image">Recipe Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <label for="meal-type">Meal Type:</label>
        <div id="meal-type">
            <input type="radio" id="breakfast" name="meal-type" value="breakfast" required>
            <label for="breakfast">Breakfast</label>

            <input type="radio" id="lunch" name="meal-type" value="lunch">
            <label for="lunch">Lunch</label>

            <input type="radio" id="dinner" name="meal-type" value="dinner">
            <label for="dinner">Dinner</label>

            <input type="radio" id="snack" name="meal-type" value="snack">
            <label for="snack">Snack</label>

            <input type="radio" id="teatime" name="meal-type" value="teatime">
            <label for="teatime">Teatime</label>
        </div>

        <button type="submit" class="add-recipe-btn">Submit Recipe</button>
    </form>
</div>

<div class="footer">
        <p> Sujals Cooking Private Limited &copy;</p>
    </div>


<script src="recipeshowscript.js"></script>

</body>
</html>
