<?php

namespace MS\Core\Utility\Querybuilder;

use MS\Core\Utility\QueryBuilder;

class Update extends QueryBuilder
{
    private $activity = '';

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
                    $this->columns .= $key . '=:' . $key . ' , ';

                } else {
                    $this->columns .= $key . '=:' . $key;

                }

                $this->args[':' . $key] = htmlspecialchars($value);
                $loop++;
            }

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

            $loop = 1;
            foreach ($this->queryDefaultArgs as $key => $value) {
                if ($key != 'crdate') {
                    if ($loop < count($this->queryDefaultArgs)) {
                        $this->columns .= $key . '=\'' . htmlspecialchars($value) . '\' , ';
                    } else {
                        $this->columns .= $key . '=\'' . htmlspecialchars($value) . '\'';
                    }
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
        $this->query = 'UPDATE ' . $this->tableName . ' SET ';
        $this->query .= $this->columns;
        if (!empty($this->where)) {
            $this->query .= ' WHERE ' . $this->where;
        }

        return $this->query;
    }

    /**
     * @return int|string
     */
    public function execute()
    {
        try {
            \MS\Core\Controller\CoreActivity::writeActivity('update', $this->tableName, $this->activity);
        } catch (\Exception $exception) {
        }

        return $this->getDb()->update($this->getQuery(), $this->args);
    }
}
