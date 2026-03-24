<?php
session_start();
include('db.php');

$msg = "";

if(isset($_POST['update_price'])) {
    $car_id    = mysqli_real_escape_string($conn, $_POST['car_id']);
    $new_price = mysqli_real_escape_string($conn, $_POST['new_price']);

    $sql = "UPDATE cars SET price_per_day = '$new_price' WHERE id = '$car_id'";
    if(mysqli_query($conn, $sql)) {
        $msg = "<p style='color:green; font-weight:bold;'>✅ Price updated successfully!</p>";
    } else {
        $msg = "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Car Prices - Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; padding: 40px; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #2c3e50; color: white; }
        tr:hover { background: #f8f9fa; }
        input[type="number"] { padding: 8px; width: 100px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background: #27ae60; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .btn:hover { background: #219a52; }
        .back { display: inline-block; margin-top: 20px; color: #e67e22; text-decoration: none; }
    </style>
</head>
<body>
    <h2>✏️ Update Car Prices</h2>
    <?php echo $msg; ?>
    <table>
        <tr>
            <th>Brand & Model</th>
            <th>Category</th>
            <th>Current Price (₹/day)</th>
            <th>New Price</th>
            <th>Action</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM cars ORDER BY brand");
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($row['brand']) . " " . htmlspecialchars($row['model']) . "</strong> (" . $row['year'] . ")</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>₹" . number_format($row['price_per_day'], 2) . "</td>";
            echo "<td>
                    <form method='POST' style='display:flex; gap:8px; align-items:center;'>
                        <input type='hidden' name='car_id' value='" . $row['id'] . "'>
                        <input type='number' name='new_price' placeholder='New price' required>
                  </td>";
            echo "<td><button type='submit' name='update_price' class='btn'>Update</button></form></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <a href="index.php" class="back">← Back to Gallery</a>
</body>
</html>
