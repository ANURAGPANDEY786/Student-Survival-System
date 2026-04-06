<?php
include("../db.php");
session_start();

// ✅ YOUR ACTUAL COLUMN
$user_id = $_SESSION['user_id'] ?? 0;

// ✅ DELETE USER DATA
mysqli_query($conn, "DELETE FROM expenses WHERE user_id='$user_id'");


echo json_encode(["ok"=>true]);