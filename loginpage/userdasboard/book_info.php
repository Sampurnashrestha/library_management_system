<?php
include '../../connection.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: loginofuser.php");
    exit();
}

$email = $_SESSION['email'];
$message = "";
$categories = ['BHM', 'BBA', 'BCSIT'];
$selectedCategory = $_POST['category'] ?? '';

// Handle issue form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $category = $_POST['category'];
    $issue_date = date("Y-m-d");
    $return_date = date("Y-m-d", strtotime("+7 days"));

    $check = mysqli_query($connection, "SELECT * FROM issued_books WHERE user_email='$email' AND book_id=$book_id AND category='$category'");
    if (mysqli_num_rows($check) > 0) {
        $message = "⚠️ Already issued this book.";
    } else {
        $sql = "INSERT INTO issued_books (user_email, category, book_id, issue_date, return_date)
                VALUES ('$email', '$category', '$book_id', '$issue_date', '$return_date')";
        $message = mysqli_query($connection, $sql) ? "✅ Book issued!" : "❌ Error: " . mysqli_error($connection);
    }
}

// Load books
$books = [];
if ($selectedCategory) {
    $books = mysqli_query($connection, "SELECT * FROM `$selectedCategory`");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User - Issue Book</title>
    <link rel="stylesheet" href="../userdasboard/front.css">
    <link rel="stylesheet" href="./fornt.css">
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
<div class="container">
    <h2>User - Issue Book</h2>
    <?php if ($message) echo "<p class='msg'>$message</p>"; ?>
    <form method="POST">
        <label>Select Category:</label>
        <select name="category" onchange="this.form.submit()" required>
            <option value="">--Choose--</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat ?>" <?= $cat == $selectedCategory ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
        </select>

        <?php if ($selectedCategory && mysqli_num_rows($books) > 0): ?>
            <label>Select Book:</label>
            <select name="book_id" required>
                <option value="">--Select Book--</option>
                <?php while ($book = mysqli_fetch_assoc($books)): ?>
                    <option value="<?= $book['id'] ?>"><?= $book['book_name'] ?> by <?= $book['author'] ?></option>
                <?php endwhile; ?>
            </select>
            <input type="hidden" name="category" value="<?= $selectedCategory ?>">
            <button type="submit">Issue Book</button>
        <?php elseif ($selectedCategory): ?>
            <p>No books available in <?= $selectedCategory ?>.</p>
        <?php endif; ?>
    </form>
</div>

<footer>
    <p>&copy; 2025 Online Library. All rights reserved.</p>
</footer>
</body>
</html>
