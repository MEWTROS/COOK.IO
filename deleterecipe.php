<?php
session_start();

// Check if the user is logged in and if they have admin privileges
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You do not have permission to delete recipes."; // Set error message
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

    // Prepare delete SQL statement
    $sql = "DELETE FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);

    // Try to execute and check if the deletion was successful
    if ($stmt->execute()) {
        // Check how many rows were affected
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Recipe deleted successfully!"; // Success message
            header("Location: recipes.php"); // Redirect to your recipe list page
            exit; // Stop further execution
        } else {
            $_SESSION['error'] = "No recipe found with the given ID."; // Set error message
            header("Location: recipeshow.php?id=" . $recipe_id); // Redirect back to the recipe page
            exit; // Stop further execution
        }
    } else {
        $_SESSION['error'] = "Error executing query: " . $stmt->error; // Set error message
        header("Location: recipes.php"); // Redirect to recipe list with error
        exit; // Stop further execution
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "No recipe ID provided."; // Set error message
    header("Location: recipes.php"); // Redirect to recipe list
    exit; // Stop further execution
}

$conn->close();
?>
