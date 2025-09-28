<?php
require_once("config/database.php");
class ProductModel
{
    public function store($product)
    {
        global $db;
        ////ارور دیتا بیس
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "INSERT INTO `products` (`name`,`short_desc`,`price`,`image`,`size`,`in_stock`,`desc`)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param(
            "ssissis",
            $product["name"],
            $product["short_desc"],
            $product["price"],
            $product["image"],
            $product["size"],
            $product["in_stock"],
            $product["desc"]
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

        $sql = "DELETE FROM `products` WHERE `id` = ?";
        $result = $db->prepare($sql);

        if (!$result) {
            die("Prepare failed: " . $db->error);
        }

        $result->bind_param(
            "i",
            $id
        );

        $result->execute();
        $result->close();
    }
    function findById($id)
    {
        global $db;
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "SELECT * FROM `products` WHERE `id` = ?";
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

    public function all()
    {
        global $db;

        if (!$db) die("Database connection not initialized.");

        $sql = "SELECT * FROM `products` ORDER BY `created_at` DESC";
        $result = $db->query($sql);

        $products = [];
        if ($result && $result->num_rows > 0) {
            $products = $result->fetch_all(MYSQLI_ASSOC);
        }

        return $products;
    }

    function update($product, $id)
    {
        global $db;

        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "UPDATE `products` 
         SET `name` = ?, `short_desc` = ?, `price` = ?, `image` = ?, `size` = ?, `in_stock` = ?, `desc` = ?
            WHERE `id` = ?";

        $stmt = $db->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param(
            "ssissisi",
            $product["name"],
            $product["short_desc"],
            $product["price"],
            $product["image"],
            $product["size"],
            $product["in_stock"],
            $product["desc"],
            $id
        );

        $stmt->execute();
        $stmt->close();
    }


    public function getByCategory($categoryId)
    {
        global $db;

        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "
        SELECT p.id, p.name
        FROM products p
        INNER JOIN category_product cp ON p.id = cp.product_id
        WHERE cp.category_id = ?
        ORDER BY p.name ASC
    ";

        $stmt = $db->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products;
    }

      public function allForMainPage($limit = 8)
    {
        global $db;

        if (!$db) die("Database connection not initialized.");

        $sql = "SELECT * FROM `products` ORDER BY `created_at` DESC LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $limit); 
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        if ($result && $result->num_rows > 0) {
            $products = $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
        return $products;
    }
}
