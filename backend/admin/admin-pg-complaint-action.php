<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

$id = intval($_GET["id"] ?? 0);
$action = $_GET["action"] ?? "";

if ($id <= 0) die("Invalid ID");

if ($action === "delete") {
  // delete confirms first
  $conn->query("DELETE FROM pg_complaint_confirms WHERE complaint_id=$id");
  $conn->query("DELETE FROM pg_complaints WHERE id=$id");
} 
else if ($action === "resolve") {
  $conn->query("UPDATE pg_complaints SET status='Resolved' WHERE id=$id");
}
else if ($action === "open") {
  $conn->query("UPDATE pg_complaints SET status='Open' WHERE id=$id");
}

$conn->close();
header("Location: ../admin/moderation.php");
