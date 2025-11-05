<?php
include '../../connection.php';
session_start();

// ✅ Combine books from all 3 category tables
$categories = ['BCSIT', 'BBA', 'BHM'];
$books = [];

foreach ($categories as $cat) {
    $sql = "SELECT id, book_name, author, quantity, image, '$cat' AS category FROM `$cat`";
    $result = mysqli_query($connection, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Books - Admin</title>
  <link rel="stylesheet" href="admin1.css">
  <style>
    .main-wrapper {
      flex: 1;
      padding-top: 100px;
    }
    .book-table {
      width: 100%;
      max-width: 1100px;
      margin: 0 auto 60px;
      border-collapse: collapse;
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    .book-table th,
    .book-table td {
      padding: 14px;
      text-align: center;
      border-bottom: 1px solid #ccc;
    }
    .book-table th {
      background-color: #007acc;
      color: white;
    }
    .book-table td {
      background-color: white;
    }
    .book-table img {
      width: 60px;
      height: 80px;
      object-fit: cover;
      border-radius: 6px;
    }
    .edit-btn, .delete-btn {
      padding: 6px 14px;
      font-size: 0.9em;
      border: none;
      border-radius: 20px;
      color: white;
      cursor: pointer;
      text-decoration: none;
      transition: 0.3s;
      margin: 2px;
      display: inline-block;
    }
    .edit-btn {
      background-color: #007acc;
    }
    .edit-btn:hover {
      background-color: #005f99;
    }
    .delete-btn {
      background-color: #e74c3c;
    }
    .delete-btn:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>
  <nav class="admin-nav">
    <div class="logo">📚 Admin Dashboard</div>
    <ul class="admin-menu">
      <li><a href="../admin/admindasboard.php">Dashboard</a></li>
      <li><a href="../admin/manageuser.php">Users</a></li>
      <li><a href="../admin/managebooks.php">Manage Books</a></li>
      <li><a href="../admin/issuedbook.php">Issued Books</a></li>
      <li><a href="../admin/adminlogin.php">Logout</a></li>
    </ul>
  </nav>

  <div class="main-wrapper">
    <div class="dashboard-content">
      <h1 style="text-align:center;">📘 Manage All Books</h1>
    </div>

    <table class="book-table">
      <tr>
        <th>ID</th>
        <th>Category</th>
        <th>Image</th>
        <th>Book Name</th>
        <th>Author</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
      <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
          <tr>
            <td><?= $book['id'] ?></td>
            <td><?= $book['category'] ?></td>
            <td><img src="<?= htmlspecialchars($book['image']) ?>" alt="Book Image"></td>
            <td><?= htmlspecialchars($book['book_name']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td><?= htmlspecialchars($book['quantity']) ?></td>
            <td>
              <a href="edit_book.php?id=<?= $book['id'] ?>&category=<?= $book['category'] ?>" class="edit-btn">Edit</a>
              <a href="delete_book.php?id=<?= $book['id'] ?>&category=<?= $book['category'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7">No books found.</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <footer style="text-align:center; margin:30px 0;">&copy; 2025 Online Library Admin Dashboard. All rights reserved.</footer>
</body>
</html>
