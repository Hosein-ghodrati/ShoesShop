<?php
require_once("config/database.php");

class HomeModel
{

    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }


    public function getCategoriesWithProducts()
    {
        $result = [];

        $categoriesQuery = $this->db->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $categoriesQuery->fetch_all(MYSQLI_ASSOC);

        foreach ($categories as $cat) {
            $catId = $cat['id'];
            $productsQuery = $this->db->query("
                SELECT p.id, p.name 
                FROM products p
                JOIN category_product cp ON cp.product_id = p.id
                WHERE cp.category_id = $catId
            ");
            $products = $productsQuery->fetch_all(MYSQLI_ASSOC);

            $result[] = [
                'id' => $cat['id'],
                'name' => $cat['name'],
                'products' => $products
            ];
        }

        return $result;
    }

    public function all($limit = 8)
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
