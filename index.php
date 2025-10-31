<?php 
include('config.php'); 
include('includes/header.php'); 

// Helper function: estimate carbon footprint based on category and weight
function estimate_carbon_footprint($category, $weight) {
  // Default multipliers (kg CO₂e per kg of product)
  $multipliers = [
    'electronics' => 20,
    'clothing'    => 5,
    'furniture'   => 15,
    'accessories' => 8,
    'default'     => 10
  ];
  
  // Normalize category for lookup
  $category_key = strtolower(trim($category));
  
  // Select appropriate multiplier
  $multiplier = isset($multipliers[$category_key]) ? $multipliers[$category_key] : $multipliers['default'];
  
  // Use at least 0.5 kg if weight missing (avoid zero)
  $weight = $weight > 0 ? $weight : 0.5;

  // Return estimated footprint rounded to 2 decimals
  return round($multiplier * $weight, 2);
}
?>

<div class="container mt-4">
  <h2 class="mb-4 text-center">Welcome to DragonStone</h2>
  <div class="row">
    <?php
      $query = "SELECT * FROM products";
      $result = mysqli_query($conn, $query);
      
      while($row = mysqli_fetch_assoc($result)) {
        // Step 1: Use stored carbon footprint if available
        if (!empty($row['carbon_footprint'])) {
          $carbon_footprint = number_format($row['carbon_footprint'], 2);
        } else {
          // Step 2: Estimate based on category + weight
          $category = isset($row['category']) ? $row['category'] : 'default';
          $weight = isset($row['weight']) ? floatval($row['weight']) : 0;
          $carbon_footprint = estimate_carbon_footprint($category, $weight);
        }

        echo "
        <div class='col-md-3 mb-4'>
          <div class='card h-100 text-center shadow-sm'>
            <div class='card-body d-flex flex-column'>
              <h5 class='card-title mb-3'>{$row['name']}</h5>
              <div class='mb-3'>
                <img src='{$row['image_url']}' class='img-fluid rounded' alt='{$row['name']}' style='max-height:180px; object-fit:cover;'>
              </div>
              <p class='card-text text-muted mb-2'>R{$row['price']}</p>
              <p class='card-text small text-success carbon-footprint' data-footprint='{$carbon_footprint}'>
                Estimated Carbon Footprint: <strong>{$carbon_footprint} kg CO₂e</strong>
              </p>
              <a href='cart.php?id={$row['product_id']}' class='btn btn-primary mt-auto'>Add to Cart</a>
            </div>
          </div>
        </div>";
      }
    ?>
  </div>
</div>

<!-- JavaScript for interactive carbon footprint info -->
<script>
// Wait until page is loaded
document.addEventListener('DOMContentLoaded', function() {
  const footprintElements = document.querySelectorAll('.carbon-footprint');

  footprintElements.forEach(el => {
    el.addEventListener('click', () => {
      const value = el.getAttribute('data-footprint');
      alert(`Estimated carbon footprint: ${value} kg CO₂e\n\nThis value represents the approximate amount of CO₂ emissions associated with producing this product. It’s based on the product’s weight and category.`);
    });
  });
});
</script>

<?php include('includes/footer.php'); ?>
