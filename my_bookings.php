<?php
session_start();
include('db.php');
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$uid = $_SESSION['user_id'];

// Handle cancellation
if(isset($_GET['cancel'])) {
    $bid = mysqli_real_escape_string($conn, $_GET['cancel']);
    mysqli_query($conn, "UPDATE bookings SET status='cancelled' WHERE id='$bid' AND user_id='$uid'");
    header("Location: my_bookings.php?msg=cancelled");
    exit();
}

$result = mysqli_query($conn, "SELECT bookings.*, cars.brand, cars.model FROM bookings JOIN cars ON bookings.car_id = cars.id WHERE bookings.user_id='$uid' ORDER BY bookings.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; }
        nav { background: #2c3e50; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        nav .brand { color: white; font-size: 1.3em; font-weight: bold; text-decoration: none; }
        nav a { color: white; text-decoration: none; margin-left: 15px; font-size: 0.95em; }
        nav a:hover { color: #f39c12; }
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #2c3e50; margin-bottom: 20px; }
        .msg { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        .msg.success { background: #eafaf1; color: #27ae60; border: 1px solid #27ae60; }
        .msg.cancelled { background: #fdf2f2; color: #e74c3c; border: 1px solid #e74c3c; }
        .card { background: white; border-radius: 10px; padding: 20px 25px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 5px solid #27ae60; }
        .card.cancelled-card { border-left-color: #e74c3c; opacity: 0.8; }
        .card h3 { color: #2c3e50; margin-bottom: 8px; }
        .card p { color: #555; font-size: 0.92em; margin: 4px 0; }
        .badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 0.8em; font-weight: bold; margin-top: 8px; }
        .badge.confirmed { background: #eafaf1; color: #27ae60; }
        .badge.cancelled { background: #fdf2f2; color: #e74c3c; }
        .badge.pending { background: #fef9e7; color: #f39c12; }
        .cancel-btn { float: right; background: #e74c3c; color: white; border: none; padding: 7px 15px; border-radius: 6px; cursor: pointer; font-size: 0.85em; text-decoration: none; }
        .cancel-btn:hover { background: #c0392b; }
        .empty { text-align: center; padding: 60px; color: #888; }
        .empty a { color: #e67e22; text-decoration: none; }
    </style>
</head>
<body>
<nav>
    <a href="index.php" class="brand">🚗 CarRental Pro</a>
    <div>
        <a href="index.php">Home</a>
        <a href="logout.php" style="color:#ff7675;">Logout</a>
    </div>
</nav>

<div class="container">
    <h1>📋 My Bookings</h1>

    <?php if(isset($_GET['success'])): ?>
        <div class="msg success">✅ Booking confirmed successfully!</div>
    <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'cancelled'): ?>
        <div class="msg cancelled">❌ Booking cancelled.</div>
    <?php endif; ?>

    <?php if(mysqli_num_rows($result) > 0):
        while($row = mysqli_fetch_assoc($result)):
        $isCancelled = $row['status'] == 'cancelled';
    ?>
    <div class="card <?php echo $isCancelled ? 'cancelled-card' : ''; ?>">
        <?php if(!$isCancelled): ?>
            <a href="my_bookings.php?cancel=<?php echo $row['id']; ?>" class="cancel-btn" onclick="return confirm('Cancel this booking?')">Cancel</a>
        <?php endif; ?>
        <h3><?php echo $row['brand']." ".$row['model']; ?></h3>
        <p>📅 <?php echo $row['pickup_date']; ?> → <?php echo $row['return_date']; ?> (<?php echo $row['total_days']; ?> days)</p>
        <p>💰 Total: <strong>₹<?php echo number_format($row['total_price'], 2); ?></strong></p>
        <span class="badge <?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span>
        <p style="color:#aaa; font-size:0.8em; margin-top:8px;">Booked on: <?php echo $row['created_at']; ?></p>
    </div>
    <?php endwhile; else: ?>
        <div class="empty">
            <p>No bookings yet.</p><br>
            <a href="index.php">Browse cars →</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>