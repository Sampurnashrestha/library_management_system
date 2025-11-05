<?php
include '../../connection.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../user/loginofuser.php");
    exit();
}

$email = $_SESSION['email'];
$message = "";

// Handle issue book request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id']) && isset($_POST['category'])) {
    $book_id = intval($_POST['book_id']);
    $category = $_POST['category'];
    $issue_date = date("Y-m-d");
    $return_date = date("Y-m-d", strtotime("+7 days"));

    // Check if already issued
    $check = mysqli_query($connection, "SELECT * FROM issued_books WHERE user_email='$email' AND book_id=$book_id AND category='$category' AND status='issued'");
    if (mysqli_num_rows($check) > 0) {
        $message = "⚠️ You already issued this book.";
    } else {
        // Insert issue record
        $sql = "INSERT INTO issued_books (user_email, category, book_id, issue_date, return_date, status)
                VALUES ('$email', '$category', '$book_id', '$issue_date', '$return_date', 'issued')";
        if (mysqli_query($connection, $sql)) {
            $message = "✅ Book issued successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($connection);
        }
    }
}

// Load books
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'ALL';
$tables = ['BBA', 'BCSIT', 'BHM'];
$all_books = [];

if ($selected_category !== 'ALL' && in_array($selected_category, $tables)) {
    $sql = "SELECT id, book_name, author, quantity, image FROM $selected_category";
    $result = $connection->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['category'] = $selected_category;
            $all_books[] = $row;
        }
    }
} else {
    foreach ($tables as $table) {
        $sql = "SELECT id, book_name, author, quantity, image FROM $table";
        $result = $connection->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['category'] = $table;
                $all_books[] = $row;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Books</title>
  <link rel="stylesheet" href="../userdasboard/front.css">
  <link rel="stylesheet" href="account.css">
  <style>
  .book-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
  }
  .book-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 170px;
    text-align: center;
    transition: transform 0.3s;
  }
  .book-card:hover { transform: translateY(-5px); }
  .book-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-bottom: 1px solid #ccc;
  }
  .book-card .info { padding: 8px; }
  .book-card h3 { font-size: 0.95em; margin: 4px 0; }
  .book-card p { margin: 3px 0; font-size: 0.85em; color: #333; }
  .category-badge {
    background-color: #007acc;
    color: white;
    padding: 2px 8px;
    font-size: 0.7em;
    border-radius: 12px;
    margin-bottom: 6px;
    display: inline-block;
  }
  .search-form {
    text-align: center;
    margin-top: 30px;
  }
  .search-form select,
  .search-form button {
    padding: 6px 12px;
    font-size: 0.95em;
    margin-left: 8px;
    border-radius: 6px;
  }
  .issue-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 0.8em;
    margin-top: 5px;
  }
  .issue-btn:hover {
    background-color: #218838;
  }
  .msg {
    text-align: center;
    font-weight: bold;
    color: #007acc;
    margin-top: 20px;
  }
  </style>
</head>
<body>

<nav>
  <div class="nav-container">
    <div class="logo">📚 Online Library</div>
    <ul class="menu">
      <li class="dropdown"><a href="../userdasboard/front.php">Dashboard</a></li>
      <li class="dropdown"><a href="../userdasboard/view_book.php">Books</a></li>
      <li class="dropdown">
        <a href="#">Account</a>
        <ul class="dropdown-menu">
          <li><a href="account.php">Account Info</a></li>
          <li><a href="../userdasboard/book_info.php">Books Info</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<div class="content-container">
  <h2 style="text-align:center;">Available Books</h2>

  <?php if ($message): ?>
    <p class="msg"><?= $message ?></p>
  <?php endif; ?>

  <form method="GET" class="search-form">
    <label for="category">Filter by Category:</label>
    <select name="category" id="category">
      <option value="ALL" <?= $selected_category == 'ALL' ? 'selected' : '' ?>>All</option>
      <option value="BBA" <?= $selected_category == 'BBA' ? 'selected' : '' ?>>BBA</option>
      <option value="BCSIT" <?= $selected_category == 'BCSIT' ? 'selected' : '' ?>>BCSIT</option>
      <option value="BHM" <?= $selected_category == 'BHM' ? 'selected' : '' ?>>BHM</option>
    </select>
    <button type="submit">Search</button>
  </form>

  <div class="book-grid">
    <?php if (!empty($all_books)): ?>
      <?php foreach ($all_books as $book): ?>
        <div class="book-card">
          <img src="<?= htmlspecialchars($book['image']) ?>" alt="Book Image">
          <div class="info">
            <div class="category-badge"><?= htmlspecialchars($book['category']) ?></div>
            <h3><?= htmlspecialchars($book['book_name']) ?></h3>
            <p>👤 <?= htmlspecialchars($book['author']) ?></p>
            <p>📦 Quantity: <?= $book['quantity'] ?></p>
            
            <!-- Issue Book Button -->
            <form method="POST" style="margin-top:5px;">
              <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
              <input type="hidden" name="category" value="<?= $book['category'] ?>">
              <button type="submit" class="issue-btn">📘 Issue Book</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align:center;">No books found.</p>
    <?php endif; ?>
  </div>
</div>

<footer>
  <p style="text-align:center;">&copy; 2025 Online Library. All rights reserved.</p>
</footer>

</body>
</html>
