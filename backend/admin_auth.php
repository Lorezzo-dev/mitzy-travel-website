<?php
session_start();

// 🧠 Database connection (Hostinger)
$servername = "localhost";
$username = "u364560609_lorenzmitzy";
$password = "7U5AIMc;hN";
$dbname = "u364560609_MitzyDB";

$conn = new mysqli($servername, $username, $password, $dbname);

// 🛑 Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user = trim($_POST['username'] ?? '');
$pass = trim($_POST['password'] ?? '');

if ($user === '' || $pass === '') {
  echo "<script>alert('Please enter both username and password.'); window.location.href='admin-login.php';</script>";
  exit();
}

// ✅ Fetch user
$sql = "SELECT * FROM admin_users WHERE username = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("Database error: " . $conn->error);
}
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();

  // ✅ Secure password verification
  if (password_verify($pass, $row['password'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $row['username'];
    header("Location: admin.php");
    exit();
  } else {
    echo "<script>alert('Invalid password.'); window.location.href='admin-login.php';</script>";
  }
} else {
  echo "<script>alert('User not found.'); window.location.href='admin-login.php';</script>";
}

$conn->close();
?>
