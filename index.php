<?php
session_start();
include('db.php');

$where = "WHERE is_available = 1";
if(!empty($_GET['search'])) $where .= " AND (brand LIKE '%" . mysqli_real_escape_string($conn, $_GET['search']) . "%' OR model LIKE '%" . mysqli_real_escape_string($conn, $_GET['search']) . "%')";
if(!empty($_GET['category']) && $_GET['category'] != 'all') $where .= " AND category = '" . mysqli_real_escape_string($conn, $_GET['category']) . "'";
if(!empty($_GET['price'])) {
    if($_GET['price'] == '1') $where .= " AND price_per_day <= 2000";
    elseif($_GET['price'] == '2') $where .= " AND price_per_day BETWEEN 2001 AND 4000";
    elseif($_GET['price'] == '3') $where .= " AND price_per_day > 4000";
}

$result = mysqli_query($conn, "SELECT * FROM cars $where ORDER BY price_per_day ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>CarRental Pro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }
        nav { background: #2c3e50; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        nav .brand { color: white; font-size: 1.4em; font-weight: bold; }
        nav a { color: white; text-decoration: none; margin-left: 15px; }
        nav a:hover { color: #f39c12; }
        .hero { text-align: center; padding: 50px; background: #34495e; color: white; }
        .hero h1 { font-size: 2em; margin-bottom: 8px; }
        .hero p { color: #bdc3c7; }
        .filters { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; padding: 20px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.08); }
        .filters input, .filters select { padding: 9px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95em; }
        .filters input { width: 220px; }
        .filters button { padding: 9px 20px; background: #e67e22; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .filters a { color: #888; text-decoration: none; padding: 9px; font-size: 0.9em; }
        .cars { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 30px; }
        .card { background: white; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); width: 250px; padding: 20px; text-align: center; transition: transform 0.2s; }
        .card:hover { transform: translateY(-4px); }
        .card h3 { color: #2c3e50; margin-bottom: 6px; }
        .card p { color: #7f8c8d; font-size: 0.85em; margin-bottom: 8px; }
        .price { color: #27ae60; font-size: 1.2em; font-weight: bold; }
        .btn { display: block; margin-top: 12px; background: #e67e22; color: white; padding: 9px; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .btn:hover { background: #d35400; }
        footer { text-align: center; padding: 20px; background: #2c3e50; color: #bdc3c7; margin-top: 30px; }
    </style>
</head>
<body>

<nav>
    <div class="brand">🚗 CarRental Pro</div>
    <div>
        <?php if(isset($_SESSION['user_name'])): ?>
            <span style="color:white;">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <a href="my_bookings.php">My Bookings</a>
            <a href="logout.php" style="color:#ff7675;">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>

<div class="hero">
    <h1>Find Your Perfect Ride</h1>
    <p>Secure, fast, and easy car rentals at your fingertips.</p>
</div>

<form method="GET">
<div class="filters">
    <input type="text" name="search" placeholder="🔍 Search brand or model..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <select name="category">
        <option value="all">All Categories</option>
        <?php foreach(['Sedan','SUV','Hatchback','Luxury','Electric'] as $c): ?>
            <option value="<?php echo $c; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $c) ? 'selected' : ''; ?>><?php echo $c; ?></option>
        <?php endforeach; ?>
    </select>
    <select name="price">
        <option value="">All Prices</option>
        <option value="1" <?php echo (isset($_GET['price']) && $_GET['price']=='1') ? 'selected':''; ?>>Under ₹2,000</option>
        <option value="2" <?php echo (isset($_GET['price']) && $_GET['price']=='2') ? 'selected':''; ?>>₹2,000 - ₹4,000</option>
        <option value="3" <?php echo (isset($_GET['price']) && $_GET['price']=='3') ? 'selected':''; ?>>Above ₹4,000</option>
    </select>
    <button type="submit">Search</button>
    <a href="index.php">✕ Reset</a>
</div>
</form>

<div class="cars">
<?php if(mysqli_num_rows($result) > 0):
    while($row = mysqli_fetch_assoc($result)): ?>
    <div class="card">
        <h3><?php echo $row['brand']." ".$row['model']; ?></h3>
        <p><?php echo $row['category']." | ".$row['fuel_type']." | ".$row['transmission']; ?></p>
        <p class="price">₹<?php echo number_format($row['price_per_day'],2); ?> / day</p>
        <a href="checkout.php?car_id=<?php echo $row['id']; ?>" class="btn">Rent Now</a>
    </div>
<?php endwhile; else: ?>
    <p style="color:#888; padding:40px;">No cars found. <a href="index.php">Reset filters</a></p>
<?php endif; ?>
</div>

<footer><p>© 2026 CarRental Pro. All rights reserved.</p></footer>
</body>
</html>