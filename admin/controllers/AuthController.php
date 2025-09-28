<?php
class AuthController {

    function profile() {
        require_once(__DIR__ . "/../views/index.php");
    }

    function index() {
        require_once(__DIR__ . "/../views/login.php");
    }

    function register() {
        require_once(__DIR__ . "/../views/register.php");
    }

   public function registerProcess() {
    // اطمینان از شروع سشن
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

  
    require_once __DIR__ . '/../../config/database.php';
    /** @var mysqli $db */

   
    if (!isset($db) || !($db instanceof mysqli)) {
        $_SESSION['errors']['general'] = "مشکل داخلی: اتصال به دیتابیس برقرار نیست.";
        header("Location: /index.php?path=register");
        exit();
    }
    if ($db->connect_error) {
        $_SESSION['errors']['general'] = "خطای دیتابیس: " . $db->connect_error;
        header("Location: /index.php?path=register");
        exit();
    }

  
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /index.php?path=register");
        exit();
    }

  
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $errors = [];

   
    if ($username === '') {
        $errors['username'] = "لطفا یوزرنیم خود را وارد کنید";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "فرمت ایمیل وارد شده صحیح نمی‌باشد";
    }

    if ($password === '') {
        $errors['password'] = "لطفا پسورد خود را وارد کنید";
    }

    $_SESSION['old'] = ['username' => $username, 'email' => $email];

    if (empty($errors)) {
        $checkSql = "SELECT 1 FROM users WHERE username = ? OR email = ? LIMIT 1";
        $checkStmt = $db->prepare($checkSql);
        if (!$checkStmt) {
         
            $errors['general'] = "خطا در آماده‌سازی پرس‌وجو: " . $db->error;
        } else {
            $checkStmt->bind_param("ss", $username, $email);
            if (!$checkStmt->execute()) {
                $errors['general'] = "خطا در اجرای پرس‌وجو: " . $checkStmt->error;
            } else {
                $checkStmt->store_result();
                if ($checkStmt->num_rows > 0) {
                    $errors['username'] = "یوزرنیم یا ایمیل قبلا استفاده شده است";
                }
            }
            $checkStmt->close();
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertSql = "INSERT INTO users (username, email, password, Is_admin, Join_date) VALUES (?, ?, ?, 0, NOW())";
        $stmt = $db->prepare($insertSql);
        if (!$stmt) {
            $errors['general'] = "خطا در آماده‌سازی پرس‌وجو: " . $db->error;
        } else {
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            if (!$stmt->execute()) {
                $errors['general'] = "خطا در ثبت اطلاعات: " . $stmt->error;
            } else {
            
                $_SESSION['success'] = "ثبت نام با موفقیت انجام شد ✅";
                unset($_SESSION['old']);
                $stmt->close();

                header("Location: /index.php?path=login");
                exit();
            }
            if ($stmt) $stmt->close();
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: /index.php?path=register");
        exit();
    }

    header("Location: /index.php?path=register");
    exit();
}
public function loginprocess() {
    $DEBUG = false;

    if (session_status() === PHP_SESSION_NONE) session_start();

   
    require_once __DIR__ . '/../../config/database.php'; // مسیر دیتابیس
    /** @var mysqli $db */

  
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /index.php?path=login");
        exit();
    }

   
    if (!isset($db) || !($db instanceof mysqli)) {
        $_SESSION['error'] = "خطا در اتصال به دیتابیس. لطفاً بعدا تلاش کنید.";
        header("Location: /index.php?path=login");
        exit();
    }

    $loginInput = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');

    if ($loginInput === '') {
        $_SESSION['error'] = "لطفا یوزرنیم یا ایمیل را وارد کنید";
        header("Location: /index.php?path=login");
        exit();
    }
    if ($password === '') {
        $_SESSION['error'] = "لطفا پسورد را وارد کنید";
        header("Location: /index.php?path=login");
        exit();
    }
    if (strpos($loginInput, '@') !== false && !filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "فرمت ایمیل وارد شده صحیح نمی‌باشد";
        header("Location: /index.php?path=login");
        exit();
    }

    $sql = "SELECT username, email, password AS db_password, Is_admin AS is_admin FROM users WHERE username = ? OR email = ? LIMIT 1";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        $_SESSION['error'] = "خطا در سرور. لطفاً دوباره تلاش کنید";
        header("Location: /index.php?path=login");
        exit();
    }

    
    $stmt->bind_param("ss", $loginInput, $loginInput);
    if (!$stmt->execute()) {
        $_SESSION['error'] = "خطا در اجرای پرس‌وجو. لطفاً دوباره تلاش کنید";
        $stmt->close();
        header("Location: /index.php?path=login");
        exit();
    }

   
    $user = null;
    if (method_exists($stmt, 'get_result')) {
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
           $user = [
    'username' => $row['username'] ?? '',
    'email'    => $row['email'] ?? '',
    'db_password' => $row['db_password'] ?? '',
    'is_admin' => isset($row['is_admin']) ? (int)$row['is_admin'] : 0
];
        }
    } else {
       
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_username, $db_password_alias, $db_is_admin);
            $stmt->fetch();
            $user = [
                'username' => $db_username,
                'db_password' => $db_password_alias,
                'is_admin' => isset($db_is_admin) ? (int)$db_is_admin : 0
            ];
        }
    }
    $stmt->close();

    
    if (!$user) {
        $_SESSION['error'] = "یوزرنیم یا ایمیل پیدا نشد";
        header("Location: /index.php?path=login");
        exit();
    }

   
    if ($DEBUG) {
        $_SESSION['debug'] = [
            'loginInput' => $loginInput,
            'db_password_len' => strlen((string)$user['db_password']),
            'db_password_prefix' => substr((string)$user['db_password'], 0, 10)
        ];
    }

    $dbHash = (string) ($user['db_password'] ?? '');

    $passwordOk = false;

    if ($dbHash !== '' && password_verify($password, $dbHash)) {
        $passwordOk = true;
    } else {
        
        if (is_string($dbHash) && strlen($dbHash) === 32) {
            if (hash_equals($dbHash, md5($password))) {
                $passwordOk = true;
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $up = $db->prepare("UPDATE users SET password = ? WHERE username = ? LIMIT 1");
                if ($up) {
                    $unameForUpdate = $user['username'];
                    $up->bind_param("ss", $newHash, $unameForUpdate);
                    $up->execute();
                    $up->close();
                }
            }
        }
    }

    if (!$passwordOk) {
        usleep(150000);
        $_SESSION['error'] = "رمز عبور اشتباه است";
        header("Location: /index.php?path=login");
        exit();
    }

    
 $_SESSION['user'] = [
    'username' => $user['username'],
    'email'    => $user['email'],   
    'is_admin' => $user['is_admin'] ?? 0
];
    session_regenerate_id(true);
    $_SESSION['success'] = "خوش آمدید، " . htmlspecialchars($user['username']) . "!";

    // redirect
    if (($user['is_admin'] ?? 0) === 1) {
        header("Location: /index.php?path=profile");
        exit();
    } else {
        header("Location: /index.php");
        exit();
    }
}



}








