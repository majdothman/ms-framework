<?php

namespace MS\Core\Controller;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class CoreActivity
 *
 * @package MS\Controller
 */
class CoreActivity
{
    private static $activitiesFile = ROOT_PATH . "/activities.txt";


    public static function writeActivity($activity = "noActivity", $toTable = '', $data = "NoData")
    {
        /** if not stop log activities */
        if (!MS_ENV['SYS']['enable_activities'])
            return;
        $userName = 'Sys';
        if (defined("USER_DATA")) {
            $userName = '[' . USER_DATA["uid"] . '] ' . USER_DATA["firstname"] . ' ' . USER_DATA["lastname"];

            $activitiesFile = rtrim(dirname(ROOT_PATH), '/') . '/activities.txt';
            $log = new Logger(strtoupper($activity));
            $log->pushHandler(new StreamHandler($activitiesFile, Logger::INFO));
            $log->info(
                substr($data, 0, 50) . ' ...',
                ['table' => $toTable, 'execute By' => $userName, 'AT' => date('Y.m.d H:i', TIME_STAMP)]
            );
        }

        return;
    }
}
