<?php
require_once("admin/models/ProductModel.php");
require_once("admin/models/CategoryModel.php");
require_once("admin/models/CategoryProductModel.php");
require_once("config/database.php");
class  AdminProductController
{
    function create()
    {
        $categoryModel = new CategoryModel();
        $allCategories = $categoryModel->all();
        require_once("admin/views/create.php");
    }

    function storeproduct()
    {
        // var_dump($_POST);
        // echo "<pre>";
        // var_dump($_FILES);
        // echo "<pre>";
        // exit();
        // echo "<pre>";
        // var_dump($_POST);
        // echo "<pre>";
        // exit();

        if (isset($_POST["save_product"])) {
            $errors = [];

            if (!isset($_FILES["image"]) || !isset($_FILES["image"]["name"]) || $_FILES["image"]["error"] != 0) {
                $errors["image"] = "لطفا فایل را به درستی وارد کنید";
            } else {
                if (
                    !($_FILES["image"]["type"] == "image/jpg" ||
                        $_FILES["image"]["type"] == "image/jpeg" ||
                        $_FILES["image"]["type"] == "image/png" ||
                        $_FILES["image"]["type"] == "image/webp" || 
                        $_FILES["image"]["type"] == "image/avif" )
                ) {
                    $errors["image"] = "لطفا فقط فایل تصویری بزارید";
                }

                if ($_FILES["image"]["size"] > 5000000) {
                    $errors["image"] = "حجم فایل باید کمتر از 5 مگابایت باشد";
                }
            }

            if (!isset($_POST["name"]) || empty($_POST["name"])) {
                $errors["name"] = "اسم محصول را وارد نمایید";
            }
            if (!isset($_POST["short_desc"]) || empty($_POST["short_desc"])) {
                $errors["short_desc"] = "توضیحات محصول را وارد نمایید";
            }
            if (!isset($_POST["price"]) || empty($_POST["price"]) || !is_numeric($_POST["price"]) || $_POST["price"] < 1) {
                $errors["price"] = "قیمت محصول را وارد نمایید";
            }
            if (!isset($_POST["in_stock"]) || !is_numeric($_POST["in_stock"]) || $_POST["in_stock"] < 0) {
                $errors["in_stock"] = "تعداد محصول را وارد نمایید(بالاتر از صفر)";
            }
            if (!isset($_POST["size"]) || empty($_POST["size"])) {
                $errors["size"] = "سایز محصول را وارد نمایید";
            }
            if (!isset($_POST["categories"]) || !is_array($_POST["categories"]) || count($_POST["categories"]) === 0) {
                $errors["categories"] = "لطفا حداقل یک دسته بندی را انتخاب کنید";
            }
            if (!isset($_POST["desc"]) || empty($_POST["desc"])) {
                $errors["desc"] = "نقد و بررسی محصول را وارد نمایید";
            }

            if (count($errors) > 0) {
                $_SESSION["errors"] = $errors;
                $_SESSION["old"] = $_POST; // ذخیره اینپوت های کاربر
                header("location: /index.php?path=admin_add_product");
                exit();
            } else {

                if (move_uploaded_file($_FILES["image"]["tmp_name"], "media/" . $_FILES["image"]["name"])) {
                    $productModel = new ProductModel();
                    $productData = $_POST;
                    $productData["image"] = $_FILES["image"]["name"];

                    unset($productData["categories"]);

                    $lastInsertedProductId = $productModel->store($productData);


                 if($lastInsertedProductId){
                       $CategoryProductModel = new CategoryProductModel();
                    foreach ($_POST["categories"] as $selectedCategoryId) {
                        $CategoryProductModel->store([
                            "product_id" => $lastInsertedProductId,
                            "category_id" => $selectedCategoryId
                        ]);
                    }
                 }else{
                     $errors["image"] = "مشکل در آپلود فایل";
                    $_SESSION['errors'] = $errors; // Save the error
                    header("location: /index.php?path=admin_add_product");
                    exit();
                 }


                    $_SESSION['success'] = "محصول با موفقیت اضافه شد";
                    header("location: /index.php?path=admin_add_product");
                    exit();
                } else {
                    $errors["image"] = "مشکل در آپلود فایل";
                    $_SESSION['errors'] = $errors; // Save the error
                    header("location: /index.php?path=admin_add_product");
                    exit();
                }
            }
        } else {
            header("location: /index.php?path=admin_add_product");
        }
    }


    function listproduct()
    {
        $productModel = new ProductModel();

        $allProducts = $productModel->all();

        require_once("admin/views/listproduct.php");
    }


    function delete($id)
    {
        // echo $id;

        $productModel = new ProductModel();

        $product = $productModel->findById($id);

        if (file_exists('media/' . $product['image'])) {
            unlink('media/' . $product['image']);
        }

        $productModel->deleteById($id);
        $_SESSION['delete'] = "محصول با موفقیت حذف شد!";
        header("location: /admin_list_product");
        exit();
    }

    function edit($id)
    {
        $productModel = new ProductModel();

        $product = $productModel->findById($id);

        require_once("admin/views/edit_product.php");
    }

 
function update($id)
    {
        if (isset($_POST["update_product"])) {
            $errors = [];

            if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
                if ($_FILES["image"]["error"] != 0) {
                    $errors["image"] = "لطفا فایل را به درستی وارد کنید";
                } else if (!in_array($_FILES["image"]["type"], ["image/jpg", "image/jpeg", "image/png", "image/webp"])) {
                    $errors["image"] = "لطفا فقط فایل تصویری بزارید";
                } else if ($_FILES["image"]["size"] > 5000000) {
                    $errors["image"] = "حجم فایل باید کمتر از 5 مگابایت باشد";
                }
            }

            if (empty($_POST["name"])) $errors["name"] = "اسم محصول را وارد نمایید";
            if (empty($_POST["short_desc"])) $errors["short_desc"] = "توضیحات محصول را وارد نمایید";
            if (!isset($_POST["price"]) || !is_numeric($_POST["price"]) || $_POST["price"] < 1) $errors["price"] = "قیمت محصول را وارد نمایید";
            if (!isset($_POST["in_stock"]) || !is_numeric($_POST["in_stock"]) || $_POST["in_stock"] < 0) $errors["in_stock"] = "تعداد محصول را وارد نمایید(بالاتر از صفر)";
            if (empty($_POST["size"])) $errors["size"] = "سایز محصول را وارد نمایید";
            if (empty($_POST["desc"])) $errors["desc"] = "نقد و بررسی محصول را وارد نمایید";

            if (count($errors) > 0) {
                $_SESSION["errors"] = $errors;
                $_SESSION["old"] = $_POST;
                header("Location: /admin_edit_product/" . $id);
                exit();
            }

            $productModel = new ProductModel();
            $productData = $_POST;

            if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], "media/" . $_FILES["image"]["name"])) {
                    $errors["image"] = "مشکل در آپلود فایل";
                    $_SESSION['errors'] = $errors;
                    header("Location: /admin_edit_product/" . $id);
                    exit();
                }
                $productData["image"] = $_FILES["image"]["name"];
            } else {
                $oldProduct = $productModel->findById($id);
                $productData["image"] = $oldProduct["image"];
            }

            $productModel->update($productData, $id);

            $_SESSION['success'] = "محصول با موفقیت ویرایش شد";
            header("Location: /admin_list_product/" . $id);
            exit();
        } else {
            header("Location: /admin_edit_product/" . $id);
            exit();
        }
    }

}
