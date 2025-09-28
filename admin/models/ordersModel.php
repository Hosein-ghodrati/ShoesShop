<?php
require_once("config/database.php");

class ordersModel {

    public function getAllOrders() {
        global $db; 
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        $result = $db->query($sql);

        $orders = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        return $orders;
    }

      function findById($id)
    {
        global $db;
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "SELECT * FROM `orders` WHERE `id` = ?";
        $preparsql = $db->prepare($sql);

        if (!$preparsql) {
            die("Prepare failed: " . $db->error);
        }

        $preparsql->bind_param(
            "i",
            $id
        );

        $preparsql->execute();
        $result = $preparsql->get_result();
        return $result->fetch_assoc();
    }

 public function updateOrderStatus($orderId, $status) {
    global $db;
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);
    return $stmt->execute();
}


}

