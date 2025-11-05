<?php
include '../../connection.php'; // Adjust path as needed
session_start();

$sql = "SELECT ib.id, ib.user_email, ib.category, ib.book_id, ib.issue_date, ib.return_date,
        COALESCE(b1.book_name, b2.book_name, b3.book_name) AS book_title,
        COALESCE(b1.author, b2.author, b3.author) AS author
        FROM issued_books ib
        LEFT JOIN BHM b1 ON ib.category = 'BHM' AND ib.book_id = b1.id
        LEFT JOIN BBA b2 ON ib.category = 'BBA' AND ib.book_id = b2.id
        LEFT JOIN BCSIT b3 ON ib.category = 'BCSIT' AND ib.book_id = b3.id
        ORDER BY ib.id ASC";

$result = $connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Issued Books</title>
  <link rel="stylesheet" href="admin.css">
  <style>
    .main-wrapper {
      flex: 1;
      padding-top: 100px;
    }

    .issue-table {
      width: 100%;
      max-width: 1100px;
      margin: 0 auto 60px;
      border-collapse: collapse;
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .issue-table th, .issue-table td {
      padding: 14px;
      text-align: center;
      border-bottom: 1px solid #ccc;
    }

    .issue-table th {
      background-color: #007acc;
      color: white;
    }

    .issue-table td {
      background-color: white;
    }

    .action-btn {
      padding: 6px 14px;
      font-size: 0.9em;
      border: none;
      border-radius: 20px;
      background-color: #dc3545;
      color: white;
      cursor: pointer;
      text-decoration: none;
    }

    .action-btn:hover {
      background-color: #bb2d3b;
    }
  </style>
</head>
<body>
  <nav class="admin-nav">
    <div class="logo">📚 Admin Dashboard</div>
    <ul class="admin-menu">
      <li><a href="../admin/admindasboard.php">Dashboard</a></li>
      <li><a href="../admin/manageuser.php">Users</a></li>
      <li><a href="#">Books</a></li>
      <li><a href="adminlogin.php">Logout</a></li>
    </ul>
  </nav>

  <div class="main-wrapper">
    <div class="dashboard-content">
      <h1>Manage Issued Books</h1>
    </div>
    <table class="issue-table">
      <tr>
        <th>ID</th>
        <th>User Email</th>
        <th>Category</th>
        <th>Book Title</th>
        <th>Author</th>
        <th>Issue Date</th>
        <th>Return Date</th>
        <th>Action</th>
      </tr>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= $row['category'] ?></td>
            <td><?= htmlspecialchars($row['book_title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= $row['issue_date'] ?></td>
            <td><?= $row['return_date'] ?></td>
            <td>
              <a href="../admin/return_book.php?id=<?= $row['id'] ?>" class="action-btn"
                 onclick="return confirm('Are you sure you want to return this book?')"
                 >Return</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="8">No issued books found.</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

  <footer>
    &copy; 2025 Online Library Admin Dashboard. All rights reserved.
  </footer>
</body>
</html>
