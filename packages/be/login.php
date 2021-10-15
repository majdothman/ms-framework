<?php

// for cache files
$versionTimestamp = "1582202757";
// MS is the security, if run some file without index.php
define("MS_BE", "MS_BE");

$vendorPath = dirname(dirname(dirname(__FILE__)));
require_once $vendorPath . '/vendor/autoload.php';
require_once $vendorPath . '/vendor/mscore/core/Config/header.php';

try {
    /** If logged in */
    if (defined("USER_DATA") && !empty(USER_DATA)) {
        header("Location:" . BASE_URL . "/mscms/index.php");

    }
    /** error_message for login */
    $error_message = '';
    $userController = \MS\Core\Controller\UserController::getInstance();
    if ($_POST) {
        $loginResult = $userController->loginAction();
        if ($loginResult && !is_array($loginResult)) {
            if (isset($_GET['href']) && !empty($_GET['href'])) {
                header("Location:" . urldecode($_GET['href']));
            } else {
                header("Location:" . BASE_URL . "/mscms/index.php");
            }
        } else {
            $error_message = '* ' . $loginResult[0];
        }
    }

    if (!RUN_EXCEPTION) {
        error_reporting(0);
    }

    ?>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/favicon-16x16.png">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <title> MsCMS Login </title>
        <link rel="stylesheet" href="<?php echo BASE_URL ?>/mscms/Resources/assets/css/bootstrap.min.css?v=<?= $versionTimestamp ?>">
    </head>
    <body class="container-fluid" style="background-color: aliceblue">
    <div class="row" style="margin-top: 15%">
        <div class="col-3"></div>
        <div class="col-6 border p-4 bg-white">
            <div class="  aligns-items-center justify-content-center">
                <p class="text-center">
                    <img src="<?php echo BASE_URL ?>/mscms/Resources/assets/images/logos/MsCMS.png" alt="MsCMS logo">
                </p>
                <h1>Login <br></h1>
                <form method="post" class="row g-3">
                    <div class="mb-1">
                        <label for="Email">Email or Username *</label>
                        <input type="text" class="form-control" id="email" name="email"
                               placeholder="Email"
                               value="<?php echo isset($_POST['email'])
                                   ? $_POST['email']
                                   : ''; ?>">
                    </div>
                    <div class="mb-1">
                        <label for="Password">Password *</label>
                        <input type="password" class="form-control" id="password" name="password"
                               minlength="2"
                               placeholder="Password"
                               value="">
                    </div>
                    <div class="mb-1">
                        <button type="submit" id="login" class="btn btn-outline-secondary">Log in</button>
                        <a type="button" class="btn-link" data-bs-toggle="modal" data-bs-target="#forgot-password-Modal">
                            Reset my password
                        </a>
                    </div>
                    <div class="mb-1"><span class="text-danger"><?php echo $error_message; ?></span></div>
                </form>
            </div>
        </div>
    </div>
    <?php
    require_once PARTIALS_PATH . '/Users/Modal/ForgotPassword.php';
    ?>
    <script src="<?php echo BASE_URL ?>/mscms/Resources/assets/js/bootstrap.bundle.min.js?v=<?= $versionTimestamp ?>"></script>
    </body>
    </html>

    <?php
} catch (Exception $exception) {
    \MS\Core\Controller\CoreException::writeError("index", $exception->getMessage(), "1540465673");
}
