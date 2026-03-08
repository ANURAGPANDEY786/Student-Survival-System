<?php
header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli("localhost","root","","sss");
if ($conn->connect_error) { echo json_encode([]); exit; }

$pg_id = intval($_GET["pg_id"]);

$sql = "
SELECT pc.id, pc.student_name, pc.issue, pc.message, pc.created_at,
       (SELECT COUNT(*) FROM pg_complaint_confirms pcc WHERE pcc.complaint_id=pc.id) AS confirms
FROM pg_complaints pc
WHERE pc.pg_id=$pg_id
ORDER BY pc.created_at DESC
LIMIT 20
";
$res = $conn->query($sql);

$out = [];
while($row = $res->fetch_assoc()) $out[] = $row;

echo json_encode($out);
$conn->close();
