<?php
session_start();
include('db.php');
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: index.php"); exit(); }

$_SESSION['booking'] = [
    'car_id'        => $_POST['car_id'],
    'pickup_date'   => $_POST['pickup_date'],
    'return_date'   => $_POST['return_date'],
    'total_days'    => $_POST['total_days'],
    'total_price'   => $_POST['total_price'],
    'price_per_day' => $_POST['price_per_day'],
];

$car = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cars WHERE id='" . mysqli_real_escape_string($conn, $_POST['car_id']) . "'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; display: flex; justify-content: center; padding: 50px 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 420px; }
        h2 { color: #2c3e50; margin-bottom: 5px; }
        .subtitle { color: #7f8c8d; font-size: 0.9em; margin-bottom: 20px; }
        .summary { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .summary p { font-size: 0.9em; color: #555; margin: 4px 0; }
        .summary .total { font-size: 1.1em; font-weight: bold; color: #27ae60; margin-top: 8px; }
        .method { border: 2px solid #ddd; border-radius: 8px; padding: 14px 15px; cursor: pointer; display: flex; align-items: center; gap: 12px; margin-bottom: 10px; transition: all 0.2s; }
        .method:hover { border-color: #2c3e50; }
        .method.selected { border-color: #27ae60; background: #eafaf1; }
        .method input { accent-color: #27ae60; width: 18px; height: 18px; }
        .method label { cursor: pointer; font-weight: bold; color: #2c3e50; }
        .method span { font-size: 0.82em; color: #7f8c8d; display: block; }
        .upi-box { display: none; margin-bottom: 10px; }
        .upi-box input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95em; }
        .btn { width: 100%; padding: 13px; background: #27ae60; color: white; border: none; border-radius: 6px; font-size: 1em; font-weight: bold; cursor: pointer; margin-top: 5px; }
        .btn:hover { background: #219a52; }
        .back { display: block; text-align: center; margin-top: 12px; color: #888; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>
<div class="card">
    <h2>💳 Payment</h2>
    <p class="subtitle">Choose your payment method.</p>

    <div class="summary">
        <p>🚗 <strong><?php echo $car['brand']." ".$car['model']; ?></strong></p>
        <p>📅 <?php echo $_POST['pickup_date']; ?> → <?php echo $_POST['return_date']; ?> (<?php echo $_POST['total_days']; ?> days)</p>
        <p class="total">Total: ₹<?php echo number_format($_POST['total_price'], 2); ?></p>
    </div>

    <form action="confirm_booking.php" method="POST">
        <input type="hidden" name="payment_method" id="payment_method_input">

        <div class="method" id="m-cash" onclick="selectMethod('cash')">
            <input type="radio" name="method" value="cash" id="cash">
            <div>
                <label for="cash">💵 Cash on Delivery</label>
                <span>Pay when you pick up the car</span>
            </div>
        </div>

        <div class="method" id="m-upi" onclick="selectMethod('upi')">
            <input type="radio" name="method" value="upi" id="upi">
            <div>
                <label for="upi">📱 UPI</label>
                <span>Pay via UPI ID</span>
            </div>
        </div>

        <div class="upi-box" id="upi-box">
            <input type="text" id="upi_id" placeholder="Enter UPI ID (e.g. name@upi)">
        </div>

        <button type="submit" class="btn" onclick="return validate()">Pay & Confirm Booking</button>
    </form>
    <a href="javascript:history.back()" class="back">← Go back</a>
</div>

<script>
function selectMethod(m) {
    document.querySelectorAll('.method').forEach(x => x.classList.remove('selected'));
    document.getElementById('m-' + m).classList.add('selected');
    document.getElementById('payment_method_input').value = m;
    document.getElementById('upi-box').style.display = m === 'upi' ? 'block' : 'none';
    document.getElementById(m).checked = true;
}
function validate() {
    const m = document.getElementById('payment_method_input').value;
    if(!m) { alert('Please select a payment method!'); return false; }
    if(m === 'upi' && !document.getElementById('upi_id').value) { alert('Please enter UPI ID!'); return false; }
    return true;
}
</script>
</body>
</html>
