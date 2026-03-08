<?php
header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli("localhost", "root", "", "sss");

if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM pg_rooms WHERE id = $id";
$result = $conn->query($sql);

echo json_encode($result->fetch_assoc());
$conn->close();
