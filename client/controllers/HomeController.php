<?php
require_once("admin/models/CategoryModel.php");
require_once("admin/models/ProductModel.php");
require_once("client/models/HomeModel.php");


class HomeController {

    function index() {
        $CategoryModel = new CategoryModel();
        $categories = $CategoryModel->allWithImage();

        $ProductModel = new ProductModel();
        $products = $ProductModel->allForMainPage(8); 
        require_once("client/views/index.php");
    }

function categoryshow($categoryId) {
        global $conn; // <-- Add this

        if (!$categoryId) die("Category not found.");

        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$categoryId]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$category) die("Category not found!");

        $stmt = $conn->prepare("
            SELECT p.* 
            FROM products p
            INNER JOIN category_product cp ON p.id = cp.product_id
            WHERE cp.category_id = ?
        ");
        $stmt->execute([$categoryId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once("client/views/categoryshow.php");
    }


    function getCategoriesDropdown() {
        $CategoryModel = new CategoryModel();
        $ProductModel = new ProductModel();

        $categories = $CategoryModel->all(); 

        foreach ($categories as &$cat) {
            $catId = $cat['id'];
            $cat['products'] = $ProductModel->getByCategory($catId); 
        }

        return $categories;
    }
}
