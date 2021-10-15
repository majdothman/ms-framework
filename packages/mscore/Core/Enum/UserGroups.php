<?php

namespace MS\Core\Enum;

/**
 * Class UserGroups
 *
 * @package MS\Enum
 */
class UserGroups
{
    public static function getGroupTitle($groupNumber)
    {
        $groupNumber = (int)$groupNumber;
        switch ($groupNumber) {
            case 1:
                return "Super Admin";
                break;
            case 2:
                return "Admin";
                break;
            case 3:
            default:
                return "User";
                break;
        }
    }
}
