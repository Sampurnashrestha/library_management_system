<?php
include '../../connection.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Redirect if category or id is missing
if (!$category || !$id) {
    die("Invalid book selection.");
}

// Fetch book details
$sql = "SELECT * FROM `$category` WHERE id = $id";
$result = $connection->query($sql);
if (!$result || $result->num_rows == 0) {
    die("Book not found.");
}
$book = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_name = $connection->real_escape_string($_POST['book_name']);
    $author = $connection->real_escape_string($_POST['author']);
    $quantity = (int)$_POST['quantity'];

    // Handle image upload if provided
    $image_path = $book['image']; // default existing image
    if (!empty($_FILES['image']['name'])) {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $folder = "../admin/uploads/" . $filename;

        if (move_uploaded_file($tempname, $folder)) {
            $image_path = $folder;
        } else {
            echo "<script>alert('Failed to upload new image. Using existing image.');</script>";
        }
    }

    // Update the book
    $update_sql = "UPDATE `$category` SET book_name='$book_name', author='$author', quantity=$quantity, image='$image_path' WHERE id=$id";
    if ($connection->query($update_sql) === TRUE) {
        echo "<script>alert('Book updated successfully!'); window.location.href='issuedbook.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error updating book: ".$connection->error."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Book - <?= htmlspecialchars($book['book_name']) ?></title>
  <link rel="stylesheet" href="admin.css">
  <style>
    .form-container {
      max-width: 500px;
      margin: 120px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    h2 { text-align: center; margin-bottom: 25px; color: #007acc; }
    label { display: block; margin: 15px 0 6px; font-weight: bold; color: #333; }
    input, select, textarea {
      width: 100%; padding: 10px 12px; border: 1px solid #aaa;
      border-radius: 8px; font-size: 15px; background-color: #f9f9f9;
      transition: border-color 0.3s ease;
    }
    input:focus, select:focus, textarea:focus { outline: none; border-color: #007acc; }
    .submit-btn {
      margin-top: 25px; background-color: #007acc; color: white;
      border: none; padding: 12px; width: 100%; border-radius: 30px;
      font-size: 1em; cursor: pointer; transition: background 0.3s ease;
    }
    .submit-btn:hover { background-color: #005b99; }
    .message { text-align: center; margin-top: 20px; color: green; font-weight: bold; }
  </style>
</head>
<body>
 <nav class="admin-nav">
    <div class="logo">📚 Admin Dashboard</div>
    <ul class="admin-menu">
      <li><a href="../admin/admindasboard.php">Dashboard</a></li>
      <li><a href="../admin/manageuser.php">Users</a></li>
      <li><a href="../admin/issuedbook.php">Books</a></li>
      <li><a href="../admin/book.php">IssuedBooks</a></li>
      <li><a href="../admin/adminlogin.php">Logout</a></li>
    </ul>
  </nav>

  <div class="form-container">
    <h2>Edit Book - <?= htmlspecialchars($book['book_name']) ?></h2>
    <form method="post" enctype="multipart/form-data">
      <label for="book_name">Book Name</label>
      <input type="text" name="book_name" id="book_name" value="<?= htmlspecialchars($book['book_name']) ?>" required>

      <label for="author">Author</label>
      <input type="text" name="author" id="author" value="<?= htmlspecialchars($book['author']) ?>" required>

      <label for="quantity">Quantity</label>
      <input type="number" name="quantity" id="quantity" value="<?= $book['quantity'] ?>" required>

      <label for="image">Book Image (leave empty to keep current)</label>
      <input type="file" name="image" id="image">

      <button type="submit" class="submit-btn">Update Book</button>
    </form>
  </div>

  <footer>
    &copy; 2025 Online Library Admin Dashboard. All rights reserved.
  </footer>
</body>
</html>
