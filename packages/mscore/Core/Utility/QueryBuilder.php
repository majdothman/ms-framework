<?php

namespace MS\Core\Utility;

use MS\Core\Utility\Querybuilder\Insert;
use MS\Core\Utility\Querybuilder\Select;
use MS\Core\Utility\Querybuilder\Update;

/**
 * ############################################################
 * #### SELECT #########
 * EXAMPLES:
 * QueryBuilder:
 * ->select()
 * ->setTableName($tableName)
 * ->columns(['*'])
 * ->functions(['count(*) as Count2','MAX(uid) as MAX2'])
 * ->where()
 * ->biggerThen(['uid' => '1'])
 * ->andWhere()
 * ->like(['title'=>'tit'])
 * ->orWhere()
 * ->like(['descrip'=>'des'])
 * ->groupBy(['title'])
 * ->orderBy(['uid'])
 * ->desc()
 * ->limit(3)
 * ->execute()
 * ;
 * #### INSERT #########
 * QueryBuilder:
 * ->insert()
 * ->setTableName('test')
 * ->setColumnsAndValues(['title' => $_POST['title'],'descrip' => $_POST['desc']])
 * ->execute()
 * ;
 * ############################################################
 */

/**
 * Class QueryBuilder
 *
 * @package MS\Core\Utility
 */
class QueryBuilder
{
    /**
     * @var DB|null
     */
    private $db;
    /**
     * @var null
     */
    public static $instance = null;
    /**
     * @var
     */
    public $where = '';
    /**
     * @var string
     */
    public $query;
    /**
     * @var string
     */
    public $tableName = '';
    /**
     * @var string
     */
    public $join = '';
    /**
     * @var string
     */
    public $on = '';

    /**
     * @var array
     */
    public $columns;
    /**
     * @var int
     */
    public $limit;
    /**
     * @var array
     */
    public $groupBy;

    /**
     * @var array
     */
    public $args = [];
    /**
     * @var array
     */
    public $orderBy;
    /**
     * @var string
     */
    public $orderType;
    /**
     * Default fields in all Tables in DB
     *
     * @var array
     */
    public $queryDefaultArgs = [
        'crdate' => TIME_STAMP,
        'updated' => TIME_STAMP,
//        install in __construct
        'updatedBy' => -1,
        // hidden && deleted should not here
    ];

    /**
     * Get instance of this Class
     *
     * @return QueryBuilder
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * QueryBuilder constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
        if (isset($_COOKIE['uid'])) {
            $this->queryDefaultArgs['updatedBy'] = $_COOKIE['uid'];
        }
    }


    /**
     * @return DB|null
     */
    public function getDb(): DB
    {
        return $this->db;
    }

    /**
     * @param $tableName
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }


    /**
     * @return Select
     */
    public function select()
    {
        return new Select();
    }

    public function innerJoin($join)
    {
        $this->join .= "  INNER JOIN " . $join . "  ";

        return $this;
    }


    public function rightJoin($join)
    {
        $this->join .= "  RIGHT JOIN " . $join . "  ";

        return $this;
    }


    public function leftJoin($join)
    {
        $this->join .= "  LEFT JOIN " . $join . "  ";

        return $this;
    }


    public function on($on)
    {
        $this->on .= $on;

        return $this;
    }


    /**
     * @return Insert
     */
    public function insert()
    {
        return new Insert();
    }

    /**
     * @return Update
     */
    public function update()
    {
        return new Update();
    }


    /**
     * @return $this
     */
    public function where()
    {
        return $this;
    }


    /**
     * @return $this
     */
    public function andWhere()
    {
        $this->where .= " AND ";

        return $this;
    }

    /**
     * @return $this
     */
    public function orWhere()
    {
        $this->where .= " OR ";

        return $this;
    }

    /**
     * @return $this
     */
    public function openConditionsGroup()
    {
        $this->where .= " ( ";

        return $this;
    }

    /**
     * @return $this
     */
    public function closeConditionsGroup()
    {
        $this->where .= " ) ";

        return $this;
    }

    /**
     * @param array $condition = ['fileName1' => 'Value1','fileName2' => 'Value2']
     * @return $this
     */
    public function eq(array $condition = [1 => 1])
    {
        $this->where .= '';
        if (!empty($condition)) {
            $this->where .= " (";
            $loop = 1;
            foreach ($condition as $key => $value) {
                $rand = rand(1, 99999);
                ($loop < count($condition))
                    ? $this->where .= '' . $key . '' . '=:' . str_replace('.', '', $key) . $rand . ' AND '
                    : $this->where .= '' . $key . '' . '=:' . str_replace('.', '', $key) . $rand;
                $this->args[':' . str_replace('.', '', $key) . $rand] = $value;
                $loop++;

            }

            $this->where .= " )";
        }

        return $this;
    }
    /**
     * @param array $condition = ['fileName1' => 'Value1','fileName2' => 'Value2']
     * @return $this
     */
    public function notEq(array $condition = [1 => 1])
    {
        $this->where .= '';
        if (!empty($condition)) {
            $this->where .= " (";
            $loop = 1;
            foreach ($condition as $key => $value) {
                $rand = rand(1, 99999);
                ($loop < count($condition))
                    ? $this->where .= '' . $key . '' . '!=:' . str_replace('.', '', $key) . $rand . ' AND '
                    : $this->where .= '' . $key . '' . '!=:' . str_replace('.', '', $key) . $rand;
                $this->args[':' . str_replace('.', '', $key) . $rand] = $value;
                $loop++;

            }

            $this->where .= " )";
        }

        return $this;
    }
    /**
     * fileName1 IS NULL
     * @param array $condition = ['fileName1']
     * @return $this
     */
    public function isNull(array $condition = [1])
    {
        $this->where .= '';
        if (!empty($condition)) {
            $this->where .= " (";
            $loop = 1;
            foreach ($condition as $key => $value) {
                ($loop < count($condition))
                    ? $this->where .= '' . $value . '' . ' IS NULL '. ' AND '
                    : $this->where .= '' . $value . '' . ' IS NULL ';
                $loop++;
            }

            $this->where .= " )";
        }

        return $this;
    }

    /**
     * @param array $condition = ['fileName1' => 'Value1','fileName2' => 'Value2']
     * @return $this
     */
    public function lessThen(array $condition = [1 => 1])
    {
        $this->where .= '';
        if (!empty($condition)) {
            $this->where .= " ( ";
            $loop = 1;
            foreach ($condition as $key => $value) {
                $rand = rand(1, 99999);
                $argKey = str_replace('.', '', $key) . $rand;
                ($loop < count($condition))
                    ? $this->where .= $key . '<:' . $argKey . ' AND '
                    : $this->where .= $key . '<:' . $argKey;
                $this->args[':' . $argKey] = $value;
                $loop++;

            }

            $this->where .= " ) ";
        }

        return $this;
    }

    /**
     * @param array $condition = ['fileName1' => 'Value1','fileName2' => 'Value2']
     * @return $this
     */
    public function lessThenOrEqual(array $condition = [1 => 1])
    {
        $this->where .= '';
        if (!empty($condition)) {
            $this->where .= " ";
            $loop = 1;
            foreach ($condition as $key => $value) {
                $rand = rand(1, 99999);
                $argKey = str_replace('.', '', $key) . $rand;
                ($loop < count($condition))
                    ? $this->where .= $key . '<=:' . $argKey . ' AND '
                    : $this->where .= $key . '<=:' . $argKey;
                $this->args[':' . $argKey] = $value;
                $loop++;

            }

            $this->where .= " ";
        }

        return $this;
    }

    /**
     * @param array $condition = ['fileName1' => 'Value1','fileName2' => 'Value2']
     * @return $this
     */
    public function biggerThen(array $condition = [1 => 1])
    {
        $this->where .= ' ';
        if (!empty($condition)) {
            $this->where .= " ( ";
            $loop = 1;
            foreach ($condition as $key => $value) {
                $rand = rand(1, 99999);
                $argKey = str_replace('.', '', $key) . $rand;
                ($loop < count($condition))
                    ? $this->where .= $key . ' > :' . $argKey . ' AND '
                    : $this->where .= $key . ' > :' . $argKey;
                $this->args[':' . $argKey] = $value;
                $loop++;

            }

            $this->where .= " ) ";
        }

        return $this;
    }

    /**
     * @param array $condition = ['fileName1' => 'Value1','fileName2' => 'Value2']
     * @return $this
     */
    public function biggerThenOrEqual(array $condition = [1 => 1])
    {
        $this->where .= '';
        if (!empty($condition)) {
            $this->where .= " ";
            $loop = 1;
            foreach ($condition as $key => $value) {
                $rand = rand(1, 99999);
                $argKey = str_replace('.', '', $key) . $rand;
                ($loop < count($condition))
                    ? $this->where .= $key . '>=:' . $argKey . ' AND '
                    : $this->where .= $key . '>=:' . $argKey;
                $this->args[':' . $argKey] = $value;
                $loop++;

            }

            $this->where .= " ";
        }

        return $this;
    }

    /**
     * @param $field
     * @param $first
     * @param $second
     * @return $this
     */
    public function between($field, $first, $second)
    {
        $rand = rand(1, 99999);
        $this->where .= " ";
        $this->where .= '`' . $field . '`' . ' BETWEEN :' . $field . '_first_' . $rand . ' AND :' . $field . '_second_' . $rand;
        $this->where .= " ";
        $this->args[':' . $field . '_first_' . $rand] = $first;
        $this->args[':' . $field . '_second_' . $rand] = $second;

        return $this;
    }

    /**
     * @param array $condition = ['fileName1' => 'Value1','fileName2' => 'Value2']
     * @param bool $func ex: CONCAT(firstname , ' ', lastname)
     * @return $this
     */
    public function like(array $condition = [1 => 1], $func = false)
    {
        $this->where .= '';
        if (!empty($condition)) {
            $this->where .= " ";
            $loop = 1;
            foreach ($condition as $key => $value) {
                ($loop < count($condition))
                    ? $this->where .= '`' . $key . '`' . ' LIKE \'%' . $value . '%\' AND '
                    : $this->where .= '`' . $key . '`' . ' LIKE \'%' . $value . '%\'';
                if ($func) {
                    $this->where = str_replace('`', ' ', $this->where);
                }
                $loop++;

            }

            $this->where .= " ";
        }

        return $this;
    }

    /**
     * @param array $orderBy ['field1','field2']
     * @return $this
     */
    public function orderBy(array $orderBy)
    {
        $this->orderBy = implode(',', $orderBy);

        return $this;
    }

    /**
     * Set the type of Sorting is ASC
     *
     * @return $this
     */
    public function asc()
    {
        $this->orderType = ' ASC ';

        return $this;
    }

    /**
     * Set the type of Sorting is DESC
     *
     * @return $this
     */
    public function desc()
    {
        $this->orderType = ' DESC ';

        return $this;
    }

    /**
     * @param int $limit = 10
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param array $groupBy = ['field1','field2']
     * @return $this
     */
    public function groupBy(array $groupBy = [])
    {

        $this->groupBy = implode(',', $groupBy);

        return $this;
    }
}
