<?php
session_start();  // بداية الجلسة

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استلام البيانات من النموذج
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment_method'];

    // استرجاع بيانات السلة من الجلسة
    $cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    // إذا كانت السلة فارغة
    if (empty($cartData)) {
        die('Your cart is empty.');
    }

    // استرجاع السعر الإجمالي من النموذج
    $totalPrice = $_POST['total_price'];

    // اتصال بقاعدة البيانات
    $conn = new mysqli('localhost', 'root', '', 'cake_store_db');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // إدخال البيانات في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO orders (full_name, email, phone, address, payment_method, cart_data, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssd", $fullName, $email, $phone, $address, $paymentMethod, json_encode($cartData), $totalPrice);

    if ($stmt->execute()) {
        echo "Order placed successfully!";
    } else {
        echo "Error placing order: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
