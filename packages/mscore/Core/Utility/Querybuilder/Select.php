<?php

namespace MS\Core\Utility\Querybuilder;

use MS\Core\Utility\QueryBuilder;

/**
 *  * Example for JOIN
 *  $this->getQueryBuilder()
 *  ->select()
 *  ->setTableName($tableName)
 *  ->columns([$tableName.'.*','$tableName2.*'])
 *  ->rightJoin('ms_media')
 *  ->on($tableName.'.uid=$tableName2.'..pageId')
 *  ->andWhere()
 *  ->eq([$tableName.'.uid' => (int)$uid])
 *  ->execute()
 */

/**
 * Class Select
 *
 * @package MS\Core\Utility\Querybuilder
 */
class Select extends QueryBuilder
{
    /**
     * @param array $columns
     * @return $this
     */
    public function columns($columns = ['*'])
    {
        if (!empty($columns)) {
            for ($i = 0; $i < count($columns); $i++) {
                ($i + 1 < count($columns))
                    ? $this->columns .= $columns[$i] . ', '
                    : $this->columns .= $columns[$i];
            }
        }

        return $this;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function functions($columns = ['*'])
    {
        if (!empty($this->columns)) {
            $this->columns .= ', ';
        }
        if (!empty($columns)) {
            for ($i = 0; $i < count($columns); $i++) {
                ($i + 1 < count($columns))
                    ? $this->columns .= $columns[$i] . ', '
                    : $this->columns .= $columns[$i];
            }
        }

        return $this;
    }

    /**
     * Build SQL Query
     *
     * @return string
     */
    public function getQuery()
    {
        $this->query = 'SELECT ';
        $this->query .= $this->columns . ' FROM ' . $this->tableName;
        if (!empty($this->join)) {
            $this->query .= '   ' . $this->join;
        }
        if (!empty($this->on)) {
            $this->query .= '  ON  ' . $this->on;
        }
        $this->query .= '  WHERE ( ' . $this->tableName . '.deleted=0) ';
        if (!empty($this->where)) {
            $this->query .= '   ' . $this->where;
        }

        if (!empty($this->groupBy)) {
            $this->query .= ' GROUP BY ' . $this->groupBy;
        }
        if (!empty($this->orderBy)) {
            $this->query .= ' ORDER BY ' . $this->orderBy . ' ';
            !empty($this->orderType)
                ? $this->query .= $this->orderType
                : $this->query .= ' ASC ';
        }

        if (!empty($this->limit)) {
            $this->query .= ' LIMIT ' . $this->limit;
        }

        return $this->query;
    }

    /**
     * @return array|null
     */
    public function execute()
    {
        $result = $this->getDb()->select($this->getQuery(), $this->args);

        return $result;
    }
}
