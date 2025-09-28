<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
$username = $_SESSION['old']['username'] ?? '';
$email = $_SESSION['old']['email'] ?? '';

unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karma Register</title>
    <link rel="shortcut icon" type="image/png" href="/assets/img/fav.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="../assets/img/logo.png" width="180" alt="">
                                </a>
                                <p class="text-center">Your Social Campaigns</p>

                                <?php if (!empty($success)) : ?>
                                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                                <?php endif; ?>

                                <?php if (!empty($errors['general'])) : ?>
                                    <div class="alert alert-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
                                <?php endif; ?>

                                <form action="/index.php?path=register_process" method="POST">
                                    <div class="mb-3">
                                        <label for="exampleInputtext1" class="form-label">Username</label>
                                        <input type="text" placeholder="یوزرنیم خود را وارد کنید" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                                            id="exampleInputtext1" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                        <?php if (isset($errors['username'])) : ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['username']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Email Address</label>
                                        <input type="email" placeholder="ایمیل خود را وارد کنید" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                            id="exampleInputEmail1" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                        <?php if (isset($errors['email'])) : ?>
                                            <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4 position-relative">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" placeholder="رمز خود را وارد کنید" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                                                id="exampleInputPassword1" name="password">
                                            <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                                <i class="bi bi-eye"></i>
                                            </span>
                                            <?php if (isset($errors['password'])) : ?>
                                                <div class="invalid-feedback d-block"><?php echo htmlspecialchars($errors['password']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-warning w-100 py-8 fs-4 mb-4 rounded-2">
                                        Sign Up
                                    </button>

                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                                        <a class="text-primary fw-bold ms-2" href="/login">Sign In</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordButton = document.querySelector('#togglePassword');
            if (togglePasswordButton) {
                togglePasswordButton.addEventListener('click', function() {
                    const passwordInput = document.querySelector('#exampleInputPassword1');
                    const icon = this.querySelector('i');
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                });
            }
        });
    </script>
</body>

</html>