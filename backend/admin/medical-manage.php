<?php
require_once "auth.php";
include("../db.php");

$res = mysqli_query($conn,"SELECT * FROM medical ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Medical</title>
<style>
body{ background:#0f0f0f; color:#fff; font-family:Arial; }
.wrap{ max-width:1000px; margin:30px auto; }
.card{ background:#111; padding:14px; border-radius:12px; border:1px solid #222; margin-bottom:10px; }

.btn{
  padding:6px 10px;
  border-radius:6px;
  text-decoration:none;
  font-weight:bold;
}

.del{ background:#ef4444; color:#fff; }
</style>
</head>
<body>

<div class="wrap">
<?php require_once "nav.php"; ?>

<h2>🏥 Manage Medical Facilities</h2>

<?php while($row = mysqli_fetch_assoc($res)) { ?>

<div class="card">

<b><?php echo $row["name"]; ?></b> (<?php echo $row["type"]; ?>)<br>

📍 <?php echo $row["address"]; ?><br>
📞 <?php echo $row["phone"]; ?><br>

<a class="btn del" href="delete-medical.php?id=<?php echo $row["id"]; ?>">Delete</a>

</div>

<?php } ?>

</div>

</body>
</html>