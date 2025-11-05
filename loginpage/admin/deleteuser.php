<?php
include '../../connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect after deletion
        header("Location: manageuser.php?message=User+Deleted");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    $stmt->close();
}

?>