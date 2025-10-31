<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  
  $exists = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($exists) > 0) {
    echo "<script>alert('Email already registered!');</script>";
  } else {
    $query = "INSERT INTO users (name, email, password, role_id) VALUES ('$name', '$email', '$password', 4)";
    mysqli_query($conn, $query);
    echo "<script>alert('Account created successfully!'); window.location='login.php';</script>";
  }
}
include('header.php');
?>
<h3>Create an Account</h3>
<form method="POST" class="mt-3" style="max-width:400px;">
  <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
  <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
  <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
  <button type="submit" class="btn btn-primary w-100">Register</button>
</form>
<?php include('includes/footer.php'); ?>