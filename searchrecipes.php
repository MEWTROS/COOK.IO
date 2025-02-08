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

// Get the search query from the GET request
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare and execute the SQL query to search for recipes
$sql = "SELECT id, title, image_url FROM recipes WHERE title LIKE ?";
$stmt = $conn->prepare($sql);

// Use wildcards for searching
$search_param = "%" . $search_query . "%"; 
$stmt->bind_param("s", $search_param);
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
