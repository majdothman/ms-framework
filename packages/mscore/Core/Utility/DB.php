<?php

namespace MS\Core\Utility;

use MS\Core\Controller\CoreException;
use PDO;

/**
 * Class DB
 * Work with Database
 *
 * @package MS\Utility
 */
class DB
{
    /**
     * @var PDO
     */
    protected ?PDO $connection = null;
    protected static ?DB $instance = null;

    /**
     * Get instance of this Controller
     *
     * @return DB|null
     */
    public static function getInstance(): ?DB
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        return $this->connector();
    }

    /**
     * get connect Public
     *
     * @return PDO|null
     */
    public function getConnect(): ?PDO
    {
        return $this->connector();
    }

    /**
     * Config the connection with DB
     *
     * @return PDO|null
     */
    private function connector(): ?PDO
    {
        try {
            $this->connection = $this->setMysql();

            return $this->connection;
        } catch (\Exception $exception) {
            CoreException::writeError("DB", 'NOT found the Connection - ' . $exception->getMessage(), "1540465659");
            die("You should look up code [1540465659] and solve the Problem, see log.");

            return null;
        }
    }

    /**
     * password encryption ex: 'root' -> return: 'khhm'
     *
     * @param $password
     * @return string
     */
    public static function encryptionPassword($password)
    {
        $encPassword = '';
        foreach (str_split($password) as $char) {
            $encPassword .= chr(ord($char) - 7);
        }

        return $encPassword;
    }

    /**
     * password decode ex: 'khhm' -> return: 'root'
     *
     * @param $password
     * @return string
     */
    protected function decodePassword($password)
    {
        $encPassword = '';
        foreach (str_split($password) as $char) {
            $encPassword .= chr(ord($char) + 7);
        }

        return $encPassword;
    }

    /**
     * get Static Connection for First install
     */
    private static function getStaticConnection(): PDO
    {
        /** connect with default drive 'mysql' */
        $dbHost = MS_ENV['DB']['host'];
        $dbUser = MS_ENV['DB']['username'];
        $dbPassword = MS_ENV['DB']['password'];
        $dbName = MS_ENV['DB']['database'];
        $dbPort = MS_ENV['DB']['port'];

        return new PDO("mysql:dbname=$dbName;port=$dbPort;host=$dbHost", $dbUser, $dbPassword);
    }

    /**
     * import DB for First install
     */
    public static function firstImportDB(): bool
    {
        try {
            $corePath = dirname(dirname(dirname(rtrim(__FILE__, '/') . "/")));
            $sqlFileName = rtrim($corePath, '/') . '/Core/sql.sql';
            /** if sql.sql exist, that mean site in first install */
            if (!file_exists($sqlFileName)) {
                return false;
            }
            $conn = self::getStaticConnection();
            $dbName = $conn->prepare('SELECT DATABASE() as dbName');
            if ($dbName->execute()) {
                $dbName = $dbName->fetchAll(PDO::FETCH_ASSOC);
            }

            if (!empty($dbName)) {
                $countTables = $conn->prepare('SELECT count(*) AS counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE "' . $dbName[0]['dbName'] . '"');
                if ($countTables->execute()) {
                    $countTables = $countTables->fetchAll(PDO::FETCH_ASSOC);
                }

                if (!empty($countTables) && (int)$countTables[0]['counter'] <= 0) {
                    $op_data = '';
                    $lines = file($sqlFileName);
                    foreach ($lines as $line) {
                        //This IF Remove Comment Inside SQL FILE
                        if (substr($line, 0, 2) == '--' || $line == '') {
                            continue;
                        }
                        $op_data .= $line;
                        //Breack Line Upto ';' NEW QUERY
                        if (substr(trim($line), -1, 1) == ';') {
                            $conn->query($op_data);
                            $op_data = '';
                        }
                    }
                }
                unlink(rtrim(MS_ENV['SYS']['root_path'], '/') . '/web/SETUP_DB');
                /**
                 * if sql.sql done
                 * redirect
                 */
                if (!file_exists(rtrim(MS_ENV['SYS']['root_path'], '/') . '/web/SETUP_DB')) {
                    header("location: ./");
                }

                return true;
            }
        } catch (\Exception $exception) {
            throw new \Exception('DB not imported');
        }

        return false;
    }

    /**
     * Connect with MySql
     * @return PDO|null
     */
    private function setMysql(): ?PDO
    {
        if (!empty(MS_ENV)) {
            /** connect with default  drive 'mysql' */
            $dbHost = MS_ENV['DB']['host'];
            $dbUser = MS_ENV['DB']['username'];
            $dbPassword = MS_ENV['DB']['password'];
            $dbName = MS_ENV['DB']['database'];
            $dbPort = MS_ENV['DB']['port'];

            return new PDO("mysql:dbname=$dbName;port=$dbPort;host=$dbHost", $dbUser, $dbPassword);
        } else {
            CoreException::writeWarning(
                "DB",
                "No mysql database to connect, please configure your DB.",
                "1540466124"
            );

            return null;
        }
    }

    /**
     * @param $sql
     * @param $args
     * @return array|null
     */
    public function select($sql, $args = null)
    {
        try {
            if (!empty($this->connection)) {
                $stmt = $this->connection->prepare($sql);
                $result = [];
                if (!empty($args)) {
                    if ($args != null) {
                        foreach ($args as $key => $value) {
                            $stmt->bindParam($key, $value);
                        }
                    }
                }

                /** Execute the statement */
                if ($stmt->execute($args)) {
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $result = null;
                }

                if (!empty($stmt->errorCode() > 0) && RUN_EXCEPTION) {
                    MsUtility::var_dump($stmt->errorInfo(),__FILE__,__LINE__);
                }

                return $result;
            }
        } catch (\Exception $exception) {
            CoreException::writeWarning(
                "DB",
                "wrong statement select.",
                "1540562637"
            );

            return null;
        }
        return null;
    }

    public function runNormalQuery($sql)
    {
        try {
            if (!empty($this->connection)) {
                $stmt = $this->connection->prepare($sql);
                /** Execute the statement */
                $stmt->execute();
                if (!empty($stmt->errorCode() > 0) && RUN_EXCEPTION) {
                    MsUtility::var_dump($stmt->errorInfo(),__FILE__,__LINE__);

                }
            }
        } catch (\Exception $exception) {
            CoreException::writeWarning(
                "DB",
                "wrong statement of run Normal Query .",
                "1540562637"
            );

            return false;
        }
    }

    /**
     * @param $sql
     * @param $args
     * @return int|string
     */
    public function insert($sql, $args = null)
    {
        try {
            if (!empty($this->connection)) {
                $result = 0;
                $stmt = $this->connection->prepare($sql);
                if (!empty($args)) {
                    foreach ($args as $key => $value) {
                        $stmt->bindParam($key, $value);
                    }
                }

                /** Execute the statement */
                if ($stmt->execute($args)) {
                    $result = $this->connection->lastInsertId();
                    if (!$result) {
//                        $result = "inserted";
                        $result = $this->connection->lastInsertId();

                    }
                } else {
                    $result = 0;
                }

                if (!empty($stmt->errorCode() > 0) && RUN_EXCEPTION) {
                    MsUtility::var_dump($stmt->errorInfo(),__FILE__,__LINE__);
                }

                return $result;
            }
        } catch (\Exception $exception) {
            CoreException::writeWarning(
                "DB",
                "wrong statement insert.",
                "1540562637"
            );
            return false;
        }
    }

    /**
     * @param $sql
     * @param $args
     * @return int|string
     */
    public function update($sql, $args = null)
    {
        try {
            if (!empty($this->connection)) {
                $result = 0;
                $stmt = $this->connection->prepare($sql);
                if (!empty($args)) {
                    foreach ($args as $key => $value) {
                        $stmt->bindParam($key, $value);
                    }
                }

                /** Execute the statement */
                if ($stmt->execute($args)) {
                    $result = $this->connection->lastInsertId();
                    if (!$result) {
                        $result = $stmt->rowCount();
                    }
                } else {
                    $result = 0;
                }

                if (!empty($stmt->errorCode() > 0) && RUN_EXCEPTION) {
                    MsUtility::var_dump($stmt->errorInfo(),__FILE__,__LINE__);
                }

                return $result;
            }
        } catch (\Exception $exception) {
            CoreException::writeWarning(
                "DB",
                "wrong statement insert.",
                "1540562637"
            );

            return false;
        }
    }

    /**
     * @param $sql
     * @param $args
     * @return int|string
     */
    public function delete($sql, $args = null)
    {
        try {
            if (!empty($this->connection)) {
                $result = 0;
                $stmt = $this->connection->prepare($sql);
                if (!empty($args)) {
                    foreach ($args as $key => $value) {
                        $stmt->bindParam($key, $value);
                    }
                }

                /** Execute the statement */
                if ($stmt->execute($args)) {
                    $result = $stmt->rowCount();
                } else {
                    $result = 0;
                }

                if (!empty($stmt->errorCode() > 0) && RUN_EXCEPTION) {
                    MsUtility::var_dump($stmt->errorInfo(),__FILE__,__LINE__);
                }

                return $result;
            }
        } catch (\Exception $exception) {
            CoreException::writeWarning(
                "DB",
                "wrong statement insert.",
                "1540562637"
            );

            return false;
        }
    }


    /**
     * just test, if i am in DB Class
     */
    public function test()
    {
        echo "test DB";
    }
}
