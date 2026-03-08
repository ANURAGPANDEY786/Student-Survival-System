<?php
header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) { echo json_encode([]); exit; }

// 1) Today complaints count (PG complaints)
$todayRes = $conn->query("SELECT COUNT(*) AS c FROM pg_complaints WHERE DATE(created_at)=CURDATE()");
$todayRow = $todayRes->fetch_assoc();
$today_pg_complaints = (int)$todayRow["c"];

// 2) Trending issue last 7 days (PG complaints)
$trendRes = $conn->query("
  SELECT issue, COUNT(*) AS c
  FROM pg_complaints
  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
  GROUP BY issue
  ORDER BY c DESC
  LIMIT 1
");
$trend = $trendRes->fetch_assoc();
$trending_issue = $trend ? $trend["issue"] : "none";
$trending_issue_count = $trend ? (int)$trend["c"] : 0;

// 3) Top 3 risky PGs (based on confirms + avg rating)
$sql = "
SELECT 
  p.id, p.name, p.price, p.distance, p.type, p.photo,
  IFNULL(r.avg_rating,0) AS avg_rating,
  IFNULL(r.total_reviews,0) AS total_reviews,
  IFNULL(c.total_complaints,0) AS total_complaints,
  IFNULL(c.risk_points,0) AS risk_points
FROM pg_rooms p
LEFT JOIN (
  SELECT pg_id, ROUND(AVG(rating),1) AS avg_rating, COUNT(*) AS total_reviews
  FROM pg_reviews
  GROUP BY pg_id
) r ON r.pg_id = p.id
LEFT JOIN (
  SELECT pg_id,
         COUNT(*) AS total_complaints,
         SUM(
           CASE
             WHEN confirms >= 5 THEN 3
             WHEN confirms >= 2 THEN 2
             ELSE 1
           END
         ) AS risk_points
  FROM (
    SELECT pc.pg_id, pc.id,
           (SELECT COUNT(*) FROM pg_complaint_confirms pcc WHERE pcc.complaint_id = pc.id) AS confirms
    FROM pg_complaints pc
  ) t
  GROUP BY pg_id
) c ON c.pg_id = p.id
ORDER BY risk_points DESC, avg_rating ASC, total_complaints DESC
LIMIT 3
";

$res = $conn->query($sql);
$top = [];

while ($row = $res->fetch_assoc()) {
  $points = (int)$row["risk_points"];
  $avg = (float)$row["avg_rating"];

  $level = "Safe";
  if ($points >= 8 || ($avg > 0 && $avg < 2.8)) $level = "High Risk";
  else if ($points >= 3 || ($avg > 0 && $avg < 3.6)) $level = "Warning";

  $row["risk_level"] = $level;
  $top[] = $row;
}

echo json_encode([
  "today_pg_complaints" => $today_pg_complaints,
  "trending_issue" => $trending_issue,
  "trending_issue_count" => $trending_issue_count,
  "top_risky_pgs" => $top
]);

$conn->close();
