<?php
session_start();
include('db.php');

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$uid = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - CarRental Pro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 40px 20px; }
        h1 { color: #2c3e50; margin-bottom: 5px; }
        .back { color: #e67e22; text-decoration: none; font-size: 0.95em; }
        hr { margin: 15px 0 25px 0; border: none; border-top: 1px solid #ddd; }
        .success-msg { background: #eafaf1; border: 1px solid #27ae60; color: #27ae60; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .receipt { background: white; padding: 20px 25px; border-radius: 10px; margin-bottom: 15px; border-left: 5px solid #27ae60; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .receipt h3 { color: #2c3e50; margin-bottom: 10px; }
        .receipt p { color: #555; margin: 5px 0; font-size: 0.95em; }
        .status { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 0.85em; font-weight: bold; background: #eafaf1; color: #27ae60; }
        .date { color: #95a5a6; font-size: 0.82em; margin-top: 8px; }
        .empty { text-align: center; color: #888; margin-top: 50px; font-size: 1.1em; }
        .empty a { color: #e67e22; text-decoration: none; }
    </style>
</head>
<body>
    <h1>📋 Your Rental History</h1>
    <a href="index.php" class="back">← Back to Cars</a>
    <hr>

    <?php if(isset($_GET['success'])): ?>
        <div class="success-msg">✅ Booking confirmed successfully! Your car is reserved.</div>
    <?php endif; ?>

    <?php
    $sql = "SELECT bookings.*, cars.brand, cars.model, cars.category
            FROM bookings
            JOIN cars ON bookings.car_id = cars.id
            WHERE bookings.user_id = '$uid'
            ORDER BY bookings.created_at DESC";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<div class='receipt'>";
            echo "<h3>" . htmlspecialchars($row['brand']) . " " . htmlspecialchars($row['model']) . " <small style='color:#7f8c8d;font-weight:normal;'>(" . $row['category'] . ")</small></h3>";
            echo "<p>📅 Pickup: <strong>" . $row['pickup_date'] . "</strong> &nbsp;→&nbsp; Return: <strong>" . $row['return_date'] . "</strong></p>";
            echo "<p>🕐 Duration: <strong>" . $row['total_days'] . " day(s)</strong> &nbsp;|&nbsp; 💰 Total Paid: <strong>₹" . number_format($row['total_price'], 2) . "</strong></p>";
            echo "<p><span class='status'>" . ucfirst($row['status']) . "</span></p>";
            echo "<p class='date'>Booked on: " . $row['created_at'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='empty'><p>You haven't made any bookings yet.</p><br><a href='index.php'>Browse available cars →</a></div>";
    }
    ?>
</body>
</html>
