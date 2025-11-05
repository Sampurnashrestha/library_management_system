<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Library Admin Login</title>
  <link rel="stylesheet" href="login.css" />

</head>
<body>
  
  <main class="login-container">
    <section class="login-box">
      <h2>Library Admin Login</h2>
      <form action="admindasboard.php" method="POST">
        <div class="input-group">
          <label for="admin-username">Admin Username</label>
          <input type="text" id="admin-username" name="username" required />
        </div>

        <div class="input-group">
          <label for="admin-password">Password</label>
          <input type="password" id="admin-password" name="password" required />
        </div>

        <button type="submit">Login</button>
        <p class="note">Only authorized library staff can access this portal.</p>
      </form>
    </section>
  </main>

  <?php
  // admin_login.php - simple authentication example
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded admin credentials (just for demonstration)
    $admin_username = 'admin@gmail.com';
    $admin_password = 'password123';

    if ($username == $admin_username && $password == $admin_password) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo '<p>Invalid login credentials</p>';
    }
}

  ?>

    
  ?>
</body>
</html>
