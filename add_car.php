<?php
session_start();
include('db.php');

$msg = "";

if(isset($_POST['add_btn'])) {
    $brand    = mysqli_real_escape_string($conn, $_POST['brand']);
    $model    = mysqli_real_escape_string($conn, $_POST['model']);
    $year     = mysqli_real_escape_string($conn, $_POST['year']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $fuel     = mysqli_real_escape_string($conn, $_POST['fuel_type']);
    $trans    = mysqli_real_escape_string($conn, $_POST['transmission']);
    $seats    = mysqli_real_escape_string($conn, $_POST['seats']);
    $price    = mysqli_real_escape_string($conn, $_POST['price_per_day']);
    $desc     = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO cars (brand, model, year, category, fuel_type, transmission, seats, price_per_day, description, is_available)
            VALUES ('$brand', '$model', '$year', '$category', '$fuel', '$trans', '$seats', '$price', '$desc', 1)";

    if(mysqli_query($conn, $sql)) {
        $msg = "<p style='color:green; font-weight:bold;'>✅ Car added successfully! <a href='index.php'>View Gallery</a></p>";
    } else {
        $msg = "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Car - Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding: 40px 20px; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 420px; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        label { display: block; font-weight: bold; color: #2c3e50; margin: 12px 0 4px; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 1em; }
        textarea { resize: vertical; height: 80px; }
        button { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1em; font-weight: bold; margin-top: 20px; }
        button:hover { background: #219a52; }
        .back { display: block; text-align: center; margin-top: 12px; color: #888; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>🚗 Add New Car</h2>
    <?php echo $msg; ?>
    <form method="POST">
        <label>Brand</label>
        <input type="text" name="brand" placeholder="e.g. Toyota" required>

        <label>Model</label>
        <input type="text" name="model" placeholder="e.g. Camry" required>

        <label>Year</label>
        <input type="number" name="year" placeholder="e.g. 2023" min="2000" max="2030" required>

        <label>Category</label>
        <select name="category">
            <option>Sedan</option>
            <option>SUV</option>
            <option>Hatchback</option>
            <option>Luxury</option>
            <option>Electric</option>
        </select>

        <label>Fuel Type</label>
        <select name="fuel_type">
            <option>Petrol</option>
            <option>Diesel</option>
            <option>Electric</option>
            <option>Hybrid</option>
        </select>

        <label>Transmission</label>
        <select name="transmission">
            <option>Automatic</option>
            <option>Manual</option>
        </select>

        <label>Seats</label>
        <input type="number" name="seats" value="5" min="2" max="9" required>

        <label>Price Per Day (₹)</label>
        <input type="number" name="price_per_day" placeholder="e.g. 2500" required>

        <label>Description</label>
        <textarea name="description" placeholder="Brief description of the car..."></textarea>

        <button type="submit" name="add_btn">Add Car</button>
    </form>
    <a href="index.php" class="back">← Back to Gallery</a>
</div>
</body>
</html>
