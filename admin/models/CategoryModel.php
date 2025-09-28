<?php
require_once("config/database.php");

class CategoryModel
{
    public function store($category)
    {
        global $db;
        ////ارور دیتا بیس
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "INSERT INTO `categories` (`name`, `parent_id`) 
        VALUES (?, ?)";
        $result = $db->prepare($sql);

        if (!$result) {
            die("Prepare failed: " . $db->error);
        }

        $result->bind_param(
            "si",
            $category["name"],
            $category["parent_id"]
        );

        $result->execute();
        $result->close();
    }


    function deleteById($id)
    {
        global $db;
        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "DELETE FROM `categories` WHERE `id` = ?";
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

        $sql = "SELECT * FROM `categories` WHERE `id` = ?";
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


       function all(){
        $sql = "SELECT * FROM `categories`";

        global $db;
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);

    }

    function update($category, $id)
    {
        global $db;

        if (!$db) {
            throw new Exception("Database connection not initialized.");
        }

        $sql = "UPDATE `categories` 
        SET `name` = ?, `parent_id` = ?
        WHERE `id` = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $db->error);
        }

        $stmt->bind_param(
            "sii",
            $category["name"],
            $category["parent_id"] ?: null,
            $id
        );

        $stmt->execute();
        $stmt->close();
    }



    function allWithImage()
    {
        global $db;
        $sql = "
        SELECT c.id, c.name,
               (
                   SELECT p.image
                   FROM products p
                   INNER JOIN category_product cp 
                       ON cp.product_id = p.id
                   WHERE cp.category_id = c.id
                   LIMIT 1
               ) AS image
        FROM categories c
    ";
        $result = $db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }




}
