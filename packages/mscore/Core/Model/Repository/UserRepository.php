<?php

namespace MS\Core\Model\Repository;

use MS\Core\Model\Repositories;

class UserRepository extends Repositories
{
    protected static $instance = null;

    /**
     * Get instance of this Repository
     *
     * @return UserRepository|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * find all user, whatever hidden=0 or hidden=1
     *
     * @return array|bool|null
     */
    public function findAllForAdmin()
    {
        try {
            $result = $this->getQueryBuilder()
                ->select()
                ->setTableName('be_users')
                ->columns()
                ->orWhere()
                ->eq(['hidden' => 0])
                ->orWhere()
                ->eq(['hidden' => 1])
                ->execute();

            return $result;
        } catch (\Exception $exception) {
            \MS\Controller\CoreException::writeError("User-Repositories", $exception->getMessage(), "1554199192");

            return false;
        }
    }

    /**
     * find all where hidden=0
     *
     * @return array|bool|null
     */
    public function findAllActiveUsers()
    {
        try {
            $result = $this->getQueryBuilder()
                ->select()
                ->setTableName('be_users')
                ->columns()
                ->andWhere()
                ->eq(['hidden' => 0])
                ->orderBy(['idUserGroups'])
                ->execute();

            return $result;
        } catch (\Exception $exception) {
            \MS\Controller\CoreException::writeError("User-findAllActiveUsers", $exception->getMessage(), "1554199193");

            return false;
        }
    }

    public function createNewBeUser($firstname, $lastname, $username, $email, $password, $userGroup)
    {
        if (!$_POST) {
            return;
        }

        // @TODO if want active it user make isActive = 1
        $this->getQueryBuilder()
            ->insert()
            ->setTableName('be_users')
            ->setColumnsAndValues(
                [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'username' => $username,
                    'email' => $email,
                    'isActive' => 0,
                    'hidden' => 0,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'idUserGroups' => $userGroup,
                ]
            )
            ->execute();

        return $this->findAll('be_users');
    }

    /**
     * @param $firstname
     * @param $lastname
     * @param $username
     * @param $email
     * @param $password
     * @param $userGroup
     * @param $phone
     * @param $privateAnswer
     * @param string $address
     * @return int|string|void
     */
    public function register($firstname, $lastname, $username, $email, $password, $userGroup, $phone, $privateAnswer, $address = "")
    {
        if (!$_POST) {
            return;
        }

        $result = $this->getQueryBuilder()
            ->insert()
            ->setTableName('be_users')
            ->setColumnsAndValues(
                [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'username' => $username,
                    'email' => $email,
                    'isActive' => 0,
                    'hidden' => 0,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'idUserGroups' => $userGroup,
                    'phone' => $phone,
                    'privateAnswer' => $privateAnswer,
                    'address' => $address,
                ]
            )
            ->execute();

        return $result;
    }

    public function updateBeUser($columns)
    {
        if (!$_POST) {
            return;
        }

        $this->getQueryBuilder()
            ->update()
            ->setTableName('be_users')
            ->setColumnsAndValues(
                $columns
            )->where()
            ->eq(['uid' => (int)$_POST['uid']])
            ->execute();

        return $this->findAll('be_users');
    }

    /**
     * Update last active time for user
     * this update running every load page
     *
     * @param int $uid
     */
    public function updateLastActive($uid = -1)
    {
        $this->getQueryBuilder()
            ->update()
            ->setTableName('be_users')
            ->setColumnsAndValues(
                ['lastvisitDate' => TIME_STAMP]
            )->where()
            ->eq(['uid' => (int)$uid])
            ->execute();
    }

    /**
     * Update user-Account Active or UnActive
     * @param int $uid
     * @param $value
     * @return int|string
     */
    public function updateUserActive($uid, $value)
    {
        return $this->getQueryBuilder()
            ->update()
            ->setTableName('be_users')
            ->setColumnsAndValues(
                ['isActive' => (int)$value]
            )->where()
            ->eq(['uid' => (int)$uid])
            ->execute();
    }
}
