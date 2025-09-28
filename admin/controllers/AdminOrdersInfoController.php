<?php
require_once("admin/models/ordersModel.php");

class AdminOrdersInfoController
{
    public function ordersInfo()
    {
        $model = new ordersModel();
        $allOrders = $model->getAllOrders();

        require_once("admin/views/ordersInfoPage.php");
    }


    public function status()
    {
        if (!isset($_POST['orderId']) || !isset($_POST['status'])) {
            exit(json_encode(["status" => "error", "message" => "wrong data!"]));
        }

        $orderId = $_POST['orderId'];
        $status = $_POST['status'];

        $model = new ordersModel();
        $success = $model->updateOrderStatus($orderId, $status);

        header('Content-Type: application/json');
        echo json_encode([
            "status" => "success",
            "message" => "وضعیت سفارش تغییر کرد"
        ]);
        exit;
    }
}
