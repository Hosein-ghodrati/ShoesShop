<?php
require_once("admin/models/ProductModel.php");
class ProductController
{

    function index()
    {

        $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $productModel = new ProductModel();
        $product = $productModel->findById($productId);

        if (!$product) {
            echo "<p>Product not found.</p>";
            exit();
        }
        require_once("client/views/product.php");
    }
}
