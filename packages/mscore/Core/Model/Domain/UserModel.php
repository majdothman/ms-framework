<?php


namespace MS\Core\Model\Domain;


use MS\Core\Model\Models;

/**
 * Class UserModel
 *
 * @package MS\Model\Domain
 */
class UserModel extends Models
{
    /**
     * @var int
     */
    protected $idUsers;
    /**
     * @var string
     */
    protected $firstname;
    /**
     * @var string
     */
    protected $lastname;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var \DateTime
     */
    protected $registerDate;
    /**
     * @var \DateTime
     */
    protected $lastvisitDate;
    /**
     * @var int
     */
    protected $isActive;
    /**
     * @var int
     */
    protected $idUserGroups;

    /**
     * @return int
     */
    public function getIdUsers()
    {
        return $this->idUsers;
    }

    /**
     * @param int $idUsers
     */
    public function setIdUsers($idUsers)
    {
        $this->idUsers = $idUsers;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return \DateTime
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * @param \DateTime $registerDate
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastvisitDate()
    {
        return $this->lastvisitDate;
    }

    /**
     * @param \DateTime $lastvisitDate
     */
    public function setLastvisitDate($lastvisitDate)
    {
        $this->lastvisitDate = $lastvisitDate;
    }

    /**
     * @return int
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param int $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return int
     */
    public function getIdUserGroups(): int
    {
        return $this->idUserGroups;
    }

    /**
     * @param int $idUserGroups
     */
    public function setIdUserGroups(int $idUserGroups)
    {
        $this->idUserGroups = $idUserGroups;
    }
}
