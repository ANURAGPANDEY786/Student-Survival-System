<?php
header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) { echo json_encode([]); exit; }

$sql = "
SELECT 
  p.*,
  IFNULL(r.avg_rating,0) AS avg_rating,
  IFNULL(r.total_reviews,0) AS total_reviews,
  IFNULL(c.risk_points,0) AS risk_points,
  IFNULL(c.total_complaints,0) AS total_complaints
FROM pg_rooms p
LEFT JOIN (
  SELECT pg_id, ROUND(AVG(rating),1) AS avg_rating, COUNT(*) AS total_reviews
  FROM pg_reviews
  GROUP BY pg_id
) r ON r.pg_id = p.id
LEFT JOIN (
  SELECT pg_id,
         COUNT(*) AS total_complaints,
         SUM(CASE 
              WHEN confirms >= 5 THEN 3
              WHEN confirms >= 2 THEN 2
              ELSE 1
             END) AS risk_points
  FROM (
    SELECT pc.pg_id, pc.id,
           (SELECT COUNT(*) FROM pg_complaint_confirms pcc WHERE pcc.complaint_id = pc.id) AS confirms
    FROM pg_complaints pc
  ) t
  GROUP BY pg_id
) c ON c.pg_id = p.id
ORDER BY p.distance ASC
";

$res = $conn->query($sql);
$out = [];

while ($row = $res->fetch_assoc()) {
  $points = (int)$row["risk_points"];
  $avg = (float)$row["avg_rating"];

  // Risk logic (simple + strong)
  $level = "Safe";
  if ($points >= 8 || ($avg > 0 && $avg < 2.8)) $level = "High Risk";
  else if ($points >= 3 || ($avg > 0 && $avg < 3.6)) $level = "Warning";

  $row["risk_level"] = $level;
  $out[] = $row;
}

echo json_encode($out);
$conn->close();
