<?php
session_start();
error_reporting(0);
ini_set('display_errors','0');
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "conn.php";

    // form field is "char_name" to avoid HTML reserved-name conflicts
    $name         = mysqli_real_escape_string($conn, trim($_POST['char_name']   ?? ''));
    $role         = mysqli_real_escape_string($conn, trim($_POST['role']        ?? ''));
    $quote        = mysqli_real_escape_string($conn, trim($_POST['quote']       ?? ''));
    $description  = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $avatar_url   = mysqli_real_escape_string($conn, trim($_POST['avatar_url']  ?? 'img/woody.jpg'));
    $css_class    = mysqli_real_escape_string($conn, trim($_POST['css_class']   ?? 'woody'));
    $is_displayed = isset($_POST['is_displayed']) ? (int)$_POST['is_displayed'] : 1;

    if (empty($name)) {
        $_SESSION['status']      = "Character name cannot be empty.";
        $_SESSION['status_type'] = 'error';
        header("Location: dashboard.php#characters-section");
        exit();
    }

    $sql = "INSERT INTO `$tablechar`
                (`name`, role, quote, description, avatar_url, css_class, is_displayed)
            VALUES
                ('$name','$role','$quote','$description','$avatar_url','$css_class',$is_displayed)";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['status'] = "Character \"$name\" added successfully!";
    } else {
        $_SESSION['status']      = "Error adding character: " . mysqli_error($conn);
        $_SESSION['status_type'] = 'error';
    }
    header("Location: dashboard.php#characters-section");
    exit();
}
header("Location: dashboard.php");
exit();
