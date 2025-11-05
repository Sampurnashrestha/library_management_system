<?php
include '../../connection.php';
session_start(); // Required to use $_SESSION

if (!isset($_SESSION['email'])) {
    header("Location: ../user/loginofuser.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE Email = '$email'";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Info</title>
    <link rel="stylesheet" href="../userdasboard/front.css">
    <link rel="stylesheet" href="account.css">
    
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
    <h2>User Account Info</h2>
    <div class="user-info">
  <?php
while ($row = $result->fetch_assoc()) {
    echo "<div class='card'>";

    // Use path directly from DB
    $imagePath = $row['image']; // example: 'user/image/pic1.png'

    echo "<img src='{$imagePath}' width='150' height='150' class='user-image' alt='User Image'>";
    echo "<p><strong>Name:</strong> {$row['username']}</p>";
    echo "<p><strong>Email:</strong> {$row['email']}</p>";
    echo "<p><strong>Gender:</strong> {$row['gender']}</p>";
    echo '<a href="update_detailofuser.php"><button>Update Info</button></a>';
    echo "</div>";
}
?>
 

    </div>
</div>

<footer>
    <p>&copy; 2025 Online Library. All rights reserved.</p>
</footer>

</body>
</html>