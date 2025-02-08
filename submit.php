<?php
session_start(); // Start the session

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$servername = "localhost";
$username = "root";  // Adjust if you have a different username
$password = "";      // Adjust if you have a password set for your MySQL
$dbname = "webtech";  // Name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Make sure the user is logged in and the user_id is set in the session
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to submit a recipe.";
        exit();
    }

    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session

    // Retrieve and sanitize form data
    $title = trim($_POST['recipe-name']); // This should match 'title' column
    $ingredients = trim($_POST['ingredients']);
    $steps = trim($_POST['instructions']); // This should match 'steps' column
    $meal_type = trim($_POST['meal-type']); // Get the meal type from the form

    // Handle image upload
    $imagePath = 'uploads/' . basename($_FILES['image']['name']);

    // Validate input data
    if (empty($title) || empty($ingredients) || empty($steps) || empty($meal_type)) {
        echo "All fields are required.";
        exit();
    }

    // Check if the uploaded file is a valid image
    $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    $validImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $validImageTypes)) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        exit();
    }

    // Move uploaded file to the specified directory
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        echo "Error uploading file.";
        exit();
    }

    // Prepare the SQL query to insert the new recipe (ensure column names match your table)
    $sql = "INSERT INTO recipes (user_id, title, ingredients, steps, image_url, meal_type) VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssss", $user_id, $title, $ingredients, $steps, $imagePath, $meal_type);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to index page after successful submission
            header("Location: index.php?success=Recipe added successfully!");
            exit(); // Ensure no further code is executed
        } else {
            echo "Error: " . $stmt->error; // Display error if execution fails
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
