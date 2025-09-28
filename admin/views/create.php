<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$errors = $_SESSION["errors"] ?? [];
$old = $_SESSION["old"] ?? [];
unset($_SESSION["errors"], $_SESSION["old"]);

if (!empty($_SESSION['success'])) {
  echo '<div class="alert alert-success text-center mt-2">' . htmlspecialchars($_SESSION['success']) . '</div>';
  unset($_SESSION['success']);
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modernize Free</title>
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
            <p class="position">    &#128522 محصول خود را اضافه کنید  </p>
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

    <div class="d-flex justify-content-center align-items-center ">
      <form class="p-5 col-md-7 col-md-offset-5" enctype="multipart/form-data" action="admin_dash_store" method="post" dir="rtl">

        <div class="mb-3">
          <label for="name" class="form-label direction">اسم محصول</label>
          <input type="text" autocomplete="off" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
            id="name" name="name" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>">
          <?php if (isset($errors['name'])): ?>
            <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="short_desc" class="form-label direction">توضیحات کوتاه</label>
          <input type="text" autocomplete="off" class="form-control <?php echo isset($errors['short_desc']) ? 'is-invalid' : ''; ?>"
            id="short_desc" name="short_desc" value="<?php echo htmlspecialchars($old['short_desc'] ?? ''); ?>">
          <?php if (isset($errors['short_desc'])): ?>
            <div class="invalid-feedback"><?php echo $errors['short_desc']; ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="price" class="form-label direction">قیمت (تومان)</label>
          <input type="text" autocomplete="off" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>"
            id="price" name="price" value="<?php echo htmlspecialchars($old['price'] ?? ''); ?>">
          <?php if (isset($errors['price'])): ?>
            <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label direction">تصویر</label>
          <input type="file"
            class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>"
            id="image"
            name="image">
          <?php if (isset($errors['image'])): ?>
            <div class="invalid-feedback">
              <?php echo $errors['image']; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label for="size" class="form-label direction">سایز محصول</label>
          <input type="text" class="form-control <?php echo isset($errors['size']) ? 'is-invalid' : ''; ?>"
            id="size" name="size" value="<?php echo htmlspecialchars($old['size'] ?? ''); ?>">
          <?php if (isset($errors['size'])): ?>
            <div class="invalid-feedback"><?php echo $errors['size']; ?></div>
          <?php endif; ?>
        </div>

      <div class="mb-3">
    <label class="form-label direction">دسته بندی ها</label>
    <?php foreach($allCategories as $category): ?>
        <label>
            <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>"
                <?php 
                if (isset($old['categories']) && in_array($category['id'], $old['categories'])) 
                    echo 'checked'; 
                ?>>
            <?= htmlspecialchars($category['name']) ?>
        </label>
    <?php endforeach; ?>
    <?php if (isset($errors['categories'])): ?>
        <div class="text-danger mt-1"><?= $errors['categories'] ?></div>
    <?php endif; ?>
</div>
        <div class="mb-3">
          <label for="in_stock" class="form-label direction">موجودی محصول</label>
          <input type="text" autocomplete="off" class="form-control <?php echo isset($errors['in_stock']) ? 'is-invalid' : ''; ?>"
            id="in_stock" name="in_stock" value="<?php echo htmlspecialchars($old['in_stock'] ?? ''); ?>">
          <?php if (isset($errors['in_stock'])): ?>
            <div class="invalid-feedback"><?php echo $errors['in_stock']; ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="text_editor" class="form-label direction">نقد و بررسی</label>
          <textarea rows="7" autocomplete="off" class="form-control <?php echo isset($errors['desc']) ? 'is-invalid' : ''; ?>"
            name="desc" id="text_editor"><?php echo htmlspecialchars($old['desc'] ?? ''); ?></textarea>
          <?php if (isset($errors['desc'])): ?>
            <div class="invalid-feedback"><?php echo $errors['desc']; ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" name="save_product" class="btn btn-primary w-100">ارسال</button>
      </form>
    </div>


    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script type="text/javascript" src="../assets/richtexteditor/rte.js"></script>
    <script type="text/javascript" src='../assets/richtexteditor/plugins/all_plugins.js'></script>
    <script>
      var editor1 = new RichTextEditor("#text_editor");
    </script>
    <script>
      setTimeout(function() {
        const successDiv = document.getElementById('flash-success');
        if (successDiv) successDiv.style.display = 'none';

        const errorDiv = document.getElementById('flash-error');
        if (errorDiv) errorDiv.style.display = 'none';
      }, 2000);
    </script>
    <script>
      window.addEventListener('load', function() {
        const inputs = document.querySelectorAll(' #text_editor textarea');
        inputs.forEach(input => input.value = '');
      });
    </script>

</body>

</html>