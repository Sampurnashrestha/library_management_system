<?php
include '../../connection.php'; // Update the path if needed

$sql = "SELECT Id, Username, Email, Gender FROM users ORDER BY id ASC";
$result = $connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="admin.css"> <!-- Reuse your CSS -->
  <style>
    .main-wrapper {
      flex: 1;
      padding-top: 100px; /* for nav spacing */
    }

    .user-table {
      width: 100%;
      max-width: 1000px;
      margin: 0 auto 60px;
      border-collapse: collapse;
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .user-table th, .user-table td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #ccc;
    }

    .user-table th {
      background-color: #007acc;
      color: white;
    }

    .user-table td {
      background-color: white;
    }

    .action-btn {
      padding: 6px 14px;
      font-size: 0.9em;
      border: none;
      border-radius: 20px;
      background-color: #007acc;
      color: white;
      cursor: pointer;
      text-decoration: none;
    }

    .action-btn:hover {
      background-color: #005b99;
    }
  </style>
</head>
<body>
  <nav class="admin-nav">
    <div class="logo">📚 Admin Dashboard</div>
    <ul class="admin-menu">
      <li><a href="../admin/admindasboard.php">Dashboard</a></li>
      <li><a href="../admin/manageuser.php">Users</a></li>
      <li><a href="../admin/book.php">Books</a></li>
      <li><a href="../admin/issuedbook.php">Issued Books</a></li>
      <li><a href="../admin/adminlogin.php">Logout</a></li>
    </ul>
  </nav>

  <div class="main-wrapper">
    <div class="dashboard-content">
      <h1>Manage Users</h1>
    </div>
    <table class="user-table">
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Gender</th>
        <th>Action</th>
      </tr>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['Id'] ?></td>
            <td><?= htmlspecialchars($row['Username']) ?></td>
            <td><?= htmlspecialchars($row['Email']) ?></td>
            <td><?= htmlspecialchars($row['Gender']) ?></td>
            <td>
              <a href="deleteuser.php?id=<?= $row['Id'] ?>" class="action-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6">No users found.</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

  <footer>
    &copy; 2025 Online Library Admin Dashboard. All rights reserved.
  </footer>
</body>
</html>
