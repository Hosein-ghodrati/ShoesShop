<?php
require_once("config/database.php");

class CategoryProductModel
{
public function store($data) {
    global $db; 
    if (!$db) throw new Exception("Database connection not initialized.");

    $sql = "INSERT INTO `category_product` (`product_id`, `category_id`) VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $db->error);

    $stmt->bind_param(
        "ii",
        $data["product_id"],
        $data["category_id"]
    );

    $stmt->execute();
    $stmt->close();
    return $db->insert_id;
}
    function deleteById($id)
    {
        global $db;
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "DELETE FROM `category_product` WHERE `id` = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    function findById($id)
    {
        global $db;
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "SELECT * FROM `category_product` WHERE `id` = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    function all()
    {
        global $db;
        $sql = "SELECT * FROM `category_product`";
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function update($data, $id)
    {
        global $db;
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "UPDATE `category_product` 
                SET `product_id` = ?, `category_id` = ?
                WHERE `id` = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param(
            "iii",
            $data["product_id"],
            $data["category_id"],
            $id
        );

        $stmt->execute();
        $stmt->close();
    }


}



