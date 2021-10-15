<?php

namespace MS\Core\Be\Repository;

use MS\Core\Model\Repositories;
use MS\Core\Utility\DB;

class SettingsRepository extends Repositories
{
    protected static $instance = null;

    /**
     * Get instance of this Class
     *
     * @return SettingsRepository
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return mixed
     */
    public function getOnlineUsers()
    {
        $analyse['now'] = date('d.m.Y , H:i', time());
        /**
         * Online Users, where is online && lastvisitDate between now and (now - 1 min)
         * ex: time() + (7 * 24 * 60 * 60); 7 days; 24 hours; 60 mins; 60 secs
         */
        $onlineUsers = $this->getQueryBuilder()
            ->select()
            ->setTableName('be_users')
            ->columns(['uid', 'firstname', 'lastname', 'lastvisitDate'])
            ->andWhere()
            ->eq(['isOnline' => 1])
            ->andWhere()
            ->biggerThen(['lastvisitDate' => (time() - 60)])
            ->limit(50)
            ->execute();
        $analyse['onlineUsers'] = $onlineUsers;
        return $analyse;
    }

    /**
     * runCommand method
     * @param $command
     * @return array|bool|null
     */
    public function runCommand($command)
    {
        try {
            $result = null;
            if (str_contains(strtolower($command), 'select')) {
                $result = DB::getInstance()->select($command);
            } else {
//            $result = DB::getInstance()->runNormalQuery($command);
                $result = 'For security you can only get data from SELECT command';
            }
            return $result;
        } catch (\Exception $exception) {
            return 'xxx';
        }
    }
}
