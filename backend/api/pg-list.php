<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "sss");

if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

$sql = "SELECT * FROM pg_rooms ORDER BY price ASC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
$conn->close();
