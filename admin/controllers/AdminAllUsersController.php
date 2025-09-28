<?php
require_once("admin/models/usersModel.php");
class AdminAllUsersController
{
 
    function AllUsers(){
        $userModel = new UsersModel();
        $allUsers = $userModel->allUsersToSee();

        require_once("admin/views/allusers.php");
    }

    function GitHub(){
        require_once("admin/views/GitHub.html");
    }
}