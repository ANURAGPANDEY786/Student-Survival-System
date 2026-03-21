<?php
session_start();
include("../db.php");

if(!isset($_SESSION["user_id"])) exit(json_encode(["ok"=>false]));

$user_id = $_SESSION["user_id"];
$amount = $_POST["amount"];
$category = $_POST["category"];
$note = $_POST["note"];

mysqli_query($conn,"INSERT INTO expenses(user_id,amount,category,note,date) VALUES($user_id,$amount,'$category','$note',CURDATE())");

echo json_encode(["ok"=>true]);