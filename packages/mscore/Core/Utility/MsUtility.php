<?php

namespace MS\Core\Utility;

use MS\Core\Controller\UserController;
use Symfony\Component\Yaml\Yaml;

/**
 * Class MsUtility
 *
 * @package MS\Core\Utility
 */
class MsUtility
{
    /**
     * @param $var
     * @param string $fileName
     * @param int $line
     */
    public static function var_dump($var, $fileName = '', $line = 0)
    {
        /** if live - do not show any Dump */
        if (!RUN_EXCEPTION && (UserController::isSuperAdmin() || UserController::isAdmin())) {
            return;
        }
        echo '<pre class="var_dump" style="">';
        if (!empty($fileName)) {
            echo '<p style="color: red">';
            echo preg_replace("/\.[^.]+$/", "", basename($fileName));
            if ($line > 0) {
                echo '| Line:' . $line;
            }
            echo '</p>';
        }

        var_dump($var);
        echo '</pre>';
    }

    /**
     * @param $message
     * @param string $type
     */
    public static function fe_dump($message, $type = 'info', $code = 0)
    {
        echo '<div class="fe-dump">';
        echo '<div class="alert alert-' . $type . ' alert-dismissible m-0 fade show" role="alert">';
        if (is_array($message)) {
            foreach ($message as $key => $value) {
                echo '* ' . $value;
                echo '<br>';
            }
            echo '<br>' . self::printCodeTime($code);
        } else {
            echo $message . self::printCodeTime($code);
        }
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '<br>';
        echo '</div>';
        echo '</div>';
    }

    protected static function printCodeTime($code)
    {
        if (UserController::isSuperAdmin() || UserController::isAdmin())
            return ', [' . $code . ']';
    }

    /**
     * String encryption ex: 'root' -> return: str[length] + 'khhm' + str[length]
     *
     * @param $str
     * @return string
     */
    public static function encryptionString($str)
    {
        $encPassword = '';
        foreach (str_split($str) as $char) {
            $encPassword .= chr(ord($char) - 7);
        }

        return self::randString() . $encPassword . self::randString();
    }

    /**
     * @param int $length
     * @return bool|string
     */
    public static function randString($length = 20)
    {
        $chars = substr(strtolower(md5(rand())), 0, $length);

        return $chars;
    }

    /**
     * String decode ex: str[length] + khhm + str[length] -> return: 'root'
     *
     * @param $str
     * @param int $length
     * @return string
     */
    public static function decodeString($str, $length = 20)
    {
        $removedTheRandomString = substr($str, $length);
        $strLength = strlen($removedTheRandomString);

        $removedRandomString = substr($removedTheRandomString, 0, $strLength - $length);

        $decodeString = '';
        foreach (str_split($removedRandomString) as $char) {
            $decodeString .= chr(ord($char) + 7);
        }

        return $decodeString;
    }

    /**
     * @param $filePath
     * @return mixed
     */
    public static function yamlParse($filePath)
    {
        return Yaml::parseFile($filePath);
    }

    /**
     * @return string
     */
    public static function baseUrl(): string
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off")
                ? "https"
                : "http";
        } else {
            $protocol = 'http';
        }

        return $protocol . '://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * pass arguments to file
     * @param $fileName
     * @param array $args
     */
    public static function renderFile($fileName, $args = [])
    {
        require_once $fileName;
    }
}
