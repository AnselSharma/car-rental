<?php
session_start();
include('db.php');

if(!isset($_SESSION['user_id']) || !isset($_SESSION['booking'])) {
    header("Location: index.php");
    exit();
}

$uid     = $_SESSION['user_id'];
$b       = $_SESSION['booking'];
$method  = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? 'cash');

$car_id      = mysqli_real_escape_string($conn, $b['car_id']);
$pickup      = mysqli_real_escape_string($conn, $b['pickup_date']);
$return      = mysqli_real_escape_string($conn, $b['return_date']);
$total_days  = mysqli_real_escape_string($conn, $b['total_days']);
$total_price = mysqli_real_escape_string($conn, $b['total_price']);

$sql = "INSERT INTO bookings (user_id, car_id, pickup_date, return_date, total_days, total_price, status)
        VALUES ('$uid', '$car_id', '$pickup', '$return', '$total_days', '$total_price', 'confirmed')";

if(mysqli_query($conn, $sql)) {
    $bid = mysqli_insert_id($conn);
    mysqli_query($conn, "INSERT INTO payments (booking_id, user_id, amount, payment_method, payment_status)
        VALUES ('$bid', '$uid', '$total_price', '$method', 'paid')");
    unset($_SESSION['booking']);
    header("Location: my_bookings.php?success=1");
    exit();
} else {
    echo "Booking error: " . mysqli_error($conn);
}
?>