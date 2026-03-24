<?php
session_start();
include('db.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id    = $_SESSION['user_id'];
    $car_id     = mysqli_real_escape_string($conn, $_POST['car_id']);
    $pickup     = mysqli_real_escape_string($conn, $_POST['pickup_date']);
    $return     = mysqli_real_escape_string($conn, $_POST['return_date']);
    $total_days = mysqli_real_escape_string($conn, $_POST['total_days']);
    $total      = mysqli_real_escape_string($conn, $_POST['total_price']);

    $sql = "INSERT INTO bookings (user_id, car_id, pickup_date, return_date, total_days, total_price, status)
            VALUES ('$user_id', '$car_id', '$pickup', '$return', '$total_days', '$total', 'confirmed')";

    if(mysqli_query($conn, $sql)) {
        $booking_id = mysqli_insert_id($conn);
        header("Location: my_bookings.php?success=1&booking_id=" . $booking_id);
        exit();
    } else {
        echo "Booking error: " . mysqli_error($conn);
    }
} else {
    header("Location: login.php");
    exit();
}
?>
