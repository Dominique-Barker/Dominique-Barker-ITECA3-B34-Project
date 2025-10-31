<?php
include('config.php');
include('includes/auth.php');

// Sales report
$sales = mysqli_query($conn, "SELECT SUM(total_amount) AS revenue, COUNT(*) AS orders FROM orders");

// Inventory report
$inventory = mysqli_query($conn, "SELECT COUNT(*) AS total_products, SUM(stock) AS total_stock FROM products");

// User activity
$users = mysqli_query($conn, "SELECT name, email FROM users ORDER BY user_id DESC LIMIT 5");
?>

<div class="container mt-4">
  <h3>Reports</h3>
  <div class="card p-3 mb-3">
    <h5>Sales Summary</h5>
    <?php $data = mysqli_fetch_assoc($sales); ?>
    <p>Total Revenue: R<?php echo $data['revenue']; ?></p>
    <p>Total Orders: <?php echo $data['orders']; ?></p>
  </div>

  <div class="card p-3 mb-3">
    <h5>Inventory Summary</h5>
    <?php $inv = mysqli_fetch_assoc($inventory); ?>
    <p>Total Products: <?php echo $inv['total_products']; ?></p>
    <p>Total Items in Stock: <?php echo $inv['total_stock']; ?></p>
  </div>

  <div class="card p-3">
    <h5>Recent Users</h5>
    <ul>
      <?php while($u = mysqli_fetch_assoc($users)) echo "<li>{$u['name']} ({$u['email']})</li>"; ?>
    </ul>
  </div>
</div>