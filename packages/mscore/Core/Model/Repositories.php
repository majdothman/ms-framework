<?php

namespace MS\Core\Model;

use MS\Core\Controller\MediaController;
use MS\Core\Inerface\Repository;
use MS\Core\Utility\DB;
use MS\Core\Utility\QueryBuilder;

/**
 * Parent class
 * Class Repositories
 *
 */
class Repositories
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * Repositories constructor.
     */
    protected function __construct()
    {
        $this->queryBuilder = QueryBuilder::getInstance();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * connect with DB and Delete Query
     *
     * @param null $sql
     * @param null $args
     * @return bool|int|string
     */
    protected function deleteQuery($sql = null, $args = null)
    {
        try {
            return DB::getInstance()->delete($sql, $args);
        } catch (\Exception $exception) {
            \MS\Controller\CoreException::writeError("Repositories", $exception->getMessage(), "1543783313");

            return false;
        }
    }

    /**
     * @param null $tableName
     * @param $where
     * @param $sort
     * @return array|bool|null
     */
    public function findBy($tableName = null, $where = [], $orderType = 'ASC')
    {
        try {
            $sort = 'asc';
            if (strtolower($orderType) != 'asc') {
                $sort = 'desc';
            }
            $result = $this->getQueryBuilder()
                ->select()
                ->setTableName($tableName)
                ->columns()
                ->andWhere()
                ->eq($where)
                ->orderBy(['uid'])
                ->$sort()
                ->execute();

            return $result;
        } catch (\Exception $exception) {
            \MS\Controller\CoreException::writeError("Repositories", $exception->getMessage(), "1540554529");

            return false;
        }
    }

    /**
     * $tableName: 'String', name fo the table
     * $uid: uid in the table
     *
     * @param null $tableName
     * @param int $uid
     * @return array|bool|null
     */
    public function findByUid($tableName = null, $uid = -2)
    {
        try {
            $result = $this->getQueryBuilder()
                ->select()
                ->setTableName($tableName)
                ->columns()
                ->andWhere()
                ->eq(['uid' => (int)$uid, 'hidden' => 0])
                ->execute();

            return $result;
        } catch (\Exception $exception) {
            \MS\Controller\CoreException::writeError("Repositories", $exception->getMessage(), "1540554529");

            return false;
        }
    }

    /**
     * $tableName: 'String', name fo the table
     *
     * @param null $tableName
     * @return array|bool|null
     */
    public function findAll($tableName = null)
    {
        try {
            $result = $this->getQueryBuilder()
                ->select()
                ->setTableName($tableName)
                ->columns()
                ->execute();

            return $result;
        } catch (\Exception $exception) {
            \MS\Controller\CoreException::writeError("Repositories", $exception->getMessage(), "1540554530");

            return false;
        }
    }

    /**
     * update fields, where ...etc
     *
     * @param $table
     * @param array $field
     * @param array $where
     * @return int|string|void
     */
    public function updateField($table, $field = [], $where = [])
    {
        if (!$_POST) {
            return;
        }

        $lastInsertId = $this->getQueryBuilder()
            ->update()
            ->setTableName($table)
            ->setColumnsAndValues(
                $field
            )->where()
            ->eq($where)
            ->execute();

        return $lastInsertId;
    }

    /**
     * @param $table
     * @param array $fieldAndValues
     * @return int|string
     */
    public function insertRecord($table, $fieldAndValues = [])
    {
        $lastInsertId = $this->getQueryBuilder()
            ->insert()
            ->setTableName($table)
            ->setColumnsAndValues(
                $fieldAndValues
            )
            ->execute();

        return $lastInsertId;
    }

    /**
     * Return all languages from DB
     */
    public function findAllLanguages()
    {
        $languages = $this->getQueryBuilder()
            ->select()
            ->setTableName('ms_languages')
            ->columns()
            ->execute();

        return $languages;
    }

    /**
     * runCommand method
     * @param $command
     * @return array|bool|null
     */
    public function runCommand($command)
    {
        $result = null;
        if (strpos(strtolower($command), 'select') >= 0) {
            $result = DB::getInstance()->select($command);
        } else {
            $result = DB::getInstance()->runNormalQuery($command);
        }
        return $result;
    }
}
