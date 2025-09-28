<?php
require_once("jalali.php");
if (session_status() === PHP_SESSION_NONE) session_start();
$errors = $_SESSION["errors"] ?? [];
$old = $_SESSION["old"] ?? [];
unset($_SESSION["errors"], $_SESSION["old"]);

if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success text-center mt-2">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['delete'])): ?>
    <div class="alert alert-danger text-center mt-2">
        <?= htmlspecialchars($_SESSION['delete']) ?>
    </div>
<?php 
    unset($_SESSION['delete']);
endif;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modernize Free</title>
    <link rel="stylesheet" href="../assets/css/dataTables.dataTables.min.css">
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/richtexteditor/rte_theme_default.css" />
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
        .hover{
            color: blue;

        }
        .hover:hover{
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
                        <p class="position">   : محصولات شما    </p>
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
                        <th>ID</th>
                        <th>نام</th>
                        <th>تصویر</th>
                        <th>قیمت</th>
                        <th>سایز محصول</th>
                        <th>بازدید</th>
                        <th>توضیحات</th>
                        <th>تاریخ ساخت</th>
                        <th>تاریخ ویرایش</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($allProducts as $produts) {
                    ?>
                        <tr>
                            <td><?php echo $produts['id']; ?></td>
                            <td><?php echo $produts['name']; ?></td>
                            <td>
                                <img src="/media/<?php echo $produts['image']; ?>" width="100px" alt="">
                            </td>
                            <td><?php echo $produts['price']; ?></td>
                            <td><?php echo $produts['size']; ?></td>
                            <td><?php echo $produts['views']; ?></td>
                            <td><?php echo $produts['desc']; ?></td>
                            <td><?php echo toJalali($produts['created_at']); ?></td>
                            <td><?php echo toJalali($produts['updated_at']); ?></td>
                            <td>
                                <a class="hover" onclick="return confirm('میخوای پاک کنی واقعا!؟')" href="/admin_delete_product/<?php echo $produts['id']; ?>"> حذف </a>
                                <br>
                                 <a class="hover" onclick="return confirm('میخوای ویرایش کنی!؟')" href="/admin_edit_product/<?php echo $produts['id']; ?>"> ویرایش </a>
                            </td>
                        </tr>


                    <?php
                    }


                    ?>
                </tbody>

            </table>
        </div>



        <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="../assets/js/dataTables.min.js"></script>
        <script>
            let table = new DataTable('#myTable');
        </script>
        <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/sidebarmenu.js"></script>
        <script src="../assets/js/app.min.js"></script>
        <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
        <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
        <script src="../assets/js/dashboard.js"></script>


</body>

</html>