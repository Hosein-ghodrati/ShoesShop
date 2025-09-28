<?php
session_start();
// require_once("vendor/autoload.php");
// $date = \Morilog\Jalali\Jalalian::now();
// var_dump($date);
// exit();

$path = $_GET["path"] ?? "";
$PathSplitedBySlash = explode("/", $path);

$routes = [
    "" => [
        "controller" => "HomeController",
        "method" => "index",
        "area" => "client"
    ],
    "blog" => [
        "controller" => "BlogController",
        "method" => "index",
        "area" => "client"
    ],
    "product" => [
        "controller" => "ProductController",
        "method" => "index",
        "area" => "client"
    ],
    "addProductController" => [
        "controller" => "addProductController",
        "method" => "index",
        "area" => "client"
    ],
    "category" => [
        "controller" => "HomeController",
        "method" => "categoryshow",
        "area" => "client"
    ],
    "cart" => [
        "controller" => "cartController",
        "method" => "index",
        "area" => "client"
    ],
    "checkout" => [
        "controller" => "cartController",
        "method" => "checkout",
        "area" => "client"
    ],
    "checkout_verify" => [
        "controller" => "cartController",
        "method" => "verify",
        "area" => "client"
    ],
    "profile" => [
        "controller" => "AuthController",
        "method" => "profile",
        "area" => "admin",
        "auth" => true,       // نیاز به لاگین
        "admin" => true       // نیاز به ادمین
    ],
    "admin_add_product" => [
        "controller" => "AdminProductController",
        "method" => "create",
        "area" => "admin"
    ],
    "login" => [
        "controller" => "AuthController",
        "method" => "index",
        "area" => "admin"
    ],
    "register_process" => [
        "controller" => "AuthController",
        "method" => "registerProcess",
        "area" => "admin"
    ],
    "register" => [
        "controller" => "AuthController",
        "method" => "register",
        "area" => "admin"
    ],
    "login_process" => [
        "controller" => "AuthController",
        "method" => "loginprocess",
        "area" => "admin"
    ],
    "logout" => [
        "controller" => "Logoutcontroller",
        "method" => "logout",
        "area" => "client",
        "auth" => true // فقط کسی که لاگین کرده بتونه لاگ‌اوت کنه
    ],
    "admin_dash_store" => [
        "controller" => "AdminProductController",
        "method" => "storeproduct",
        "area" => "admin"
    ],
    "admin_list_product" => [
        "controller" => "AdminProductController",
        "method" => "listproduct",
        "area" => "admin"
    ],
    "admin_delete_product" => [
        "controller" => "AdminProductController",
        "method" => "delete",
        "area" => "admin"
    ],
    "admin_edit_product" => [
        "controller" => "AdminProductController",
        "method" => "edit",
        "area" => "admin"
    ],
    "admin_update_store" => [
        "controller" => "AdminProductController",
        "method" => "update",
        "area" => "admin"
    ],
    /////////روت دسته بندی////////
    "admin_add_categories" => [
        "controller" => "AdminCategoriesController",
        "method" => "create",
        "area" => "admin"
    ],
    "admin_categories_store" => [
        "controller" => "AdminCategoriesController",
        "method" => "storeproduct",
        "area" => "admin"
    ],
    "admin_list_categories" => [
        "controller" => "AdminCategoriesController",
        "method" => "list",
        "area" => "admin"
    ],
    "admin_delete_categories" => [
        "controller" => "AdminCategoriesController",
        "method" => "delete",
        "area" => "admin"
    ],
    "admin_edit_categories" => [
        "controller" => "AdminCategoriesController",
        "method" => "edit",
        "area" => "admin"
    ],
    "admin_update_categories" => [
        "controller" => "AdminCategoriesController",
        "method" => "update",
        "area" => "admin"
    ],
     "admin_users" => [
        "controller" => "AdminAllUsersController",
        "method" => "AllUsers",
        "area" => "admin"
    ],
    "orders_show" => [
        "controller" => "AdminOrdersInfoController",
        "method" => "ordersInfo",
        "area" => "admin"
    ],
    "orders_status" => [
        "controller" => "AdminOrdersInfoController",
        "method" => "status",
        "area" => "admin"
    ],
     "GitHub" => [
        "controller" => "AdminAllUsersController",
        "method" => "GitHub",
        "area" => "admin"
    ],
];


// index.php or your router file





$routeKey = $PathSplitedBySlash[0] ?? "";

if (isset($routes[$routeKey])) {
    $route = $routes[$routeKey];

    // --- کنترل دسترسی ---
    if (!empty($route['auth']) && empty($_SESSION['user'])) {
        header("Location: /index.php?path=login");
        exit();
    }

    if (!empty($route['admin']) && (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] != 1)) {
        header("Location: /index.php?path=");
        exit();
    }
    // --------------------

    $controllerName = $route["controller"];
    $methodName = $route["method"];
    $area = $route["area"];
    $controllerPath = __DIR__ . "/" . $area . "/controllers/" . $controllerName . ".php";

    if (file_exists($controllerPath)) {
        require_once($controllerPath);

        $obj = new $controllerName();

        if (isset($PathSplitedBySlash[1])) {
            $obj->$methodName($PathSplitedBySlash[1]);
        } else {
            $obj->$methodName();
        }
    } else {
        die("Controller not found! ($controllerPath)");
    }
} else {
    require_once("error404.html");
}
