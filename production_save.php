<?php
session_start(); require 'db.php';
if(!isset($_SESSION['department'])) exit();

$stmt = $pdo->prepare("INSERT INTO production (job_no,job_date,job_code,size,coil1,coil2,total_weight,total_rolls,per_roll_meter) VALUES (?,?,?,?,?,?,?,?,?)");
$stmt->execute([$_POST['job_no'],$_POST['job_date'],$_POST['job_code'],$_POST['size'],$_POST['coil1'],$_POST['coil2'],$_POST['total_weight'],$_POST['total_rolls'],$_POST['per_roll_meter']]);

header("Location: dashboard.php");
?>
