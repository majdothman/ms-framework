<?php
session_start();
if (!defined("MS") && !defined("MS_BE")) {
    die("Access Denied");
}

/** Load MS_ENV array */
define('MS_ENV', \MS\Core\Utility\MsUtility::yamlParse(dirname(__FILE__, 4) . '/config.yaml')['MS']);
define("RUN_EXCEPTION", MS_ENV['SYS']['enable_log']);
if (!RUN_EXCEPTION) {
    error_reporting(0);
}
if (MS_ENV['SYS']['enable_log'] === 0) {
    error_reporting(0);
}

/**
 * Define
 */

// find index.php
// detect document root (if docRoot is not equal to the docRoot defined in Host etc.)
if (!defined("DOC_ROOT")) {
    $alternativeDocRootPath = str_replace([$_SERVER['DOCUMENT_ROOT'], '/index.php'], '', $_SERVER['SCRIPT_FILENAME']);
    if (!empty($alternativeDocRootPath)) {
        define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT'] . $alternativeDocRootPath);
    } else {
        define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT']);
    }
}
date_default_timezone_set('Europe/Berlin');


$now = new DateTime();
define("TIME_STAMP", (int)$now->getTimestamp());
define("DATE_STAMP", date('Y-m-d H:i'));

/** get Ms Config of DB and Override */
$msConfig = \MS\Core\Utility\QueryBuilder::getInstance();
$msConfig = $msConfig->select()->setTableName('ms_configuration')->columns()->execute();

if (empty($msConfig)) {
    \MS\Core\Bootstrap\Dispatcher::setupDB();
    die();
}
define("MSCONFIG", $msConfig[0]);

require_once 'constants.php';
require_once 'globals.php';
if (defined('MS_BE') && isset($_COOKIE['uid']) && $_COOKIE['uid'] > 0) {
    if (!defined("USER_DATA")) {
        $userController = MS\Core\Controller\UserController::getInstance();
        $userData = $userController->updateUserDataInCookiesAction($_COOKIE['uid']);
        if (!empty($userData)) {
            define("USER_DATA", ($userData));
            if ((int)(USER_DATA['idUserGroups'] == 1 || (int)USER_DATA['idUserGroups'] == 2)) {
                define("IS_ADMIN", 1);
            } elseif ((int)(USER_DATA['idUserGroups'] == 3)) {
                define("IS_ADMIN", 0);
            } else {
                define("IS_ADMIN", 0);
            }
            if ((int)(USER_DATA['idUserGroups'] == 1)) {
                define("IS_SUPER_ADMIN", 1);
            }
            setrawcookie("FullName", rawurlencode(USER_DATA['firstname'] . ' ' . USER_DATA['lastname']), time() + 31556926, '/');
        }
    }
    /** if the user token not matched -> logout */
    if (!isset($_COOKIE["token"]) || ($_COOKIE["token"] != USER_DATA['token'])) {
        header('Location:' . BASE_URL . '/mscms/?controller=user&action=logout');
    }
}

/**
 * MS_LANGUAGES storage All languages
 */
$languages = \MS\Core\Model\Repository\MediaRepository::getInstance();
define("MS_LANGUAGES", $languages->findAllLanguages());

/**
 * storage which language to insert and update
 */
if (isset($_COOKIE['language']) && (int)$_COOKIE['language'] > 0) {
    $GLOBALS['BE']['languageId'] = (int)$_COOKIE['language'];
}
define('CURRENT_LANGUAGE', 1);

// update last activity time stamp
$_SESSION['LAST_ACTIVITY'] = time();
setcookie("LAST_ACTIVITY", $_SESSION['LAST_ACTIVITY'], time() + 31556926, '/');
