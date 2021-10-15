<?php

namespace MS\Fe\Repository;

use MS\Core\Model\Repositories;

class HomeRepository extends Repositories
{
    protected static $instance = null;

    /**
     * Get instance of this Class
     *
     * @return HomeRepository
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getData(): array
    {
        $data = [];
        /**
         * You can here a query building to get Data from Database and returned to Controller
         * ex:
         * $onlineUsers = $this->getQueryBuilder()
         * ->select()
         * ->setTableName('be_users')
         * ->columns(['uid', 'firstname', 'lastname', 'lastvisitDate'])
         * ->andWhere()
         * ->eq(['isOnline' => 1])
         * ->andWhere()
         * ->biggerThen(['lastvisitDate' => (time() - 60)])
         * ->limit(50)
         * ->execute();
         */
        return $data;
    }
}
