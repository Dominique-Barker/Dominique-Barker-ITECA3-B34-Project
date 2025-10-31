<?php
session_start();
include('config.php');
include('includes/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role_id'] = $user['role_id'];
    header('Location: admin/dashboard.php');
  } else {
    echo "<script>alert('Invalid credentials');</script>";
  }
}
?>

<form method="POST" class="container mt-5" style="max-width:400px;">
  <h3>Login</h3>
  <div class="form-group mb-3">
    <input type="email" name="email" class="form-control" placeholder="Email" required>
  </div>
  <div class="form-group mb-3">
    <input type="password" name="password" class="form-control" placeholder="Password" required>
  </div>
  <button type="submit" class="btn btn-primary w-100">Login</button>
</form>