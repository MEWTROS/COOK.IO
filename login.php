<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";  // Adjust if you have a different username
$password = "";      // Adjust if you have a password set for your MySQL
$dbname = "webtech"; // Name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input data
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit();
    }

    // Prepare the SQL query to select the user ID, hashed password, and role
    $sql = "SELECT id, password, role FROM users WHERE username = ?"; // Select id as well
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);

        // Execute the query
        $stmt->execute();
        $stmt->store_result();

        // Check if the username exists
        if ($stmt->num_rows > 0) {
            // Bind result variables
            $stmt->bind_result($user_id, $hashed_password, $role);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, store user_id, username, and role in session
                $_SESSION['user_id'] = $user_id; // Store the user ID in session
                $_SESSION['username'] = $username; // Store the username in session
                $_SESSION['role'] = $role; // Store the role in session
                header("Location: index.php"); // Redirect to the homepage
                exit(); // Ensure no further code is executed
            } else {
                echo "<script>
                alert('INVALID PASSWORD');
                window.location.href = 'login.html';
                </script>";

           }
        } else {
            echo "<script>
            alert('Username does not exist.');
            window.location.href = 'login.html';
            </script>";
            
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
