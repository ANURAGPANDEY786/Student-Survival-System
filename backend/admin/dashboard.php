<?php
require_once "auth.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body{ background:#0f0f0f; color:#fff; font-family:Arial; }
    .wrap{ max-width:1000px; margin:30px auto; padding:0 12px; }
    .card{ background:#111; border:1px solid #222; border-radius:14px; padding:16px; }
    .grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:12px; }
    .mod{ background:#111; border:1px solid #222; border-radius:14px; padding:14px; cursor:pointer; transition:.15s; }
    .mod:hover{ transform:translateY(-2px); border-color:#333; }
    .tag{ display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:800; }
    .live{ background:#22c55e; color:#000; }
    .soon{ background:#334155; color:#fff; opacity:.9; }
    a{ color:inherit; text-decoration:none; }
    @media(max-width:900px){ .grid{ grid-template-columns:1fr; } }
  </style>
</head>
<body>
<div class="wrap">
  <?php require_once "nav.php"; ?>

  <div class="card">
    <h2 style="margin:0;">🧑‍💼 Admin Control Center</h2>
    <div style="opacity:.85; margin-top:8px;">
      Manage all modules from one place. (Single admin login)
    </div>
  </div>

  <div class="grid">

    <a href="pg-manage.php">
      <div class="mod">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <b>🏠 PG / Rooms</b><span class="tag live">LIVE</span>
        </div>
        <div style="opacity:.85;margin-top:8px;">Add / edit / delete PGs, upload photos.</div>
      </div>
    </a>

    <a href="moderation.php">
      <div class="mod">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <b>🛡 Moderation</b><span class="tag live">LIVE</span>
        </div>
        <div style="opacity:.85;margin-top:8px;">Delete fake reviews, resolve complaints.</div>
      </div>
    </a>

    <a href="../../frontend/dashboard.html">
      <div class="mod">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <b>📊 Student Dashboard</b><span class="tag live">LIVE</span>
        </div>
        <div style="opacity:.85;margin-top:8px;">See what students see (for testing).</div>
      </div>
    </a>

    <a href="../../frontend/pg.html">
  <div class="mod">
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <b>🏠 Student PG Listing</b><span class="tag live">LIVE</span>
    </div>
    <div style="opacity:.85;margin-top:8px;">Open student PG module (testing view).</div>
  </div>
</a>


    <a href="food-manage.php">
  <div class="mod">
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <b>🍱 Food Admin</b><span class="tag live">LIVE</span>
    </div>
    <div style="opacity:.85;margin-top:8px;">Add / edit / delete food places, upload photos.</div>
  </div>
</a>

    <a href="food-moderation.php">
  <div class="mod">
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <b>🛡 Food Moderation</b><span class="tag live">LIVE</span>
    </div>
    <div style="opacity:.85;margin-top:8px;">Delete fake reviews, resolve food issues.</div>
  </div>
</a>
    

    <div class="mod" onclick="alert('Medical module admin will be added next');">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <b>🏥 Medical Admin</b><span class="tag soon">NEXT</span>
      </div>
      <div style="opacity:.85;margin-top:8px;">Manage hospitals, pharmacies, emergency contacts.</div>
    </div>

    <div class="mod" onclick="alert('Expense module admin will be added next');">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <b>💰 Expense Admin</b><span class="tag soon">NEXT</span>
      </div>
      <div style="opacity:.85;margin-top:8px;">Manage categories, limits, reports (optional).</div>
    </div>

  </div>
</div>
</body>
</html>
