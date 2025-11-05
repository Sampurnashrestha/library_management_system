<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Detail</title>
   <link rel="stylesheet" href="../userdasboard/front.css">
    <style>
        form {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        /* Label Styles */
        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: bold;
            margin-top: 15px;
        }

        /* Input Fields */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: border 0.3s;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Optional: Button (if you add one later) */
        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
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
    <form action="update_detailofuser.php" method="POST">
        <h2>Update Details</h2>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="New Username" required><br />

        <label for="username">email:</label>
        <input type="email" name="email" id="email" placeholder="New Email" required><br />

        <label for="pass">password:</label>
        <input type="password" name="pass" id="pass" placeholder="New Password" required><br />

        <button>Update</button>

    </form>

    <?php
    include '../../connection.php';
        session_start();
        if (!isset($_SESSION['email'])) {
    header("Location: ../user/loginofuser.php");
    exit();
}
    $get_email = $_SESSION['email'];
    // echo "<h1>" . $get_email  . "</h1>";
    $sql = "SELECT * FROM users WHERE Email = '$get_email'";
    $result = $connection->query($sql);
    $data=$result->fetch_assoc();
    // echo "<h1>" . $data['Email']  . "</h1>";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $username=$connection->real_escape_string($_POST['username']);
        $email=$connection->real_escape_string($_POST['email']);
         $password=$connection->real_escape_string($_POST['pass']);
         $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

            $sqli="UPDATE users SET Username='$username', Email='$email',Password='$hashedPassword' WHERE Email='$get_email'";
            if($connection->query($sqli)){
                echo "<script>alert('Update successful');</script>";
            }
            else{
                echo "<script>alert('Failed to Update');</script>";
            }
         
    }
    ?>
    <footer>
        <p>&copy; 2025 Online Library. All rights reserved.</p>
    </footer>
</body>

</html>