<?php

namespace MS\Core\Utility\Querybuilder;

use MS\Core\Utility\QueryBuilder;

/**
 * Class Insert
 *
 * @package MS\Core\Utility\Querybuilder
 */
class Insert extends QueryBuilder
{
    private $activity = '';

    /**
     * @var string
     */
    private $values = '';

    /**
     * @param $columns = ['KEY1' => VALUES1, 'KEY2' => VALUES2]
     * @return $this
     */
    public function setColumnsAndValues($columns)
    {
        if (!empty($columns)) {
            $this->columns .= " ";
            $loop = 1;
            foreach ($columns as $key => $value) {
                if ($loop < count($columns)) {
                    $this->columns .= $key . ' , ';
                    $this->values .= ':' . $key . ' , ';

                } else {
                    $this->columns .= $key;
                    $this->values .= ':' . $key;

                }

                $this->args[':' . $key] = htmlspecialchars($value);
                $loop++;
            }

            /** set activity */
            try {
                $this->activity = implode(', ', array_map(
                    function ($v, $k) {
                        return sprintf("%s='%s'", $k, $v);
                    },
                    $columns,
                    array_keys($columns)
                ));
            } catch (\Exception $exception) {
            }

            $this->columns .= " ";
        }
        if (!empty($this->queryDefaultArgs)) {
            $this->columns .= ", ";
            $this->values .= ", ";

            $loop = 1;
            foreach ($this->queryDefaultArgs as $key => $value) {
                if ($loop < count($this->queryDefaultArgs)) {
                    $this->columns .= $key . ' , ';
                    $this->values .= '"' . htmlspecialchars($value ). '" , ';

                } else {
                    $this->columns .= $key;
                    $this->values .= '"' . htmlspecialchars($value ). '"';

                }
                $loop++;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        $this->query = 'INSERT INTO ' . $this->tableName;
        $createdBy = 0;
        if (defined("USER_DATA")) {
            $createdBy = USER_DATA['uid'];
        }
        $this->query .= '(' . $this->columns . ',createdBy)' . ' VALUES(' . $this->values . ',' . $createdBy . ')';

        return $this->query;
    }

    /**
     * @return int|string
     */
    public function execute()
    {
        try {
            \MS\Core\Controller\CoreActivity::writeActivity('insert',$this->tableName, $this->activity);
        } catch (\Exception $exception) {
        }

        return $this->getDb()->insert($this->getQuery(), $this->args);
    }
}
