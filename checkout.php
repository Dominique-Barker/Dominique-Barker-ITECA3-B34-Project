<?php
ob_start();
session_start();
include('config.php');

// 1. Enforce login before any output
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 2. Ensure cart is not empty
if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

include('includes/header.php');

// 3. Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");
    $total = 0;
    while ($p = mysqli_fetch_assoc($result)) {
        $total += $p['price'] * $_SESSION['cart'][$p['product_id']];
    }

    mysqli_query($conn, "INSERT INTO orders (user_id, total_amount) VALUES ($user_id, $total)");
    $order_id = mysqli_insert_id($conn);

    foreach ($_SESSION['cart'] as $pid => $qty) {
        $res = mysqli_query($conn, "SELECT price FROM products WHERE product_id=$pid");
        $price = mysqli_fetch_assoc($res)['price'];
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $pid, $qty, $price)");
        mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE product_id=$pid");
    }

    unset($_SESSION['cart']);
    echo "<script>alert('Order placed successfully!'); window.location='index.php';</script>";
   // Redirect to index.php
    header('Location: index.php');
exit;
}
?>
