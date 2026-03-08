<?php
require_once "auth.php";
include("../db.php");

if(isset($_GET["del_review"])){
  $rid = intval($_GET["del_review"]);
  mysqli_query($conn, "DELETE FROM food_reviews WHERE id=$rid");
  header("Location: food-moderation.php");
  exit;
}

if(isset($_GET["resolve"])){
  $cid = intval($_GET["resolve"]);
  mysqli_query($conn, "UPDATE food_complaints SET severity='Resolved' WHERE id=$cid");
  header("Location: food-moderation.php");
  exit;
}

$reviews = mysqli_query($conn, "
SELECT fr.id, fr.food_id, fr.rating, fr.review_text, fr.created_at,
       fp.name as food_name
FROM food_reviews fr
LEFT JOIN food_places fp ON fp.id = fr.food_id
ORDER BY fr.id DESC
LIMIT 80
");

$complaints = mysqli_query($conn, "
SELECT fc.id, fc.food_id, fc.issue, fc.message, fc.confirms, fc.severity, fc.created_at,
       fp.name as food_name
FROM food_complaints fc
LEFT JOIN food_places fp ON fp.id = fc.food_id
ORDER BY fc.id DESC
LIMIT 80
");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Food Moderation</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .wrap{ max-width:1100px; margin:30px auto; padding:0 12px; }
    .card{ background:#111; border:1px solid #222; border-radius:14px; padding:16px; margin-bottom:14px; }
    .muted{ opacity:.85; font-size:13px; }
    table{ width:100%; border-collapse:collapse; margin-top:10px; }
    th,td{ border-bottom:1px solid #222; padding:10px; text-align:left; vertical-align:top; }
    .btn{ display:inline-block; padding:8px 10px; border-radius:10px; text-decoration:none; font-weight:800; }
    .red{ background:#ef4444; color:#fff; }
    .green{ background:#22c55e; color:#000; }
    .pill{ padding:4px 10px; border-radius:999px; font-size:12px; font-weight:900; }
    .pending{ background:#334155; color:#fff; }
    .warning{ background:#facc15; color:#000; }
    .critical{ background:#ef4444; color:#fff; }
    .resolved{ background:#22c55e; color:#000; }
  </style>
</head>
<body>
<div class="wrap">
  <?php require_once "nav.php"; ?>

  <div class="card">
    <h2 style="margin:0;">🛡 Food Moderation</h2>
    <div class="muted">Delete fake reviews and resolve food issues.</div>
  </div>

  <div class="card">
    <h3 style="margin:0;">⭐ Recent Food Reviews</h3>
    <table>
      <thead>
        <tr>
          <th>Food</th>
          <th>Rating</th>
          <th>Review</th>
          <th>Time</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php while($r = mysqli_fetch_assoc($reviews)){ ?>
        <tr>
          <td><b><?php echo htmlspecialchars($r["food_name"] ?? ""); ?></b> (#<?php echo intval($r["food_id"]); ?>)</td>
          <td>⭐ <?php echo intval($r["rating"]); ?></td>
          <td><?php echo htmlspecialchars($r["review_text"] ?? ""); ?></td>
          <td class="muted"><?php echo htmlspecialchars($r["created_at"]); ?></td>
          <td>
            <a class="btn red" href="food-moderation.php?del_review=<?php echo intval($r["id"]); ?>"
               onclick="return confirm('Delete this review?');">Delete</a>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h3 style="margin:0;">🚨 Recent Food Issues</h3>
    <table>
      <thead>
        <tr>
          <th>Food</th>
          <th>Issue</th>
          <th>Message</th>
          <th>Confirms</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php while($c = mysqli_fetch_assoc($complaints)){ 
        $sev = $c["severity"] ?? "Pending";
        $cls = "pending";
        if($sev==="Warning") $cls="warning";
        else if($sev==="Critical") $cls="critical";
        else if($sev==="Resolved") $cls="resolved";
      ?>
        <tr>
          <td><b><?php echo htmlspecialchars($c["food_name"] ?? ""); ?></b> (#<?php echo intval($c["food_id"]); ?>)</td>
          <td><?php echo htmlspecialchars($c["issue"] ?? ""); ?></td>
          <td><?php echo htmlspecialchars($c["message"] ?? ""); ?></td>
          <td><?php echo intval($c["confirms"]); ?></td>
          <td><span class="pill <?php echo $cls; ?>"><?php echo htmlspecialchars($sev); ?></span></td>
          <td>
            <?php if($sev!=="Resolved"){ ?>
              <a class="btn green" href="food-moderation.php?resolve=<?php echo intval($c["id"]); ?>"
                 onclick="return confirm('Mark as resolved?');">Resolve</a>
            <?php } else { ?>
              <span class="muted">Done</span>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>