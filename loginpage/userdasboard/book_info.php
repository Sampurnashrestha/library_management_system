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

// 📘 Handle book issue
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id']) && isset($_POST['issue_book'])) {
    $book_id = $_POST['book_id'];
    $category = $_POST['category'];
    $issue_date = date("Y-m-d");
    $return_date = date("Y-m-d", strtotime("+7 days"));

    $check = mysqli_query($connection, "SELECT * FROM issued_books WHERE user_email='$email' AND book_id=$book_id AND category='$category' AND status='issued'");
    if (mysqli_num_rows($check) > 0) {
        $message = "⚠️ You have already issued this book.";
    } else {
        $sql = "INSERT INTO issued_books (user_email, category, book_id, issue_date, return_date, status)
                VALUES ('$email', '$category', '$book_id', '$issue_date', '$return_date', 'issued')";
        $message = mysqli_query($connection, $sql) ? "✅ Book issued successfully!" : "❌ Error: " . mysqli_error($connection);
    }
}

// 📚 Load books by category
$books = [];
if ($selectedCategory) {
    $books = mysqli_query($connection, "SELECT * FROM `$selectedCategory`");
}

// 📋 Fetch issued books of this user
$sqlIssued = "
SELECT ib.id, ib.category, ib.book_id, ib.issue_date, ib.return_date, ib.status,
       COALESCE(b1.book_name, b2.book_name, b3.book_name) AS book_name,
       COALESCE(b1.author, b2.author, b3.author) AS author
FROM issued_books ib
LEFT JOIN BHM b1 ON ib.category='BHM' AND ib.book_id=b1.id
LEFT JOIN BBA b2 ON ib.category='BBA' AND ib.book_id=b2.id
LEFT JOIN BCSIT b3 ON ib.category='BCSIT' AND ib.book_id=b3.id
WHERE ib.user_email='$email'
ORDER BY ib.id DESC
";
$issuedBooks = mysqli_query($connection, $sqlIssued);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User - Issue & Return Book</title>
    <link rel="stylesheet" href="../userdasboard/front.css">
    <style>
        .container {
            width: 80%;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007acc;
            margin-bottom: 25px;
        }

        form {
            text-align: center;
            margin-bottom: 40px;
        }

        select,
        button {
            padding: 10px 14px;
            margin: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background: #007acc;
            color: #fff;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        button:hover {
            background: #005f99;
        }

        .msg {
            text-align: center;
            font-weight: bold;
            color: #007acc;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007acc;
            color: white;
        }

        td {
            background: #fff;
        }

        .status-issued {
            color: #e67e22;
            font-weight: bold;
        }

        .status-returned {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <div class="logo">📚 Online Library</div>
            <ul class="menu">
                <li><a href="../userdasboard/front.php">Dashboard</a></li>
                <li><a href="../userdasboard/view_book.php">Books</a></li>
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
        <h2>📖 Issue Books</h2>
        <?php if ($message) echo "<p class='msg'>$message</p>"; ?>

        <!-- Issue Book Form -->
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
                        <option value="<?= $book['id'] ?>"><?= htmlspecialchars($book['book_name']) ?> by <?= htmlspecialchars($book['author']) ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="hidden" name="category" value="<?= $selectedCategory ?>">
                <button type="submit" name="issue_book">Issue Book</button>
            <?php elseif ($selectedCategory): ?>
                <p>No books available in <?= htmlspecialchars($selectedCategory) ?>.</p>
            <?php endif; ?>
        </form>

        <!-- User Issued Books -->
        <h3 style="text-align:center; margin-top:40px;">📚 Your Issued Books</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Book Name</th>
                <th>Author</th>
                <th>Issue Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>

            <?php if (mysqli_num_rows($issuedBooks) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($issuedBooks)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td><?= htmlspecialchars($row['book_name']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= $row['issue_date'] ?></td>
                        <td><?= $row['return_date'] ?></td>
                        <td class="<?= $row['status'] == 'issued' ? 'status-issued' : 'status-returned' ?>">
                            <?= ucfirst($row['status']) ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No issued books found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <footer style="text-align:center; margin:30px 0;">&copy; 2025 Online Library. All rights reserved.</footer>
</body>

</html>