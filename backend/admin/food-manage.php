<?php
require_once "auth.php";
include("../db.php");

$res = mysqli_query($conn, "SELECT * FROM food_places ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Food Manage</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .wrap{ max-width:1050px; margin:30px auto; padding:0 12px; }
    .card{ background:#111; border:1px solid #222; border-radius:14px; padding:16px; }
    table{ width:100%; border-collapse:collapse; margin-top:12px; }
    th,td{ border-bottom:1px solid #222; padding:10px; text-align:left; vertical-align:top; }
    th{ opacity:.9; }
    .btn{ display:inline-block; padding:8px 10px; border-radius:10px; text-decoration:none; font-weight:800; }
    .blue{ background:#3b82f6; color:#fff; }
    .green{ background:#22c55e; color:#000; }
    .red{ background:#ef4444; color:#fff; }
    img{ width:70px; height:50px; object-fit:cover; border-radius:10px; border:1px solid #222; }
    .muted{ opacity:.85; font-size:13px; }
  </style>
</head>
<body>
<div class="wrap">
  <?php require_once "nav.php"; ?>

  <div class="card">
    <div style="display:flex;justify-content:space-between;gap:10px;align-items:center;flex-wrap:wrap;">
      <div>
        <h2 style="margin:0;">🍱 Manage Food Places</h2>
        <div class="muted">Add / edit / delete vendors. Photos stored in <b>/uploads/food</b>.</div>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn green" href="add-food.php">➕ Add Food Place</a>
        <a class="btn blue" href="food-moderation.php">🛡 Moderation</a>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Photo</th>
          <th>Name</th>
          <th>Type</th>
          <th>Price</th>
          <th>Distance</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = mysqli_fetch_assoc($res)){ ?>
        <tr>
          <td>
            <?php if(!empty($row["photo"])): ?>
              <img src="../../uploads/food/<?php echo htmlspecialchars($row["photo"]); ?>" alt="">
            <?php else: ?>
              <span class="muted">No photo</span>
            <?php endif; ?>
          </td>
          <td>
            <b><?php echo htmlspecialchars($row["name"]); ?></b><br>
            <span class="muted"><?php echo htmlspecialchars($row["address"]); ?></span>
          </td>
          <td><?php echo htmlspecialchars($row["type"]); ?></td>
          <td>₹<?php echo intval($row["price"]); ?></td>
          <td><?php echo htmlspecialchars($row["distance"]); ?> km</td>
          <td><?php echo htmlspecialchars($row["phone"] ?? ""); ?></td>
          <td style="white-space:nowrap;">
            <a class="btn blue" href="edit-food.php?id=<?php echo $row["id"]; ?>">Edit</a>
            <a class="btn red" href="delete-food.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Delete this food place?');">Delete</a>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>