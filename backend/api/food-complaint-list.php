<?php
include("../db.php");
header("Content-Type: application/json; charset=UTF-8");

$food_id = intval($_GET["food_id"] ?? 0);
if ($food_id <= 0) { echo json_encode([]); exit; }

$q = "
SELECT id, issue, message, confirms, severity, created_at
FROM food_complaints
WHERE food_id=$food_id
ORDER BY id DESC
LIMIT 50
";
$res = mysqli_query($conn, $q);

$list = [];
while($row = mysqli_fetch_assoc($res)) $list[] = $row;

echo json_encode($list);