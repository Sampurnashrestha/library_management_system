<?php
include '../../connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Book - Library System</title>
  <link rel="stylesheet" href="admin.css"> <!-- Use your preferred CSS file -->
</head>
<style>
  .form-container {
  max-width: 500px;
  margin: 120px auto;
  background: rgba(255, 255, 255, 0.95);
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Header */
h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #007acc;
}

/* Labels */
label {
  display: block;
  margin: 15px 0 6px;
  font-weight: bold;
  color: #333;
}

/* Inputs & select dropdowns */
input,
select,
textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #aaa;
  border-radius: 8px;
  font-size: 15px;
  background-color: #f9f9f9;
  transition: border-color 0.3s ease;
}

input:focus,
select:focus,
textarea:focus {
  outline: none;
  border-color: #007acc;
}

/* Button */
.submit-btn {
  margin-top: 25px;
  background-color: #007acc;
  color: white;
  border: none;
  padding: 12px;
  width: 100%;
  border-radius: 30px;
  font-size: 1em;
  cursor: pointer;
  transition: background 0.3s ease;
}

.submit-btn:hover {
  background-color: #005b99;
}

/* Message for feedback */
.message {
  text-align: center;
  margin-top: 20px;
  color: green;
  font-weight: bold;
}

/* Responsive tweak */
@media (max-width: 540px) {
  .form-container {
    margin: 80px 20px;
    padding: 20px;
  }
}
</style>
<body>
  <nav class="admin-nav">
    <div class="logo">📚 Admin Dashboard</div>
    <ul class="admin-menu">
      <li><a href="../admin/admindasboard.php">Dashboard</a></li>
      <li><a href="../admin/manageuser.php">Users</a></li>
      <li><a href="#">Books</a></li>
      <li><a href="../admin/adminlogin.php">Logout</a></li>
    </ul>
  </nav>
  <div class="form-container">
  <h2>Add New Book</h2>
  <form method="post" enctype="multipart/form-data">
    <label for="book_name">Book Name</label>
    <input type="text" name="book_name" id="book_name" required>

    <label for="author">Author</label>
    <input type="text" name="author" id="author" required>

    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" required>

    <label for="category">Category</label>
    <select name="category" id="category" required>
      <option value="BBA">BBA</option>
      <option value="BCSIT">BCSIT</option>
      <option value="BHM">BHM</option>
    </select>

    <label for="image">Book Image</label>
    <input type="file" name="image" id="image" required>

    <button type="submit" class="submit-btn">Add Book</button>

    <div class="message"> <!-- optional message --> </div>
  </form>
</div>


  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_name = $connection->real_escape_string($_POST["book_name"]);
    $author = $connection->real_escape_string($_POST["author"]);
    $quantity = (int)$_POST["quantity"];
    $category = $connection->real_escape_string($_POST["category"]);

    $filename = $_FILES["image"]["name"];
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = "../admin/uploads/" . $filename;

    if (move_uploaded_file($tempname, $folder)) {
      $sql = "INSERT INTO `$category` (book_name, author, quantity, image) VALUES ('$book_name', '$author', $quantity, '$folder')";
      if ($connection->query($sql) === TRUE) {
        echo "<script>alert('Book added successfully!'); window.location.href='../admin/admindasboard.php';</script>";
        exit;
      } else {
        echo "<script>alert('Error adding book: " . $connection->error . "');</script>";
      }
    } else {
      echo "<script>alert('Failed to upload image');</script>";
    }
  }
  ?>
</body>

</html>
