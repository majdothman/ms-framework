<?php

namespace MS\Core\Controller;

class ValidationController
{

    protected static $instance = null;

    /**
     * Get instance of this Controller
     *
     * @return ValidationController|mixed|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function validFirstNameAndLastName($firstname, $lastname)
    {
        $valid = true;
        /**
         * firstName, lastName
         */
        if (strlen($firstname) <= 2 || strlen($lastname) <= 2) {
            $valid = false;
        }

        if (!preg_match('/^[a-zA-Z ]*$/', $firstname) ||
            !preg_match('/^[a-zA-Z ]*$/', $lastname)) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param $email
     * @return bool
     */
    public function validEmail($email)
    {
        $valid = true;
        /** if not hotmail or web or Gmail*/
        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            /** if email format false */
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param $password
     * @return bool
     */
    public function validPassword($password)
    {
        $valid = true;
        if (empty($password) || strlen($password) < 8) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param $userGroups
     * @return bool
     */
    public function validUserGroup($userGroups)
    {
        $valid = true;
        if (empty($userGroups) || ($userGroups == '0')) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param $selectGroups
     * @return bool
     */
    public function validSelectGroup($selectGroups)
    {
        $valid = true;
        if (empty($selectGroups) || ($selectGroups == '0')) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param $geschlechtGroup
     * @return bool
     */
    public function validGeschlechtGroup($geschlechtGroup)
    {
        $valid = true;
        if (empty($geschlechtGroup) || (($geschlechtGroup != 'M') && ($geschlechtGroup != 'W'))) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param $checkBox
     * @return bool
     */
    public function validCheckbox($checkBox)
    {
        $valid = true;
        if (empty($checkBox) || $checkBox != 'on') {
            $valid = false;
        }

        return $valid;
    }
}
