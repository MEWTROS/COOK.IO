<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Check if user is admin

// Check if the user is logged in and if they have admin privileges
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You do not have permission to edit recipes."; // Set error message
    header("Location: index.php"); // Redirect to homepage or an error page
    exit; // Stop further execution
}

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

// Check if a recipe ID is passed
if (isset($_GET['id'])) {
    $recipe_id = intval($_GET['id']); // Sanitize input

    // Fetch the recipe details from the database
    $sql = "SELECT * FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the recipe exists
    if ($result->num_rows === 1) {
        $recipe = $result->fetch_assoc(); // Fetch the recipe details
    } else {
        $_SESSION['error'] = "No recipe found with the given ID."; // Set error message
        header("Location: recipes.php"); // Redirect to recipe list
        exit; // Stop further execution
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "No recipe ID provided."; // Set error message
    header("Location: recipes.php"); // Redirect to recipe list
    exit; // Stop further execution
}

// Handle form submission for editing the recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $meal_type = $_POST['meal-type']; // Get the selected meal type

    // Prepare update SQL statement
    $sql = "UPDATE recipes SET title = ?, ingredients = ?, steps = ?, meal_type = ? WHERE id = ?"; // Include meal_type in the update statement
    $stmt = $conn->prepare($sql);

    // Bind parameters: "ssssi" means 4 strings and 1 integer
    $stmt->bind_param("ssssi", $title, $ingredients, $steps, $meal_type, $recipe_id); // Corrected binding

    // Try to execute and check if the update was successful
    if ($stmt->execute()) {
        $_SESSION['message'] = "Recipe updated successfully!"; // Success message
        header("Location: recipeshow.php?id=" . $recipe_id); // Redirect to the updated recipe page
        exit; // Stop further execution
    } else {
        $_SESSION['error'] = "Error executing query: " . $stmt->error; // Set error message
        header("Location: recipeshow.php?id=" . $recipe_id); // Redirect back to the recipe page
        exit; // Stop further execution
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe - Food Recipe Sharing Website</title>
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
        <h1>Edit Your Recipe</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="post" class="recipe-form">
            <label for="recipe-name">Recipe Title:</label>
            <input type="text" id="recipe-name" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>

            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>

            <label for="instructions">Steps:</label>
            <textarea id="instructions" name="steps" required><?php echo htmlspecialchars($recipe['steps']); ?></textarea>

            <label for="meal-type">Meal Type:</label>
            <div id="meal-type">
                <input type="radio" id="breakfast" name="meal-type" value="breakfast" 
                       <?php echo $recipe['meal_type'] === 'breakfast' ? 'checked' : ''; ?> required>
                <label for="breakfast">Breakfast</label>

                <input type="radio" id="lunch" name="meal-type" value="lunch" 
                       <?php echo $recipe['meal_type'] === 'lunch' ? 'checked' : ''; ?>>
                <label for="lunch">Lunch</label>

                <input type="radio" id="dinner" name="meal-type" value="dinner" 
                       <?php echo $recipe['meal_type'] === 'dinner' ? 'checked' : ''; ?>>
                <label for="dinner">Dinner</label>

                <input type="radio" id="snack" name="meal-type" value="snack" 
                       <?php echo $recipe['meal_type'] === 'snack' ? 'checked' : ''; ?>>
                <label for="snack">Snack</label>

                <input type="radio" id="teatime" name="meal-type" value="teatime" 
                       <?php echo $recipe['meal_type'] === 'teatime' ? 'checked' : ''; ?>>
                <label for="teatime">Teatime</label>
            </div>

            <button type="submit" class="add-recipe-btn">Update Recipe</button>
        </form>
    </div>


    <div class="footer">
        <p>Sujals Cooking Private Limited &copy;</p>
    </div>
    

    <script src="recipeshowscript.js"></script>




</body>
</html>
