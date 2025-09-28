<?php
require_once("jalali.php");
if (session_status() === PHP_SESSION_NONE) session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1); // You can turn this off after debugging
// header('Content-Type: application/json');

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modernize Free</title>
    <link rel="stylesheet" href="/assets/css/dataTables.dataTables.min.css">
    <link rel="shortcut icon" type="image/png" href="/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="/assets/css/styles.min.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/richtexteditor/rte_theme_default.css" />
    <style>
        .direction {
            display: block;
            text-align: start !important;
        }

        .position {
            font-size: 25px;
            margin-right: 150px;
            margin-top: 20px;
        }

        .table-container {
            margin-left: 350px;
            padding: 20px;
            width: calc(90% - 250px);
            box-sizing: border-box;
        }

        .hover {
            color: blue;

        }

        .hover:hover {
            color: yellow;
        }
    </style>
</head>

<body>
    <?php
    require_once("admin/views/sidebar.php");
    ?>
    </div>
    <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
        <!--  Header Start -->
        <header class="app-header">
            <nav class="navbar navbar-expand-lg navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item d-block d-xl-none">
                        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                            <i class="ti ti-bell-ringing"></i>
                            <div class="notification bg-primary rounded-circle"></div>
                        </a>
                    </li>
                </ul>
                <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                        <p class="position"> : سفارشات </p>
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                <div class="message-body">
                                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                        <i class="ti ti-user fs-6"></i>
                                        <p class="mb-0 fs-3">My Profile</p>
                                    </a>
                                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                        <i class="ti ti-mail fs-6"></i>
                                        <p class="mb-0 fs-3">My Account</p>
                                    </a>
                                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                        <i class="ti ti-list-check fs-6"></i>
                                        <p class="mb-0 fs-3">My Task</p>
                                    </a>
                                    <a href="/logout" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!--  Header End -->

        <div class="table-container">
            <table id="myTable" class="display table-container" dir="rtl">
                <thead>
                    <tr>
                        <th> ID </th>
                        <th>وضعیت</th>
                        <th>کد رهگیری</th>
                        <th>ایمیل</th>
                        <th>تاریخ ساخت</th>
                        <th>آدرس</th>
                        <th>شماره همراه</th>
                        <th>مجموع خرید</th>
                        <th>تعداد</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $statusLabels = [
        'payed' => 'پرداخت شده',
        'In_progress' => 'درحال آماده سازی',
        'Ready_to_send' => 'آماده برای ارسال',
        'Sent' => 'ارسال شده',
        'Delivered' => 'تحویل شده',
        'Canceled' => 'لغو شده'
    ];

    foreach ($allOrders as $order):
    ?>
        <tr>
            <td><?php echo htmlspecialchars($order['id']); ?></td>
            <td>
                <select name="status" class="order_status_handler" data-id="<?php echo $order['id']; ?>">
                    <?php foreach ($statusLabels as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($order['status'] === $key) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><?php echo htmlspecialchars($order['payment_ref']); ?></td>
            <td><?php echo htmlspecialchars($order['user_email']); ?></td>
            <td><?php echo htmlspecialchars(toJalali($order['created_at'])); ?></td>
            <td><?php echo htmlspecialchars($order['address']); ?></td>
            <td><?php echo htmlspecialchars($order['number']); ?></td>
            <td><?php echo htmlspecialchars($order['totall_price']); ?></td>
            <td><?php echo htmlspecialchars($order['count']); ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>

        </div>



        <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
        <script>
            $(document).on("change", ".order_status_handler", function() {
                const orderId = $(this).attr("data-id");
                const status = $(this).val();
                console.log("changed!", orderId, status);

                $.ajax({
                    url: "/orders_status",
                    method: "POST",
                    data: {
                        orderId: orderId,
                        status: status
                    },
                    success: function(response) {
                        const data = typeof response === "string" ? JSON.parse(response) : response;
                        alert(data.message);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                        console.log(xhr.responseText);
                    }
                });
            });
        </script>

        <script src="/assets/js/dataTables.min.js"></script>
        <script>
            let table = new DataTable('#myTable');
        </script>
        <script src="/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/js/sidebarmenu.js"></script>
        <script src="/assets/js/app.min.js"></script>
        <script src="/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
        <script src="/assets/libs/simplebar/dist/simplebar.js"></script>
        <script src="/assets/js/dashboard.js"></script>




</body>

</html>