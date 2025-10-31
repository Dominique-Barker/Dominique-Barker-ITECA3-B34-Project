<?php
session_start();
include('config.php');
include('includes/header.php');

// Initialize cart if not already
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Handle adding items to cart via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }
}
?>

<div class="container mt-4">
    <h3 class="mb-4">Your Shopping Cart</h3>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: 
        $ids = implode(',', array_keys($_SESSION['cart']));
        $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");
        $total = 0;
    ?>
        <table class="table table-bordered align-middle text-center" id="cart-table">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): 
                    $qty = $_SESSION['cart'][$row['product_id']];
                    $subtotal = $row['price'] * $qty;
                    $total += $subtotal;
                ?>
                <tr data-price="<?php echo $row['price']; ?>" data-product-id="<?php echo $row['product_id']; ?>">
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>
                        <input type="number" class="form-control text-center cart-qty" 
                               min="0" value="<?php echo $qty; ?>">
                    </td>
                    <td>R<?php echo number_format($row['price'], 2); ?></td>
                    <td class="subtotal">R<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="table-secondary">
                    <td colspan="3"><strong>Total</strong></td>
                    <td id="cart-total"><strong>R<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="checkout.php" method="POST">
                <button type="submit" class="btn btn-success">Proceed to Checkout</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn btn-warning">Login to Checkout</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- 🧮 JavaScript for Live Cart Totals + Remove Zero Quantities -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const cartTable = document.getElementById('cart-table');
  if (!cartTable) return;

  cartTable.addEventListener('input', function(e) {
    if (!e.target.classList.contains('cart-qty')) return;

    const row = e.target.closest('tr');
    const price = parseFloat(row.getAttribute('data-price'));
    const qty = parseInt(e.target.value);

    if (isNaN(qty) || qty < 0) return;

    if (qty === 0) {
      // Remove row if quantity is zero
      row.remove();
    } else {
      // Update row subtotal
      const subtotal = price * qty;
      row.querySelector('.subtotal').textContent = `R${subtotal.toFixed(2)}`;
    }

    // Update total
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(cell => {
      total += parseFloat(cell.textContent.replace('R', '')) || 0;
    });
    document.getElementById('cart-total').textContent = `R${total.toFixed(2)}`;

    // If no rows left, show empty message
    if (cartTable.querySelectorAll('tbody tr').length === 1) { // only total row remains
      cartTable.remove();
      const container = document.querySelector('.container');
      const p = document.createElement('p');
      p.textContent = 'Your cart is empty.';
      container.appendChild(p);
    }
  });
});
</script>

<?php include('includes/footer.php'); ?>
