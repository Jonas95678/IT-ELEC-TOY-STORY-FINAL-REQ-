<?php
session_start();
error_reporting(0); ini_set('display_errors','0');
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    require_once "conn.php";
    mysqli_query($conn, "UPDATE `$tablechar` SET is_displayed = 1 - is_displayed WHERE id=$id");
    $q = mysqli_query($conn, "SELECT `name`, is_displayed FROM `$tablechar` WHERE id=$id");
    $r = $q ? mysqli_fetch_assoc($q) : null;
    $state = ($r && $r['is_displayed']) ? 'now shown on website' : 'now hidden from website';
    $_SESSION['status'] = '"' . htmlspecialchars($r['name'] ?? '', ENT_QUOTES, 'UTF-8') . "\" is $state.";
}
header("Location: dashboard.php#characters-section"); exit();
