<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

$id = intval($_POST["id"] ?? 0);
if ($id <= 0) die("Invalid ID");

$name = $_POST["name"];
$price = $_POST["price"];
$distance = $_POST["distance"];
$type = $_POST["type"];
$phone = $_POST["phone"];
$address = $_POST["address"];
$facilities = $_POST["facilities"] ?? "";
$rules = $_POST["rules"] ?? "";

// Keep old photo by default
$photoName = null;
$res = $conn->query("SELECT photo FROM pg_rooms WHERE id=$id");
$row = $res ? $res->fetch_assoc() : null;
$oldPhoto = $row ? $row["photo"] : "";

// If new photo uploaded, replace
if (!empty($_FILES["photo"]["name"])) {
  $photoName = time() . "_" . basename($_FILES["photo"]["name"]);
  move_uploaded_file($_FILES["photo"]["tmp_name"], "../../uploads/pg/" . $photoName);

  // delete old photo file
  if (!empty($oldPhoto)) {
    $path = "../../uploads/pg/" . $oldPhoto;
    if (file_exists($path)) @unlink($path);
  }
} else {
  $photoName = $oldPhoto;
}

$stmt = $conn->prepare("
  UPDATE pg_rooms
  SET name=?, price=?, distance=?, type=?, phone=?, address=?, facilities=?, rules=?, photo=?
  WHERE id=?
");
$stmt->bind_param("sdsssssssi",
  $name, $price, $distance, $type, $phone, $address, $facilities, $rules, $photoName, $id
);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: ../admin/pg-manage.php");
