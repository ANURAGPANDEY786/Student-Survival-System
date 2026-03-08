<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"] ?? "";
  $password = $_POST["password"] ?? "";

  $stmt = $conn->prepare("SELECT id, password_hash FROM admins WHERE username=? LIMIT 1");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $admin = $result->fetch_assoc();
  $stmt->close();

  if ($admin && password_verify($password, $admin["password_hash"])) {
    $_SESSION["admin_id"] = $admin["id"];
    $_SESSION["admin_username"] = $username;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Invalid username or password";
  }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .box{ max-width:420px; margin:80px auto; background:#111; border:1px solid #222; padding:20px; border-radius:12px; }
    input, button{ width:100%; padding:12px; margin-top:10px; border-radius:10px; border:none; }
    button{ background:#22c55e; font-weight:bold; cursor:pointer; color:#000; }
    .err{ background:#ef4444; padding:10px; border-radius:10px; margin-top:10px; }
    .mini{ opacity:0.8; font-size:13px; margin-top:10px; }
  </style>
</head>
<body>
  <div class="box">
    <h2>🧑‍💼 Admin Login</h2>

    <?php if($error) { ?>
      <div class="err"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="POST">
      <input name="username" placeholder="Username" required>
      <input name="password" type="password" placeholder="Password" required>
      <button>Login</button>
    </form>

    <div class="mini">
      Default: <b>admin</b> / <b>admin123</b>
    </div>
  </div>
</body>
</html>
