<?php
session_start();
include("../db.php");

header("Content-Type: application/json; charset=UTF-8");

$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
  echo json_encode(["ok"=>false, "msg"=>"Email & password required"]);
  exit;
}

// ✅ Safe query (prevents SQL injection)
$stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

if (!$user) {
  echo json_encode(["ok"=>false, "msg"=>"User not found"]);
  exit;
}

/*
  ✅ Password handling:
  - If your DB has plain text passwords currently, this supports BOTH:
    1) plain text match
    2) hashed match (password_hash / password_verify)
*/
$stored = $user["password"];
$passOk = false;

if (strpos($stored, "$2y$") === 0 || strpos($stored, "$2a$") === 0) {
  // hashed
  $passOk = password_verify($password, $stored);
} else {
  // plain text (temporary support)
  $passOk = ($password === $stored);
}

if (!$passOk) {
  echo json_encode(["ok"=>false, "msg"=>"Invalid password"]);
  exit;
}

// ✅ Set student session for PG module
$_SESSION["student_id"] = $user["id"];
$_SESSION["student_name"] = $user["name"] ?: $user["email"];

// Also keep your old key if other parts use it
$_SESSION["user_id"] = $user["id"];

echo json_encode([
  "ok" => true,
  "student_id" => $_SESSION["student_id"],
  "student_name" => $_SESSION["student_name"]
]);
