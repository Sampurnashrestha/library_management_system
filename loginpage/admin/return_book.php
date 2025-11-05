<?php
include '../../connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $connection->prepare("DELETE FROM issued_books WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect after deletion
        header("Location:  issuedbook.php?message=User+Deleted");
        exit();
    } else {
        echo "Error : " . $conn->error;
    }

    $stmt->close();
}

?>