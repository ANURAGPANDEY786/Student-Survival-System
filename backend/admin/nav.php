<?php
// nav.php (session already started in auth.php)
?>
<div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px; align-items:center;">
  <a href="dashboard.php" style="background:#334155;color:#fff;padding:10px 12px;border-radius:10px;text-decoration:none;font-weight:bold;">🏠 Admin Home</a>

  <a href="pg-manage.php" style="background:#3b82f6;color:#fff;padding:10px 12px;border-radius:10px;text-decoration:none;font-weight:bold;">🏠 Manage PGs</a>
  <a href="add-pg.php" style="background:#22c55e;color:#000;padding:10px 12px;border-radius:10px;text-decoration:none;font-weight:bold;">➕ Add PG</a>
  <a href="moderation.php" style="background:#facc15;color:#000;padding:10px 12px;border-radius:10px;text-decoration:none;font-weight:bold;">🛡 Moderation</a>
<a href="medical-manage.php" style="background:#3b82f6;color:#fff;padding:10px;border-radius:10px;text-decoration:none;">🏥 Medical</a>

<a href="add-medical.php" style="background:#22c55e;color:#000;padding:10px;border-radius:10px;text-decoration:none;">➕ Add Medical</a>
  <div style="margin-left:auto; display:flex; gap:10px; align-items:center;">
    <span style="opacity:0.85;">👤 <?php echo htmlspecialchars($_SESSION["admin_username"] ?? "admin"); ?></span>
    <a href="logout.php" style="background:#ef4444;color:#fff;padding:10px 12px;border-radius:10px;text-decoration:none;font-weight:bold;">Logout</a>
  </div>
</div>
