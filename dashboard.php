<?php
session_start();
if(!isset($_SESSION['department'])){
    header("Location: index.php");
    exit();
}
require 'db.php';
$dept = $_SESSION['department'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $dept ?> Dashboard</title>
    <style>
        body {font-family: Arial; background:#f0f2f5; margin:0;}
        .header {background:#1a5276; color:white; padding:15px; text-align:center; position:relative;}
        .logout {position:absolute; right:15px; top:15px; background:#e74c3c; padding:8px 15px; border-radius:5px; text-decoration:none; color:white;}
        .tabs {overflow-x:auto; white-space:nowrap; background:#2c3e50; padding:10px 0;}
        .tab-btn {display:inline-block; padding:12px 20px; background:#34495e; color:white; margin:0 5px; border-radius:8px 8px 0 0;}
        .tab-btn.active {background:#27ae60;}
        .form-container {padding:20px; background:white; margin:10px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);}
        table {width:100%; border-collapse:collapse; font-size:14px;}
        th {background:#1a5276; color:white; padding:12px 8px; position:sticky; top:0;}
        td {padding:8px; text-align:center;}
        input {width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;}
        input[readonly] {background:#f8fff8; font-weight:bold;}
        .net-input {color:#27ae60;}
        .meter-input {color:#2980b9;}
        button {padding:15px; background:#27ae60; color:white; border:none; border-radius:8px; width:100%; font-size:16px; margin-top:20px;}
    </style>
</head>
<body>
<div class="header">
    <h2><?= $dept ?> Department</h2>
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="tabs">
    <?php if($dept == 'Production' || $dept == 'Admin'): ?>
        <button class="tab-btn active" onclick="show('production')">Production</button>
    <?php endif; ?>
    <?php if($dept == 'Slitting' || $dept == 'Admin'): ?>
        <button class="tab-btn" onclick="show('slitting')">Slitting Entry</button>
    <?php endif; ?>
    <?php if($dept == 'Admin'): ?>
        <button class="tab-btn" onclick="show('admin')">View All Data</button>
    <?php endif; ?>
</div>

<!-- PRODUCTION FORM -->
<?php if($dept == 'Production' || $dept == 'Admin'): ?>
<div id="production" class="form-container">
    <h3>Production Entry</h3>
    <form action="production_save.php" method="POST">
        <input type="text" name="job_no" placeholder="Job No" required>
        <input type="date" name="job_date" required>
        <input type="text" name="job_code" placeholder="Job Code" required>
        <input type="text" name="size" placeholder="Size" required>
        <input type="number" step="0.01" name="coil1" id="c1" oninput="calc()" placeholder="Coil 1">
        <input type="number" step="0.01" name="coil2" id="c2" oninput="calc()" placeholder="Coil 2">
        <input type="number" step="0.01" name="total_weight" id="total" readonly placeholder="Total Weight">
        <input type="number" name="total_rolls" placeholder="Total Rolls">
        <input type="number" step="0.01" name="per_roll_meter" placeholder="Per Roll Meter">
        <button type="submit">Save Production</button>
    </form>
</div>
<?php endif; ?>

<!-- EXCEL STYLE SLITTING ENTRY (50 ROWS) -->
<?php if($dept == 'Slitting' || $dept == 'Admin'): ?>
<div id="slitting" class="form-container" style="display:none;">
    <h3 style="text-align:center; color:#1a5276;">SLITTING DAILY ENTRY</h3>
    <form action="slitting_save_batch.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Sr</th><th>Size</th><th>Gross</th><th>Core</th><th>Net</th><th>Micron</th><th>Meter</th><th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i=1; $i<=50; $i++): ?>
                <tr>
                    <td><input type="number" name="rows[<?= $i ?>][sr_no]" value="<?= $i ?>" readonly style="width:50px;"></td>
                    <td><input type="text" name="rows[<?= $i ?>][size]" class="size"></td>
                    <td><input type="number" step="0.01" name="rows[<?= $i ?>][gross_weight]" class="gross" oninput="calcRow(this)"></td>
                    <td><input type="number" step="0.01" name="rows[<?= $i ?>][core_weight]" class="core" oninput="calcRow(this)"></td>
                    <td><input type="text" name="rows[<?= $i ?>][net_weight]" class="net" readonly></td>
                    <td><input type="number" name="rows[<?= $i ?>][micron]" class="micron" oninput="calcRow(this)"></td>
                    <td><input type="text" name="rows[<?= $i ?>][meter]" class="meter" readonly></td>
                    <td><input type="date" name="rows[<?= $i ?>][entry_date]" value="<?= date('Y-m-d') ?>"></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <button type="submit">SAVE ALL FILLED ROWS</button>
    </form>
</div>
<?php endif; ?>

<!-- ADMIN VIEW -->
<?php if($dept == 'Admin'): ?>
<div id="admin" class="form-container" style="display:none;">
    <h3>All Slitting Data</h3>
    <?php include 'admin_view.php'; ?>
</div>
<?php endif; ?>

<script>
function calc(){ let a=parseFloat(document.getElementById('c1').value)||0; let b=parseFloat(document.getElementById('c2').value)||0; document.getElementById('total').value=(a+b).toFixed(2); }

function calcRow(el) {
    const row = el.closest('tr');
    const gross = parseFloat(row.querySelector('.gross').value)||0;
    const core = parseFloat(row.querySelector('.core').value)||0;
    const net = gross - core;
    row.querySelector('.net').value = net.toFixed(2);
    
    const size = parseFloat(row.querySelector('.size').value)||0;
    const micron = parseFloat(row.querySelector('.micron').value)||0;
    if(net>0 && micron>0 && size>0){
        const meter = net / micron / 0.00139 / size;
        row.querySelector('.meter').value = meter.toFixed(2);
    }
}

function show(id) {
    document.querySelectorAll('.form-container').forEach(d=>d.style.display='none');
    document.getElementById(id).style.display='block';
    document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
    event.target.classList.add('active');
}
</script>
</body>
</html>
