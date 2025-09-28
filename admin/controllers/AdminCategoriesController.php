<?php
require_once("admin/models/CategoryModel.php");
require_once("config/database.php");
class  AdminCategoriesController
{
    function create()
    {
        $categoryModel = new CategoryModel();
        $allCategories = $categoryModel->all();

        require_once("admin/views/create_category.php");
    }

    function storeproduct()
    {
      if(isset($_POST["save_category"])){
        $errors = [];

        if(!isset($_POST["name"]) || empty($_POST["name"])){
            $errors["name"] = "لطفا نام دسته بندی را پرکنید";
        }

        if(count($errors) > 0 ){
            $_SESSION["errors"] = $errors;
            header("location: /admin_add_categories");
            exit();
        }else{

            $categoryModel = new CategoryModel();
            if(
                 !(isset($_POST["parent_id"]) && !empty($_POST["parent_id"]) && is_numeric($_POST["parent_id"])) 
                ){
                    $_POST["parent_id"] = null;
                }
            $categoryModel->store($_POST);
            $_SESSION['success'] = "دسته بندی با موفقیت اضافه شد";
            header("location: /admin_add_categories");
            exit();
        }

    
      }
    }


    function list()
    {
        $categoryModel = new CategoryModel();
        $allCategories = $categoryModel->all();

        require_once("admin/views/list_category.php");
    }


    function delete($id)
    {
        // echo $id;

        $categoryModel = new CategoryModel();

        $categoryModel->deleteById($id);
        $_SESSION['success'] = "دسته بندی با موفقیت حذف شد!";
        header("location: /admin_list_categories");
        exit();
    }

   function update($id)
    {
  
    }

}
