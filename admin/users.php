<?php
include('config.php');
include('includes/auth.php');

if (isset($_POST['delete'])) {
  $id = $_POST['user_id'];
  mysqli_query($conn, "DELETE FROM users WHERE user_id=$id");
}

$result = mysqli_query($conn, "SELECT u.*, r.role_name FROM users u JOIN roles r ON u.role_id=r.role_id");
?>
<div class="container mt-4">
  <h3>User Management</h3>
  <table class="table table-striped">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $row['user_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['role_name'] ?></td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
            <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>