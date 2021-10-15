<?php

namespace MS\Core\Controller;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class CoreException
 *
 * @package MS\Controller
 */
class CoreException
{
    private static $logFile = ROOT_PATH . "/.Log/log.txt";

    public static function writeError($title = "NoTitle", $message = "NoMsg", $code = "NoCode", $file = '', $line = '')
    {
        /** if not stop log */
        if (!MS_ENV['SYS']['enable_log'])
            return;
        $Tstamp = !empty($Tstamp)
            ? $Tstamp
            : TIME_STAMP;
        $log = new Logger($title);
        $log->pushHandler(new StreamHandler(CoreException::$logFile, Logger::ERROR));
        $log->error($message, ['TimeStamp:' . $Tstamp, 'code:' . $code]);
        CoreException::runExeption($message . ' - <small>  ' . basename($file) . ':' . $line . '</small>', $code);
    }

    public static function writeWarning($title = "NoTitle", $message = "NoMessage", $code = "NoCode")
    {
        /** if not stop log */
        if (!MS_ENV['SYS']['enable_log'])
            return;
        $Tstamp = !empty($Tstamp)
            ? $Tstamp
            : TIME_STAMP;
        $log = new Logger($title);
        $log->pushHandler(new StreamHandler(CoreException::$logFile, Logger::WARNING));
        $log->warning($message, ['TimeStamp:' . $Tstamp, 'code:' . $code]);
        CoreException::runExeption($message, $code);
    }

    /**
     * Run Exception in FE
     *
     * @param string $message
     * @param string $code
     */
    private static function runExeption($message = "NoMessage", $code = "NoCode")
    {
        /** if not stop log */
        if (!MS_ENV['SYS']['enable_log'])
            return;
        if (defined("RUN_EXCEPTION")) {
            if (RUN_EXCEPTION) {
                try {
                    throw new \Exception($message . '', (int)$code);
                } catch (Exception $exception) {
                    echo $exception->getMessage() . '...etc | see log to find code';
                    die();
                }
            }
        }
    }
}
