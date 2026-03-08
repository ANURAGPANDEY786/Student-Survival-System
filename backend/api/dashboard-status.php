<?php
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "sss");

if ($conn->connect_error) {
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$sql = "SELECT type, status_text, report_count FROM dashboard_status";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
  $data[$row['type']] = [
    "status" => $row['status_text'],
    "count" => (int)$row['report_count']
  ];
}

echo json_encode($data);
$conn->close();
