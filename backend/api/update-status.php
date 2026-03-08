<?php
$conn = new mysqli("localhost", "root", "", "sss");

if ($conn->connect_error) {
  die("DB Error");
}

$type = $_POST['type'];
$status = $_POST['status'];
$count = (int)$_POST['count'];

$sql = "UPDATE dashboard_status 
        SET status_text='$status', report_count=$count 
        WHERE type='$type'";

$conn->query($sql);

header("Location: ../admin/dashboard.php");
$conn->close();
