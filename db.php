<?php
$username = "root";
$password = "";
$dbname = "car_rental_db";

$conn = mysqli_connect("localhost", $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
