<?php
if (!isset($_SESSION)) session_start();

//شمارش تعداد خرید
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="assets/img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>Shoes Shop</title>
    <!--
		CSS
		============================================= -->
    <!-- برای سکشن دسته بندی -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/assets/css/vendor.css">
    <link rel="stylesheet" type="text/css" href="/assets/style.css">
    <!-- برای سکشن دسته بندی -->
    <link rel="stylesheet" href="/assets/css/linearicons.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.css">
    <link rel="stylesheet" href="/assets/css/nice-select.css">
    <link rel="stylesheet" href="/assets/css/nouislider.min.css">
    <link rel="stylesheet" href="/assets/css/ion.rangeSlider.css" />
    <link rel="stylesheet" href="/assets/css/ion.rangeSlider.skinFlat.css" />
    <link rel="stylesheet" href="/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <style>
        #flash-success,
        #flash-error {
            position: fixed;
            top: 130px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 700px;
            display: none;
        }

        .img_category {
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }

        .text_category {
            text-align: center;
            margin: 20px 40px 0 45px;
        }

        .single-product-page {
            margin-top: 120px;
            text-align: center;
        }


        .product-image img.single-product-page {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* crops image to fill container without stretching */
            border-radius: 10px 10px 10px 10px;
        }

        /* search bar */
        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 50%;
            border: 1px solid #a2f7f3ff;
            background: #ffe7aaff;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }

        #search-results .search-item {
            padding: 8px;
            cursor: pointer;
        }

        #search-results .search-item:hover {
            background: #f0f0f0;
        }
    </style>
</head>

<body>

    <!-- Start Header Area -->
    <header class="header_area sticky-header">
        <div class="main_menu">
            <nav class="navbar navbar-expand-lg navbar-light main_box">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="#"><img src="/assets/img/logo.png" alt=""></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <ul class="nav navbar-nav menu_nav ml-auto">
                            <li class="nav-item active"><a class="nav-link" href="/">Home</a></li>

                            <?php
                            require_once("client/controllers/HomeController.php");
                            $HomeController = new HomeController();
                            $categoriesDropdown = $HomeController->getCategoriesDropdown();
                            ?>

                            <li class="nav-item submenu dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories</a>
                                <ul class="dropdown-menu">
                                    <?php foreach ($categoriesDropdown as $cat): ?>
                                        <li class="nav-item dropdown-submenu">
                                            <a class="nav-link dropdown-toggle" href="/category/<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>

                            <li class="nav-item submenu dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                    aria-expanded="false">Blog</a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item"><a class="nav-link" href="/blog">Blog</a></li>
                                    <li class="nav-item"><a class="nav-link" href="/blog">Blog Details</a></li>
                                </ul>
                            </li>
                            <?php if (empty($_SESSION['user'])): ?>
                                <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                            <?php endif; ?>
                            <li class="nav-item"><a class="nav-link" href="/GitHub">Contact</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">


                            <li class="nav-item" style="position: relative;">
                                <a href="/cart" class="cart" style="position: relative; display: inline-block;">
                                    <span class="ti-bag" title="سبد خرید" style="font-size: 24px;"></span>

                                    <?php if ($cartCount > 0): ?>
                                        <span style="
                                                position: absolute;
                                                top: 20px;
                                                right: -5px;
                                                background: red;
                                                color: white;
                                                border-radius: 50%;
                                                width: 18px;
                                                height: 18px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                font-size: 12px;
                                                font-weight: bold;
                                                ">
                                            <?php echo $cartCount; ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </li>


                            <li class="nav-item">
                                <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
                            </li>
                            <?php if (!empty($_SESSION['user'])): ?>
                                <li style="margin-top: 18px;" class="nav-item">
                                    <a class="nav-link" href="/index.php?path=logout">Logout</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="search_input" id="search_input_box">
            <div class="container" style="position:relative;">
                <form class="d-flex justify-content-between" onsubmit="return false;">
                    <input type="text" class="form-control" id="search_input" placeholder="Search Here" autocomplete="off">
                    <button type="submit" class="btn"></button>
                    <span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
                </form>

                <div id="search-results" style="display:none;"></div>
            </div>
        </div>

        <!-- پیام خوش آمدید بالای صفحه -->
        <div id="flash-success" class="alert alert-success text-center" role="alert">
            <?php
            if (!empty($_SESSION['success'])) {
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
            }
            ?>
        </div>

        <div id="flash-error" class="alert alert-danger text-center" role="alert">
            <?php
            if (!empty($_SESSION['error'])) {
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
            }
            ?>
        </div>
    </header>
    <script>
        const successDiv = document.getElementById('flash-success');
        if (successDiv.textContent.trim() !== "") successDiv.style.display = 'block';

        const errorDiv = document.getElementById('flash-error');
        if (errorDiv.textContent.trim() !== "") errorDiv.style.display = 'block';

        setTimeout(() => {
            if (successDiv.style.display === 'block') successDiv.style.transition = "opacity 0.5s", successDiv.style.opacity = 0;
            if (errorDiv.style.display === 'block') errorDiv.style.transition = "opacity 0.5s", errorDiv.style.opacity = 0;
        }, 3000);
    </script>