<?php
session_start();
include('db.php');

if(!isset($_GET['car_id'])) {
    header("Location: index.php");
    exit();
}

$car_id = mysqli_real_escape_string($conn, $_GET['car_id']);
$result = mysqli_query($conn, "SELECT * FROM cars WHERE id = '$car_id'");
$car = mysqli_fetch_assoc($result);

if(!$car) {
    die("Car not found!");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - <?php echo $car['brand']; ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding: 50px 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 420px; }
        h2 { color: #2c3e50; margin-bottom: 5px; }
        .subtitle { color: #7f8c8d; margin-bottom: 20px; font-size: 0.9em; }
        .car-info { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .car-info h3 { color: #2c3e50; margin-bottom: 5px; }
        .car-info p { color: #7f8c8d; font-size: 0.9em; margin: 3px 0; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #eee; }
        label { display: block; font-weight: bold; color: #2c3e50; margin-top: 15px; margin-bottom: 5px; }
        input[type=date] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1em; }
        .total-box { background: #eafaf1; padding: 15px; border-radius: 8px; margin-top: 15px; display: none; }
        .total { display: flex; justify-content: space-between; font-size: 1.2em; font-weight: bold; color: #27ae60; margin-top: 10px; }
        .btn { display: block; width: 100%; padding: 13px; margin-top: 20px; background: #27ae60; color: white; border: none; border-radius: 6px; font-size: 1em; font-weight: bold; cursor: pointer; text-align: center; }
        .btn:hover { background: #219a52; }
        .back { display: block; text-align: center; margin-top: 12px; color: #888; text-decoration: none; font-size: 0.9em; }
        .login-msg { color: red; text-align: center; margin-top: 20px; }
        a { color: #e67e22; }
    </style>
</head>
<body>
<div class="card">
    <h2>Booking Summary</h2>
    <p class="subtitle">Select your dates to see the total price.</p>

    <div class="car-info">
        <h3><?php echo htmlspecialchars($car['brand']) . " " . htmlspecialchars($car['model']); ?> (<?php echo $car['year']; ?>)</h3>
        <p><?php echo $car['category']; ?> &nbsp;|&nbsp; <?php echo $car['fuel_type']; ?> &nbsp;|&nbsp; <?php echo $car['transmission']; ?> &nbsp;|&nbsp; <?php echo $car['seats']; ?> Seats</p>
    </div>

    <div class="row"><span>Price per day:</span><strong>₹<?php echo number_format($car['price_per_day'], 2); ?></strong></div>
    <div class="row"><span>Security Deposit:</span><strong>₹1,000.00</strong></div>

    <?php if(isset($_SESSION['user_id'])): ?>
    <form action="confirm_booking.php" method="POST">
        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
        <input type="hidden" name="price_per_day" value="<?php echo $car['price_per_day']; ?>">

        <label>Pickup Date</label>
        <input type="date" name="pickup_date" id="pickup" required min="<?php echo date('Y-m-d'); ?>">

        <label>Return Date</label>
        <input type="date" name="return_date" id="return" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">

        <div class="total-box" id="total-box">
            <div class="row"><span>Number of Days:</span><strong id="days"></strong></div>
            <div class="row"><span>Rental Cost:</span><strong id="rental-cost"></strong></div>
            <div class="row"><span>Security Deposit:</span><strong>₹1,000</strong></div>
            <div class="total"><span>Total Amount:</span><span id="total-amount"></span></div>
            <input type="hidden" name="total_days" id="total_days">
            <input type="hidden" name="total_price" id="total_price">
        </div>

        <button type="submit" class="btn">Confirm Booking</button>
    </form>
    <?php else: ?>
        <p class="login-msg">Please <a href="login.php">Login</a> to book this car.</p>
    <?php endif; ?>

    <a href="index.php" class="back">← Back to cars</a>
</div>

<script>
const rate = <?php echo $car['price_per_day']; ?>;
document.getElementById('pickup').addEventListener('change', calc);
document.getElementById('return').addEventListener('change', calc);

function calc() {
    const p = document.getElementById('pickup').value;
    const r = document.getElementById('return').value;
    if(p && r) {
        const days = Math.ceil((new Date(r) - new Date(p)) / 86400000);
        if(days <= 0) { alert('Return date must be after pickup date!'); document.getElementById('return').value = ''; return; }
        const rental = days * rate;
        const total = rental + 1000;
        document.getElementById('days').textContent = days + ' day(s)';
        document.getElementById('rental-cost').textContent = '₹' + rental.toLocaleString('en-IN');
        document.getElementById('total-amount').textContent = '₹' + total.toLocaleString('en-IN');
        document.getElementById('total_days').value = days;
        document.getElementById('total_price').value = total;
        document.getElementById('total-box').style.display = 'block';
    }
}
</script>
</body>
</html>
