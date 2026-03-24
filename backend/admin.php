<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin-login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Mitzy Travel</title>
  <!-- ✅ go one level up to access the shared CSS -->
  <link rel="stylesheet" href="../assets/css/mtzy.css">
  <link rel="icon" href="../assets/images/icons/mtntlogo.png" type="image/png">

</head>
<body class="admin-dashboard">

  <div class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
    <p>Select what you’d like to manage:</p>

    <ul>
      <!-- ✅ adjusted links if tourinfo and visa_logger are in root -->
      <li><a href="admin_tours.php">Manage Tour Packages</a></li>
      <li><a href="admin_blog.php">Manage Blog Posts</a></li>
    </ul>

    <!-- ✅ logout stays inside backend -->
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const numPetals = 60;
  const body = document.body;

  for (let i = 0; i < numPetals; i++) {
    const petal = document.createElement("div");
    petal.classList.add("petal"); // same class name as login
    body.appendChild(petal);

    // random horizontal placement
    petal.style.left = Math.random() * 100 + "vw";

    // random delay and duration
    petal.style.animationDelay = Math.random() * 10 + "s";
    petal.style.animationDuration = 12 + Math.random() * 8 + "s";

    // random pinks for realism
    const colors = [
      "rgba(255, 182, 193, 0.9)",
      "rgba(255, 160, 180, 0.9)",
      "rgba(255, 130, 155, 0.85)",
      "rgba(255, 192, 203, 0.95)",
      "rgba(255, 170, 190, 0.9)"
    ];
    petal.style.background = colors[Math.floor(Math.random() * colors.length)];

    // subtle size variation
    const size = 12 + Math.random() * 8;
    petal.style.width = size + "px";
    petal.style.height = size + "px";
  }
});
</script>
</body>
</html>
