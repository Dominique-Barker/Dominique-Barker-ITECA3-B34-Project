<?php
session_start();
include('config.php');
include('includes/auth.php');
?>

<div class="container mt-4">
  <h2>Welcome to Admin Dashboard</h2>
  <div class="row text-center">
    <div class="col-md-3">
      <div class="card bg-light p-3">
        <h5>Total Users</h5>
        <p>
          <?php
          $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
          $count = mysqli_fetch_assoc($res)['total'];
          echo $count;
          ?>
        </p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-light p-3">
        <h5>Total Products</h5>
        <p>
          <?php
          $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
          echo mysqli_fetch_assoc($res)['total'];
          ?>
        </p>
      </div>
    </div>
  </div>
</div>