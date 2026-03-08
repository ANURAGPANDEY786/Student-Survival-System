<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) die("Invalid ID");

// delete issues linked to review first
$conn->query("DELETE FROM pg_review_issues WHERE review_id=$id");
$conn->query("DELETE FROM pg_reviews WHERE id=$id");

$conn->close();
header("Location: ../admin/moderation.php");
