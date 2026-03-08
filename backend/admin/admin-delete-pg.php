<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) die("Invalid ID");

// (Optional) delete photo file name from server
$res = $conn->query("SELECT photo FROM pg_rooms WHERE id=$id");
$row = $res ? $res->fetch_assoc() : null;
if ($row && !empty($row["photo"])) {
  $path = "../../uploads/pg/" . $row["photo"];
  if (file_exists($path)) @unlink($path);
}

$conn->query("DELETE FROM pg_rooms WHERE id=$id");
$conn->close();

header("Location: ../admin/pg-manage.php");
