<?php
require_once("admin/models/CategoryModel.php");
require_once("admin/models/ProductModel.php");
require_once("client/models/cartModel.php");

class cartController
{

    function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

       
        if (isset($_POST['remove_id'])) {
            $removeId = $_POST['remove_id'];
            if (isset($_SESSION['cart'][$removeId])) {
                unset($_SESSION['cart'][$removeId]);
            }
        }

        require_once("client/views/cart.php");
    }

    function checkout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        
        if (empty($_SESSION['user'])) {
            header("Location: /login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $errors = $_SESSION['errors'] ?? [];
            $old = $_SESSION['old'] ?? [];
            unset($_SESSION['errors'], $_SESSION['old']);
            require_once("client/views/cart.php");
            return;
        }

        
        $address = trim($_POST['address'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');

        $errors = [];

        if ($address === '') {
            $errors['address'] = "Address is required.";
        }

        
        if ($phone === '') {
            $errors['phone'] = "Phone number is required.";
        } elseif (!preg_match('/^09\d{9}$/', $phone)) {
            $errors['phone'] = "Invalid phone number. Must start with 09 and be 11 digits.";
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $errors['cart'] = "Your cart is empty.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: /index.php?path=checkout");
            exit();
        }

        
        $totalPrice = 0;
        $totalCount = 0;
        foreach ($cart as $c) {
            $qty = isset($c['quantity']) ? (int)$c['quantity'] : 1;
            $price = isset($c['price']) ? (int)$c['price'] : 0;
            $totalPrice += $price * $qty;
            $totalCount += $qty;
        }
        if (empty($_SESSION['user']) || empty($_SESSION['user']['email'])) {
           
            $_SESSION['redirect_after_login'] = '/index.php?path=checkout';
            header("Location: /login");
            exit();
        }

      
        $cartModel = new CartModel();
        $user_email = $_SESSION['user']['email'];
        $orderId = $cartModel->createOrder($user_email, $address, $phone, $totalPrice, $totalCount, 'unpaid');

      
        foreach ($cart as $c) {
            $product_id = $c['id'];
            $qty = isset($c['quantity']) ? (int)$c['quantity'] : 1;
            $price = isset($c['price']) ? (int)$c['price'] : 0;
            $name = $c['name'] ?? '';
            $cartModel->addOrderItem($orderId, $product_id, $price, $qty, $name);
        }

       
        $amount = $totalPrice; 
        $callback = "http://shop.test/index.php?path=checkout_verify&order_id=" . $orderId;

        $data = array(
            "merchant_id" => "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
            "amount" => $amount,
            "callback_url" => $callback,
            "description" => "Payment for order #$orderId",
            "metadata" => ["email" => $user_email, "mobile" => $phone]
        );
        $jsonData = json_encode($data);

        $ch = curl_init('https://sandbox.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);

        if ($err) {
            $_SESSION['errors'] = ['payment' => "Payment gateway request failed: $err"];
            header("Location: /cart");
            exit();
        }

        if (!empty($result['errors'])) {
            $_SESSION['errors'] = ['payment' => $result['errors']['message'] ?? 'Payment request error'];
            header("Location: /cart");
            exit();
        }

        if (!empty($result['data']['authority']) && isset($result['data']['code']) && $result['data']['code'] == 100) {
            $authority = $result['data']['authority'];
        
            header('Location: https://sandbox.zarinpal.com/pg/StartPay/' . $authority);
            exit();
        } else {
            $_SESSION['errors'] = ['payment' => 'Payment request rejected by gateway.'];
            header("Location: /cart");
            exit();
        }
    }

    
    function verify()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once("client/models/CartModel.php");

        $orderId = (int)($_GET['order_id'] ?? 0);
        $authority = $_GET['Authority'] ?? null;
        $status = $_GET['Status'] ?? null;

        if (!$orderId || !$authority) {
            echo "Invalid callback parameters.";
            exit();
        }

        
        $cartModel = new CartModel();
        $order = $cartModel->findOrderById($orderId);
        if (!$order) {
            echo "Order not found.";
            exit();
        }

        $amount = (int)$order['totall_price'];

        $data = [
            "merchant_id" => "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", 
            "authority"   => $authority,
            "amount"      => $amount
        ];
        $jsonData = json_encode($data);

        $ch = curl_init('https://sandbox.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            echo "Payment verification failed (cURL error): $err";
            exit();
        }

        
        $result = json_decode($result, true);

        
        if (isset($result['data']['code'])) {
            $code = (int)$result['data']['code'];
            $ref_id = $result['data']['ref_id'] ?? null;

            if ($code === 100 || $code === 101) {
                $cartModel->updateOrderStatus($orderId, 'paid', $ref_id);
                unset($_SESSION['cart']);
                $_SESSION['success'] = "Payment successful. RefID: " . $ref_id;
                header("Location: /cart"); 
                exit();
            } else {
                
                $errMsg = $result['data']['message'] ?? 'Payment was not successful';
                $cartModel->updateOrderStatus($orderId, 'unpaid', null);
                echo "Payment failed: " . $errMsg;
                exit();
            }
        } else {
            echo "Invalid response from payment gateway.";
            exit();
        }
    }
}
