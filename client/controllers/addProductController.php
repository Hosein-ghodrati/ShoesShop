<?php
session_start();
require_once("admin/models/ProductModel.php"); 

class addProductController
{

    function index()
    {
        if (!isset($_GET['product_id'])) {
            header("Location: /");
            exit();
        }

        $productId = intval($_GET['product_id']);
        $productModel = new ProductModel();
        $product = $productModel->findById($productId);

        if (!$product) {
            header("Location: /");
            exit();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }

        header("Location: /");
        exit();
    }
}
