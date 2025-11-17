<?php require 'db.php'; ?>
<table>
    <tr><th>Sr</th><th>Size</th><th>Gross</th><th>Core</th><th>Net</th><th>Micron</th><th>Meter</th><th>Date</th></tr>
    <?php
    $res = $pdo->query("SELECT * FROM slitting ORDER BY id DESC LIMIT 200");
    while($row = $res->fetch()){
        echo "<tr><td>{$row['sr_no']}</td><td>{$row['size']}</td><td>{$row['gross_weight']}</td><td>{$row['core_weight']}</td><td>{$row['net_weight']}</td><td>{$row['micron']}</td><td>".number_format($row['meter'],2)."</td><td>{$row['entry_date']}</td></tr>";
    }
    ?>
</table>
