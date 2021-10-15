<?php
if (!defined("MS") && !defined("MS_BE")) {
    die("Access Denied");
}

########### BASE_URL./
## OR
if (!defined("BASE_URL")) {
    define("BASE_URL", \MS\Core\Utility\MsUtility::baseUrl());
}
define("ROOT_PATH", rtrim(MS_ENV['SYS']['root_path'] . '/'));

############# [important]
####### Route -> parse -> request[controller], request[action]
define("REQUEST_CONTROLLER_IN", 1);
define("REQUEST_ACTION_IN", 2);

####### Backend ##################################################################
define("SITE_BE_PATH", rtrim(MS_ENV['SYS']['root_path'] . '/') . MS_ENV['SYS']['public_folder'] . '/mscms');
define("LAYOUTS_PATH", rtrim(SITE_BE_PATH, '/') . "/Resources/Private/Layouts");
define("TEMPLATE_PATH", rtrim(SITE_BE_PATH, '/') . "/Resources/Private/Templates");
define("PARTIALS_PATH", rtrim(SITE_BE_PATH, '/') . "/Resources/Private/Partials");

####### Frontend ##################################################################
define("SITE_FE_PATH", rtrim(MS_ENV['SYS']['root_path'] . '/') . MS_ENV['SYS']['public_folder'] . '/');
define("FE_LAYOUTS_PATH", rtrim(SITE_FE_PATH, '/') . "/Resources/Private/Layouts");
define("FE_TEMPLATE_PATH", rtrim(SITE_FE_PATH, '/') . "/Resources/Private/Templates");
define("FE_PARTIALS_PATH", rtrim(SITE_FE_PATH, '/') . "/Resources/Private/Partials");


//############# Messages
define("PAGE_NOT_FOUND", "This page doesn't found");
