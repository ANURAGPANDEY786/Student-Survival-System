<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

$name = $_POST['name'];
$price = $_POST['price'];
$distance = $_POST['distance'];
$type = $_POST['type'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$facilities = $_POST['facilities'];
$rules = $_POST['rules'];

$photoName = time() . "_" . $_FILES['photo']['name'];
move_uploaded_file(
  $_FILES['photo']['tmp_name'],
  "../../uploads/pg/" . $photoName
);

$sql = "INSERT INTO pg_rooms 
(name, price, distance, type, phone, address, facilities, rules, photo)
VALUES
('$name','$price','$distance','$type','$phone','$address','$facilities','$rules','$photoName')";

$conn->query($sql);
header("Location: ../admin/add-pg.php");
