<?php
// MS is the security, if run some file without index.php
define("MS_BE", "MS_BE");

$vendorPath = dirname(dirname(dirname(__FILE__)));
require_once $vendorPath . '/vendor/autoload.php';
require_once $vendorPath . '/vendor/mscore/core/Config/header.php';


try {


    $userController = null;
    if (!defined("USER_DATA") || empty(USER_DATA) || USER_DATA['isActive'] == 0) {
        $userController = \MS\Core\Controller\UserController::getInstance();
        $userController->logoutAction();
    }


    /**  start App */
    $dispatcher = \MS\Core\Bootstrap\Dispatcher::dispatchBE();

    /** End App*/

} catch (Exception $exception) {
   \MS\Core\Controller\CoreException::writeError("index", $exception->getMessage(), "1540465671");
}
