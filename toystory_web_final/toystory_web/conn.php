<?php
$server      = "localhost";
$username    = "root";
$password    = "";
$dbname      = "toystory_db";
$tablemovies = "tbl_movies";
$tablechar   = "tbl_characters";
$tablelogin  = "tblusers";

// Connection
$conn = mysqli_connect($server, $username, $password, $dbname)
    or die("Cannot connect to database");

if (mysqli_connect_error()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
