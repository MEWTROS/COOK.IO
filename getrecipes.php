<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";  // Adjust your MySQL username
$password = "";      // Adjust your MySQL password
$dbname = "webtech";  // Name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the meal type from the GET request
$meal_type = isset($_GET['meal_type']) ? $_GET['meal_type'] : '';

// Prepare and execute the SQL query to get the recipes based on meal type
$sql = "SELECT id, title, image_url FROM recipes WHERE meal_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $meal_type);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all the results and return them as JSON
$recipes = [];
while ($row = $result->fetch_assoc()) {
    $recipes[] = [
        'id' => $row['id'],        // This will be the recipe ID
        'name' => $row['title'],   // This will be your image name
        'url' => $row['image_url'] // This will be your image URL
    ];
}

$stmt->close();
$conn->close();

// Return the recipes in JSON format
echo json_encode($recipes);
?>
