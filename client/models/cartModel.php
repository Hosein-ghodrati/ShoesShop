<?php
// client/models/CartModel.php
require_once("./config/database.php");

class CartModel {
    public function createOrder($user_email, $address, $phone, $total_price, $count, $status = 'unpaid') {
        global $db;
        if (!$db) throw new Exception("DB not initialized");

        $sql = "INSERT INTO orders (user_email, address, number, totall_price, count, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssiiis", $user_email, $address, $phone, $total_price, $count, $status);
        $stmt->close();

        $sql = "INSERT INTO orders (user_email, address, number, totall_price, `count`, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $db->error);
        $stmt->bind_param("sssiss", $user_email, $address, $phone, $total_price, $count, $status);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();
        return $orderId;
    }

    public function addOrderItem($order_id, $product_id, $price, $quantity, $name) {
        global $db;
        if (!$db) throw new Exception("DB not initialized");

        $sql = "INSERT INTO orders_items (`orders_id`, `product_id`, `price`, `count`, `name`)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $db->error);
      
        $stmt->bind_param("iiiss", $order_id, $product_id, $price, $quantity, $name);
        $stmt->bind_param("iiiss", $order_id, $product_id, $price, $quantity, $name);
        $stmt->execute();
        $stmt->close();
    }

    public function updateOrderStatus($order_id, $status, $ref_id = null) {
        global $db;
        if (!$db) throw new Exception("DB not initialized");

        if ($ref_id !== null) {
         
            $sql = "UPDATE orders SET status = ?, payment_ref = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            if (!$stmt) die("Prepare failed: " . $db->error);
            $stmt->bind_param("ssi", $status, $ref_id, $order_id);
        } else {
            $sql = "UPDATE orders SET status = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            if (!$stmt) die("Prepare failed: " . $db->error);
            $stmt->bind_param("si", $status, $order_id);
        }
        $stmt->execute();
        $stmt->close();
    }

    public function findOrderById($order_id) {
        global $db;
        if (!$db) throw new Exception("DB not initialized");

        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $db->error);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }
}
