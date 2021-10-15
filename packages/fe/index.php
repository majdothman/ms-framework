<?php
// MS is the security, if run some file without index.php
define("MS", "MS");
$vendorPath = dirname(dirname(dirname(__FILE__)));
require_once $vendorPath . '/vendor/autoload.php';
require_once $vendorPath . '/vendor/mscore/core/Config/header.php';
try {

    /**  start App */
    $dispatcher = \MS\Core\Bootstrap\Dispatcher::dispatchFE();
    /** End App*/

} catch (Exception $exception) {
    \MS\Core\Controller\CoreException::writeError("index", $exception->getMessage(), "1540465671");
}
