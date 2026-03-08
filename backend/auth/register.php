<?php
session_start();
include("../db.php");

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

if($name==="" || $email==="" || $password===""){
  die("All fields required");
}

/* Hash password */
$hash = password_hash($password, PASSWORD_DEFAULT);

/* Insert */
$stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
$stmt->bind_param("sss", $name, $email, $hash);

if($stmt->execute()){
  $_SESSION["student_id"] = $stmt->insert_id;
  $_SESSION["student_name"] = $name;
  header("Location: ../../frontend/index.html");
} else {
  echo "Email already exists.";
}