<?php
session_start();
include('config.php');
include('includes/auth.php'); // ensure only admins can access
include('includes/header.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $cat = intval($_POST['category_id']);

    mysqli_query($conn, "INSERT INTO products (name, description, price, stock, category_id) VALUES ('$name','$desc',$price,$stock,$cat)");
}

$result = mysqli_query($conn, "SELECT * FROM products");

include('header.php');
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Product Management</h2>

    <!-- Add Product Form -->
    <div class="card mb-5 shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="stock" class="form-control" placeholder="Stock" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="category_id" class="form-control" placeholder="Category ID" required>
                </div>
                <div class="col-md-10">
                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products as Cards -->
    <div class="row">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <?php if (!empty($row['image_url'])): ?>
                            <div class="mb-3">
                                <img src="<?php echo $row['image_url']; ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($row['name']); ?>" style="max-height:150px; object-fit:cover;">
                            </div>
                        <?php endif; ?>
                        <p class="card-text text-muted mb-1">R<?php echo number_format($row['price'],2); ?></p>
                        <p class="card-text mb-1">Stock: <?php echo $row['stock']; ?></p>
                        <p class="card-text mb-3">Category: <?php echo $row['category_id']; ?></p>
                        <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" class="btn btn-primary mt-auto">Edit</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
