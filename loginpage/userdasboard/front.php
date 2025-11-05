<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Library</title>
    <link rel="stylesheet" href="../userdasboard/front.css">
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

<main class="hero-section">
  <div class="hero-content">
    <h1>Welcome to the Online Library</h1>
    <p>Discover a world of books, magazines, and academic resources—anytime, anywhere.</p>
   <form class="search-form" onsubmit="return handleSearch(event)">
  <input type="text" id="searchInput" placeholder="Search for books..." class="search-bar">
  <button type="submit">Search</button>
</form>
  </div>
</main>
<script>
  function handleSearch(event) {
    event.preventDefault(); // Prevent form from submitting normally
    const query = document.getElementById('searchInput').value.trim().toLowerCase();

    if (query === 'bba') {
      window.location.href = 'bba.php';
    } else if (query === 'bcsit') {
      window.location.href = 'bcsit.php';
    } else if (query === 'bhm') {
      window.location.href = 'bhm.php';
    } else {
      alert('No matching section found. Please search for BBA, BCSIT, or BHM.');
    }
    return false;
  }
</script>


<footer>
    <p>&copy; 2025 Online Library. All rights reserved.</p>
</footer>
</body>
</html>