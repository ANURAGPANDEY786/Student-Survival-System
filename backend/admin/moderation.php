<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");

// Latest 25 reviews
$reviews = $conn->query("
  SELECT r.id, r.pg_id, r.student_name, r.rating, r.review_text, r.created_at, p.name AS pg_name
  FROM pg_reviews r
  JOIN pg_rooms p ON p.id = r.pg_id
  ORDER BY r.created_at DESC
  LIMIT 25
");

// Latest 25 pg complaints with confirms count + status
$complaints = $conn->query("
  SELECT c.id, c.pg_id, c.student_name, c.issue, c.message, c.status, c.created_at,
         p.name AS pg_name,
         (SELECT COUNT(*) FROM pg_complaint_confirms cc WHERE cc.complaint_id=c.id) AS confirms
  FROM pg_complaints c
  JOIN pg_rooms p ON p.id = c.pg_id
  ORDER BY c.created_at DESC
  LIMIT 25
");
?>
<?php require_once "auth.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin - Moderation</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .wrap{ max-width:1000px; margin:30px auto; }
    .topbar{ display:flex; gap:10px; align-items:center; margin-bottom:15px; flex-wrap:wrap; }
    a.btn{
      display:inline-block; padding:10px 12px; border-radius:10px;
      text-decoration:none; font-weight:bold;
    }
    .b1{ background:#3b82f6; color:#fff; }
    .b2{ background:#22c55e; color:#000; }
    .card{ background:#111; border:1px solid #222; border-radius:12px; padding:14px; margin-bottom:12px; }
    .mini{ opacity:0.85; font-size:13px; margin-top:6px; }
    .pill{ display:inline-block; padding:4px 10px; border-radius:999px; font-weight:bold; font-size:12px; margin-left:8px; }
    .open{ background:#facc15; color:#000; }
    .resolved{ background:#22c55e; color:#000; }
    .del{ background:#ef4444; color:#fff; padding:8px 10px; border-radius:8px; text-decoration:none; font-weight:bold; }
    .act{ background:#334155; color:#fff; padding:8px 10px; border-radius:8px; text-decoration:none; font-weight:bold; margin-right:8px; }
    hr{ border-color:#222; }
  </style>
</head>
<body>
<div class="wrap">
  <?php require_once "nav.php"; ?>

  <h2>🛡 Admin Moderation Panel</h2>

  <div class="topbar">
    <a class="btn b1" href="pg-manage.php">🏠 Manage PGs</a>
    <a class="btn b2" href="add-pg.php">➕ Add PG</a>
    <a class="btn b1" href="../../frontend/dashboard.html">📊 Dashboard</a>
    <a class="btn b1" href="../../frontend/pg.html">PG Listing</a>
  </div>

  <h3>⭐ Latest PG Reviews</h3>
  <?php while($r = $reviews->fetch_assoc()) { ?>
    <div class="card">
      <div>
        <b><?php echo htmlspecialchars($r["pg_name"]); ?></b>
        <span class="pill open">⭐ <?php echo htmlspecialchars($r["rating"]); ?>/5</span>
      </div>

      <div class="mini">
        By: <?php echo htmlspecialchars($r["student_name"]); ?> |
        <?php echo htmlspecialchars($r["created_at"]); ?>
      </div>

      <div style="margin-top:8px;">
        <?php echo htmlspecialchars($r["review_text"] ?: "(no text)"); ?>
      </div>

      <div style="margin-top:10px;">
        <a class="del" href="../api/admin-review-delete.php?id=<?php echo $r["id"]; ?>"
           onclick="return confirm('Delete this review?');">Delete Review</a>
      </div>
    </div>
  <?php } ?>

  <hr>

  <h3>🚨 Latest PG Complaints</h3>
  <?php while($c = $complaints->fetch_assoc()) { ?>
    <div class="card">
      <div>
        <b><?php echo htmlspecialchars($c["pg_name"]); ?></b>
        <?php if($c["status"] === "Resolved") { ?>
          <span class="pill resolved">Resolved</span>
        <?php } else { ?>
          <span class="pill open">Open</span>
        <?php } ?>
        <span class="pill open">Confirms: <?php echo (int)$c["confirms"]; ?></span>
      </div>

      <div class="mini">
        Issue: <b><?php echo htmlspecialchars($c["issue"]); ?></b> |
        By: <?php echo htmlspecialchars($c["student_name"]); ?> |
        <?php echo htmlspecialchars($c["created_at"]); ?>
      </div>

      <div style="margin-top:8px;">
        <?php echo htmlspecialchars($c["message"] ?: "(no details)"); ?>
      </div>

      <div style="margin-top:10px;">
        <?php if($c["status"] === "Resolved") { ?>
          <a class="act" href="../api/admin-pg-complaint-action.php?action=open&id=<?php echo $c["id"]; ?>">Mark Open</a>
        <?php } else { ?>
          <a class="act" href="../api/admin-pg-complaint-action.php?action=resolve&id=<?php echo $c["id"]; ?>">Mark Resolved</a>
        <?php } ?>

        <a class="del" href="../api/admin-pg-complaint-action.php?action=delete&id=<?php echo $c["id"]; ?>"
           onclick="return confirm('Delete this complaint?');">Delete</a>
      </div>
    </div>
  <?php } ?>
</div>
</body>
</html>
<?php $conn->close(); ?>
