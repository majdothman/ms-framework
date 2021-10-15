<?php

// for cache files
$versionTimestamp = "1582202757";

// MS is the security, if run some file without index.php
define("MS_BE", "MS_BE");

use MS\Core\Controller\UserController;

$vendorPath = dirname(dirname(dirname(__FILE__)));
require_once $vendorPath . '/vendor/autoload.php';
require_once $vendorPath . '/vendor/mscore/core/Config/header.php';

try {
    /** If logged in */
    if (defined("USER_DATA") && !empty(USER_DATA)) {
        header("Location:" . BASE_URL . "mscms/index.php");

    }
    /** error_message for Reset */
    $error_message = '';

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
        <title> MsCMS | Reset Password </title>
        <link rel="stylesheet" href="<?php echo BASE_URL ?>/mscms/Resources/assets/css/bootstrap.min.css?v=<?= $versionTimestamp ?>">
    </head>
    <body class="container-fluid" style="background-color: aliceblue">
    <?php
    $userController = UserController::getInstance();
    if ($_POST) {
        if (!isset($_POST['password']) || !isset($_POST['re-password']) || $_POST['password'] != $_POST['re-password']) {
            $error_message = 'Passwords do not match';
        } else {
            $resetResult = $userController->resetPassword();
            if ($resetResult) {
                $loginLink = '<a href="' . BASE_URL . '/mscms/">Login</a>';
                \MS\Core\Utility\MsUtility::fe_dump('Your Password changed ' . $loginLink, 'success', 1634331422);

                return;
            }
        }
    }

    $hashIsActive = false;
    if (isset($_GET['hash']) && !empty($_GET['hash'])) {
        $isHashExist = UserController::getInstance()->isHashExist($_GET['hash']);
        if ($isHashExist) {
            $hashIsActive = true;
        }
    }
    ?>

    <div class="row" style="margin-top: 15%">
        <div class="col-3"></div>
        <div class="col-6 border p-4 bg-white">
            <div class="  aligns-items-center justify-content-center">
                <p class="text-center">
                    <img src="<?php echo BASE_URL ?>/mscms/Resources/assets/images/logos/MsCMS.png" alt="MsCMS logo">
                </p>
                <h1>Reset Password
                    <hr>
                </h1>
                <?php
                if ($hashIsActive) {
                    ?>
                    <form method="post" class="row g-3">
                        <div class="mb-1">
                            <label for="Password">Password *</label>
                            <input type="password" class="form-control ms-input-text" id="password" name="password"
                                   minlength="8"
                                   placeholder="Password"
                                   value="<?= (isset($_POST['password'])) ? $_POST['password'] : '' ?>" required>
                        </div>
                        <div class="mb-1">
                            <label for="Password">Repeat Password *</label>
                            <input type="password" class="form-control ms-input-text" id="re-password"
                                   name="re-password"
                                   minlength="8"
                                   placeholder="Repeat password"
                                   value="" required>
                            <input type="hidden" name="hash" value="<?= $_GET['hash'] ?>">
                        </div>
                        <button type="submit" id="reset" class="btn btn-outline-warning">Reset</button>
                        <a href="<?= BASE_URL ?>/mscms/" class="btn btn-link"> Login</a>
                        <span class="text-danger"><?php echo $error_message; ?></span>
                    </form>
                    <?php
                } else {
                    ?>

                    <div class="text-danger">
                        <h3>Link not active any more. <br></h3>
                        <a href="<?= BASE_URL ?>/mscms/"> Go back</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    </body>
    </html>

    <?php
} catch (Exception $exception) {
    \MS\Controller\CoreException::writeError("index", $exception->getMessage(), "1563883764");
}
