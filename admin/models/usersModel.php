<?php
require_once("config/database.php");

class UsersModel
{
    public function allUsersToSee()
    {
        global $db; 
        $sql = "SELECT username, email, Is_admin, Join_date FROM `users` ORDER BY join_date DESC";
        $result = $db->query($sql);

        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
}
