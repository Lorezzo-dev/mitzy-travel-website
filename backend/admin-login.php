<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Bevan&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <title>Mitzy Travel and Tours Inc.</title>
  <link rel="icon" href="../assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="../assets/css/mtzy.css">
  <style>
    /* LOGIN PAGE CUSTOM STYLES */

  </style>
</head>
<body class="admin-login">
  <div class="login-container">
    <div class="login-box">
      <img src="../assets/images/icons/mtntlogo.png" alt="MTZY Travel Logo" class="login-logo">
      <h1>Admin Login</h1>
      <p>Sign in to manage tour packages and bookings.</p>

<form class="login-form" action="admin_auth.php" method="POST">
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button class="login-btn" type="submit">Login</button>
</form>

      <div class="login-footer">
        <p><a href="home.php">← Back to Home</a></p>
      </div>
    </div>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", () => {
  const numPetals = 100;
  const body = document.body;

  for (let i = 0; i < numPetals; i++) {
    const petal = document.createElement("div");
    petal.classList.add("petal");

    // 🌸 Random horizontal placement
    petal.style.left = Math.random() * 100 + "vw";

    // ⏱️ Random animation timing
    petal.style.animationDelay = Math.random() * 10 + "s";
    petal.style.animationDuration = 14 + Math.random() * 8 + "s";

    // 🎨 Stronger contrast colors (rich cherry blossom palette)
    const colors = [
      "rgba(255, 182, 193, 0.9)", // light pink (classic sakura)
      "rgba(255, 160, 180, 0.9)", // deeper pink rose
      "rgba(255, 130, 155, 0.85)", // medium pink
      "rgba(255, 192, 203, 0.95)", // standard pink
      "rgba(255, 170, 190, 0.9)"   // warm blush
    ];
    petal.style.background = colors[Math.floor(Math.random() * colors.length)];

    // 📏 Random size variation for realism
    const size = 10 + Math.random() * 8;
    petal.style.width = size + "px";
    petal.style.height = size + "px";

    body.appendChild(petal);
  }
});

  </script>
</body>
</html>