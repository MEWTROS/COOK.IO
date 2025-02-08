<?php
require_once 'conn.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "unavailable";
    } else {
        echo "available";
    }

    $stmt->close();
    $conn->close();
}
?>
