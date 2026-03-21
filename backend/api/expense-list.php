<?php
session_start();
include("../db.php");

if(!isset($_SESSION["user_id"])) exit(json_encode([]));

$user_id = $_SESSION["user_id"];

$res = mysqli_query($conn,"SELECT * FROM expenses WHERE user_id=$user_id ORDER BY id DESC");

$data = [];
while($row = mysqli_fetch_assoc($res)){
  $data[] = $row;
}

echo json_encode($data);