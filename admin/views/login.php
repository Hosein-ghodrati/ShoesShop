<?php
// admin/views/login.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Karma Login</title>
  <link rel="shortcut icon" type="image/png" href="../assets/img/fav.png" />
  <link rel="stylesheet" href="/assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    .password-toggle{
      position: absolute;
      right: 10px;
      top: 38px;
      cursor: pointer;
      font-size: 20px;
      color: #666;
    }
    .password-toggle:hover{
      color: #000;
    }
    .position-relative{ position: relative; }
  </style>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../assets/img/logo.png" width="180" alt="">
                </a>

             
                <?php
                if(!empty($_SESSION['error'])) {
                    echo "<div class='alert alert-danger text-center mt-2'>".htmlspecialchars($_SESSION['error'])."</div>";
                    unset($_SESSION['error']);
                }
                if(!empty($_SESSION['success'])) {
                    echo "<div class='alert alert-success text-center mt-2'>".htmlspecialchars($_SESSION['success'])."</div>";
                    unset($_SESSION['success']);
                }
                
                if(!empty($_SESSION['debug'])) {
                    echo "<pre style='color:purple'>DEBUG: ".htmlspecialchars(print_r($_SESSION['debug'], true))."</pre>";
                    unset($_SESSION['debug']);
                }
                ?>

                <p class="text-center">Your Social Campaigns</p>

                <form action="index.php?path=login_process" method="POST">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" placeholder="یوزرنیم یا ایمیل خود را وارد کنید" class="form-control" id="username" name="username" aria-describedby="emailHelp">
                  </div>

                  <div class="mb-4 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" placeholder="رمز خود را وارد کنید" class="form-control" id="password" name="password">
                    <i id="togglePassword" class="bi bi-eye password-toggle"></i>
                  </div>

                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remember this Device
                      </label>
                    </div>
                    <a class="text-primary fw-bold" href="#">Forgot Password ?</a>
                  </div>

                  <button type="submit" class="btn btn-warning w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>

                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">New to KARMA?</p>
                    <a class="text-primary fw-bold ms-2" href="/register">Create an account</a>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    if (togglePassword && password) {
      togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
      });
    }
  </script>
</body>
</html>
