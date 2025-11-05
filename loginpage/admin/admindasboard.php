
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css" />
</head>
<body>
  <?php
include'../../connection.php';

// Count total users
$sql = "SELECT COUNT(id) AS total FROM users";
$result = $connection->query($sql);

$total_users = 0;
if ($row = $result->fetch_assoc()) {
    $total_users = $row['total'];
}
?>
  <nav class="admin-nav">
    <div class="logo">📚 Admin Dashboard</div>
    <ul class="admin-menu">
      <li><a href="../admin/admindasboard.php">Dashboard</a></li>
      <li><a href="../admin/manageuser.php">Users</a></li>
      <li><a href="../admin/issuedbook.php">Books</a></li>
      <li><a href="../admin/adminlogin.php">Logout</a></li>
    </ul>
  </nav>

  <div class="main-wrapper">
    <div class="dashboard-content">
      <h1>Welcome, Admin</h1>
      <div class="cards">
        <div class="card"><h2>Total Users</h2><p><?php echo $total_users; ?></p></div>
        <div class="card"><h2>Books Issued</h2><p>456</p></div>
        <div class="card"><h2>Available Books</h2><p>789</p></div>
      </div>
      <div class="admin-actions">
        <h2>Quick Actions</h2>
        <div class="actions">
          <a href="../admin/addbook.php" class="action-btn">➕ Add Book</a>
          <a href="../admin/manageuser.php" class="action-btn">👥 Manage Users</a>
        </div>
      </div>
    </div>
  </div>

  <footer>
    &copy; 2025 Online Library Admin Dashboard. All rights reserved.
  </footer>




</body>

</html>


