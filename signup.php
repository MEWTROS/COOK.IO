<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "webtech";  


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
        alert('INVALID EMAIL FORMAT');
        window.location.href = 'signup.html';
        </script>";
        exit();
    }
    if (empty($username) || empty($password)) {
        echo "<script>
        alert('Username and password are required.');
        window.location.href = 'signup.html';
        </script>";
        exit();
    }
    if (strlen($password) < 2) {
        echo "<script>
        alert('Password must be at least 2 characters long.');
        window.location.href = 'signup.html';
        </script>";
        exit();
    }



    $checkSql = "SELECT username FROM users WHERE username = ?";
    if ($checkStmt = $conn->prepare($checkSql)) {
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkStmt->store_result(); // Store the result so we can check the number of rows

        if ($checkStmt->num_rows > 0) {
            // Username already exists
            echo "<script>
            alert('Username already exists. Please choose a different username.');
            window.location.href = 'signup.html';
            </script>";
            exit();
        }

        $checkStmt->close();
    } else {
        echo "Error preparing check statement: " . $conn->error;
    }





    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $role = 'user';

    
    $sql = "INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)";

    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $email, $username, $hashed_password, $role);

        
        if ($stmt->execute()) {
            
            header("Location: login.html");
            exit(); 
        } else {
            echo "Error: " . $stmt->error; 
        }

        
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}


$conn->close();
?>
