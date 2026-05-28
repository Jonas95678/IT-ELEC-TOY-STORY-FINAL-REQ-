<?php
session_start();
error_reporting(0); ini_set('display_errors','0');
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    require_once "conn.php";
    $q = mysqli_query($conn, "SELECT title FROM `$tablemovies` WHERE id=$id");
    $r = $q ? mysqli_fetch_assoc($q) : null;
    mysqli_query($conn, "DELETE FROM `$tablemovies` WHERE id=$id");
    $_SESSION['status'] = '"' . htmlspecialchars($r['title'] ?? 'Movie', ENT_QUOTES, 'UTF-8') . '" deleted.';
}
header("Location: dashboard.php#movies-section"); exit();
