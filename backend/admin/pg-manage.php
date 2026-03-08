<?php
$conn = new mysqli("localhost", "root", "", "sss");
if ($conn->connect_error) die("DB Error");
$res = $conn->query("SELECT id, name, price, distance, type, phone, photo FROM pg_rooms ORDER BY id DESC");
?>
<?php require_once "auth.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage PG</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .wrap{ max-width:900px; margin:30px auto; }
    .topbar{ display:flex; gap:10px; align-items:center; margin-bottom:15px; }
    a.btn{
      display:inline-block; padding:10px 12px; border-radius:10px;
      text-decoration:none; font-weight:bold;
    }
    .add{ background:#22c55e; color:#000; }
    .dash{ background:#3b82f6; color:#fff; }
    table{ width:100%; border-collapse:collapse; background:#111; border-radius:12px; overflow:hidden; }
    th, td{ padding:12px; border-bottom:1px solid #222; text-align:left; }
    th{ background:#1a1a1a; }
    img{ width:60px; height:40px; object-fit:cover; border-radius:6px; }
    .action a{ margin-right:8px; }
    .edit{ background:#facc15; color:#000; padding:8px 10px; border-radius:8px; }
    .del{ background:#ef4444; color:#fff; padding:8px 10px; border-radius:8px; }
  </style>
</head>
<body>
<div class="wrap">
  <?php require_once "nav.php"; ?>

  <h2>🧑‍💼 Admin Panel - Manage PGs</h2>

  <div class="topbar">
    <a class="btn add" href="add-pg.php">➕ Add PG</a>
    <a class="btn dash" href="../../frontend/dashboard.html">📊 Dashboard</a>
    <a class="btn dash" href="../../frontend/pg.html">🏠 PG List</a>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Photo</th>
      <th>Name</th>
      <th>Price</th>
      <th>Distance</th>
      <th>Type</th>
      <th>Phone</th>
      <th>Actions</th>
    </tr>

    <?php while($row = $res->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row["id"]; ?></td>
        <td>
          <?php if(!empty($row["photo"])) { ?>
            <img src="../../uploads/pg/<?php echo htmlspecialchars($row["photo"]); ?>" alt="photo">
          <?php } else { echo "-"; } ?>
        </td>
        <td><?php echo htmlspecialchars($row["name"]); ?></td>
        <td>₹<?php echo htmlspecialchars($row["price"]); ?></td>
        <td><?php echo htmlspecialchars($row["distance"]); ?> km</td>
        <td><?php echo htmlspecialchars($row["type"]); ?></td>
        <td><?php echo htmlspecialchars($row["phone"]); ?></td>
        <td class="action">
          <a class="edit" href="edit-pg.php?id=<?php echo $row["id"]; ?>">✏ Edit</a>
          <a class="del" href="../api/admin-delete-pg.php?id=<?php echo $row["id"]; ?>"
             onclick="return confirm('Delete this PG?');">🗑 Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>
</div>
</body>
</html>
<?php $conn->close(); ?>
