<?php
session_start(); require 'db.php';
if(!isset($_SESSION['department'])) exit();

$stmt = $pdo->prepare("INSERT INTO slitting (sr_no,size,gross_weight,core_weight,net_weight,micron,meter,entry_date) VALUES (?,?,?,?,?,?,?,?)");

foreach($_POST['rows'] as $r){
    if(!empty($r['size']) && !empty($r['gross_weight'])){
        $gross = $r['gross_weight'];
        $core = $r['core_weight'] ?? 0;
        $net = $gross - $core;
        $meter = ($net>0 && $r['micron']>0 && $r['size']>0) ? $net/$r['micron']/0.00139/$r['size'] : 0;
        
        $stmt->execute([$r['sr_no'],$r['size'],$gross,$core,$net,$r['micron'],$meter,$r['entry_date']]);
    }
}
header("Location: dashboard.php?saved=1#slitting");
?>
