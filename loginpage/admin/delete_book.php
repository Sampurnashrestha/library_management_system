<?php
include '../../connection.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate input
if (!$category || !$id) {
    die("Invalid request.");
}

// Optional: Check if the book exists
$check_sql = "SELECT * FROM `$category` WHERE id = $id";
$check_result = $connection->query($check_sql);
if (!$check_result || $check_result->num_rows == 0) {
    die("Book not found.");
}

// Delete the book
$delete_sql = "DELETE FROM `$category` WHERE id = $id";
if ($connection->query($delete_sql) === TRUE) {
    echo "<script>alert('Book deleted successfully!'); window.location.href='issuedbook.php';</script>";
    exit;
} else {
    echo "<script>alert('Error deleting book: ".$connection->error."'); window.location.href='all_books.php';</script>";
    exit;
}
?>
