<?php

namespace MS\Core\Controller;

use Exception;
use MS\Core\Model\Domain\UserModel;
use MS\Core\Model\Repository\UserRepository;
use MS\Core\Utility\MsUtility;

/**
 * Class UserController
 *
 */
class UserController
{
    protected static $instance = null;
    /**
     * @var UserRepository|null
     */
    protected $userRepository = null;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->userRepository = UserRepository::getInstance();
    }

    /**
     * Get instance of this Controller
     *
     * @return UserController|mixed|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * if stay logged in find USER_DATA
     *
     * @param int $uid
     * @return int
     */
    public function updateUserDataInCookiesAction($uid = -1)
    {
        try {
            $findUser = $this->userRepository->findByUid('be_users', (int)$uid);
            if (count($findUser) > 0) {
                /** Update last active time for user */
                $this->userRepository->updateLastActive((int)$findUser[0]['uid']);

                return $findUser[0];
            } else {
                return null;
            }
        } catch (Exception $exception) {
        }
    }

    /**
     * Update user-Account Active or UnActive
     *
     * @return int|string
     */
    public function updateUserActive($uid, $value)
    {
        $result = 0;
        try {
            if (!defined('IS_SUPER_ADMIN')) return;
            if ($_POST) {
                $result = $this->userRepository->updateUserActive((int)$uid, (int)$value);
                return $result;
            }
        } catch (Exception $exception) {
        }
    }

    /**
     * @return array|bool|int|void
     */
    public function loginAction()
    {
        if ($this->isLoggedIn()) {
            header("Location:" . BASE_URL . "/mscms/index.php");

            return;
        }
        try {
            $valid = true;
            $arguments = null;
            $errors = [];
            if ($_POST) {
                $userModel = new UserModel();

                /**
                 * Email
                 */
                if (!isset($_POST['email']) | empty($_POST['email'])) {
                    $errors[] = 'Email or Username: can not be empty.';
                    $valid = false;

                    return $errors;
                } else {
                    if (!strpos($_POST['email'], '@')) {
                        $userModel->setUsername($_POST['email']);
                    } elseif (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {

                        /** if email format false */
                        $errors[] = 'Email: Your email format is not correct, ex: {majd123@web.com}';
                        $valid = false;

                        return $errors;
                    } else {
                        $userModel->setEmail($_POST['email']);
                    }
                }

                if (!isset($_POST['password']) | empty($_POST['password'])) {
                    $errors[] = 'Password: can not be empty';
                    $valid = false;

                    return $errors;
                } else {
                    $userModel->setPassword($_POST['password']);
                }

                /**
                 * If email and Password not empty and true data.
                 */
                if ($valid) {
                    $whereColumn = ['email' => $userModel->getEmail()];
                    if ($userModel->getUsername()) {
                        $whereColumn = ['username' => $userModel->getUsername()];
                    }
                    $findUser = $this->userRepository->getQueryBuilder()
                        ->select()
                        ->setTableName("be_users")
                        ->columns()
                        ->andWhere()
                        ->eq($whereColumn)
                        ->execute();

                    if (!empty($findUser) && $findUser[0]['hidden'] >= 1) {
                        $errors[] = 'Your account is inactive, please look at your E-mail or contact with the us.';

                        return $errors;
                    }

                    if (!empty($findUser) && !password_verify($userModel->getPassword(), $findUser[0]['password'])) {
                        $errors[] = 'Incorrect E-Mail, Username or password';

                        return $errors;
                    } elseif (!empty($findUser) && $findUser[0]['isActive'] <= 0) {
                        $errors[] = 'Your account is inactive, please contact with the admin.';

                        return $errors;
                    } else {
                        /** if password_verify and true */
                        if (count($findUser) > 0) {
                            $userModel->setIdUsers($findUser[0]['uid']);
                            $userModel->setFirstname($findUser[0]['firstname']);
                            $userModel->setLastname($findUser[0]['lastname']);
                            $userModel->setLastvisitDate($findUser[0]['lastvisitDate']);
                            $userModel->setIsActive($findUser[0]['isActive']);
                            $userModel->setIdUserGroups($findUser[0]['idUserGroups']);
                            $this->setUserCookies($userModel);
                            /** update last visit and hash_link after Log in*/
                            $token = md5(session_id() . $findUser[0]['uid']);

                            $this->userRepository->getQueryBuilder()
                                ->update()
                                ->setTableName('be_users')
                                ->setColumnsAndValues(
                                    [
                                        'lastvisitDate' => TIME_STAMP,
                                        'hash_link' => '',
                                        'currentSession' => session_id(),
                                        'token' => $token,
                                        'isOnline' => 1,
                                    ]
                                )->where()
                                ->eq(['uid' => (int)$findUser[0]['uid']])
                                ->execute();


                            return 1;
                        } else {
                            $errors[] = 'Incorrect E-Mail, Username or password';

                            return $errors;
                        }
                    }
                } else {
                    return $errors;
                }
            }

            return 0;
        } catch (Exception $exception) {
            CoreException::writeError("Login-main", $exception->getMessage(), "1540560168");

            return false;
        }
    }

    /**
     * could be POST
     * allow just for $_POST AND Admin
     * if create high-level user more  than me, don't Allow
     *
     * @return bool|int
     */
    public function createUser()
    {
        if (!IS_ADMIN) {
            return false;
        }
        $valid = true;
        $errors = [];
        try {
            $arguments = null;
            if (!$_POST || !defined('USER_DATA') || ((int)USER_DATA['idUserGroups'] != 1 && (int)USER_DATA['idUserGroups'] != 2)) {
                \MS\Core\Utility\MsUtility::fe_dump('* You don\'t have permission to do this action.', 'danger', 1634330896);
            } else {
                if (!isset($_POST['firstname']) || empty($_POST['firstname'])) {
                    $errors[] = 'first name: first name can not be empty';
                    $valid = false;
                }
                if (!isset($_POST['lastname']) || empty($_POST['lastname'])) {
                    $errors[] = 'last name: last name can not be empty';
                    $valid = false;
                }

                if (!isset($_POST['username']) || empty($_POST['username'])) {
                    $errors[] = 'username: username can not be empty';
                    $valid = false;
                }
                if (!isset($_POST['email']) || empty($_POST['email'])) {
                    $errors[] = 'Email: email can not be empty.';
                    $valid = false;

                } elseif (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
                    /** if email format false */
                    $errors[] = 'Email: Your email format is not correct, ex: {majd123@web.com}';
                    $valid = false;
                }
                if (!isset($_POST['password']) || empty($_POST['password'])) {
                    $errors[] = 'Password: can not be empty';
                    $valid = false;
                }
                if (!isset($_POST['userGroup']) || empty($_POST['userGroup']) || (int)$_POST['userGroup'] <= 0) {
                    $errors[] = 'User Group: can not be empty';
                    $valid = false;
                }

                if (!$valid) {
                    \MS\Core\Utility\MsUtility::fe_dump($errors, 'danger', 1634330897);
                } else {
                    /** if user is SuperAdmin */
                    if (USER_DATA['idUserGroups'] == 1 || (int)USER_DATA['idUserGroups'] == 2) {
                        /** if create  high-level user more  than me, don't Allow */
                        if ((int)$_POST['userGroup'] < (int)USER_DATA['idUserGroups']) {
                            \MS\Core\Utility\MsUtility::fe_dump(
                                '* You don\'t have permission to do this action.',
                                'danger',
                                1634330898
                            );
                            $valid = false;
                        }
                    }
                    if ($this->isEmailExist($_POST['email'])) {
                        \MS\Core\Utility\MsUtility::fe_dump('* E-Mail is exist', 'danger', 1634330899);
                        $valid = false;
                    }
                    if ($this->isUsernameExist($_POST['username'])) {
                        \MS\Core\Utility\MsUtility::fe_dump('* Username is exist', 'danger', 1634330961);
                        $valid = false;
                    }

                    if ($valid) {
                        $result = $this->userRepository->createNewBeUser(
                            $_POST['firstname'],
                            $_POST['lastname'],
                            $_POST['username'],
                            $_POST['email'],
                            $_POST['password'],
                            $_POST['userGroup']
                        );

                        if (!empty($result)) {
                            return 1;
                        } else {
                            return 0;
                        }
                    }
                }

            }

            return 0;
        } catch (Exception $exception) {
            CoreException::writeError("User-Controller", $exception->getMessage(), "1634320863");

            return false;
        }
    }

    /**
     * could be POST
     * allow just for $_POST
     *
     * @return bool|int
     */
    public function registerAction()
    {
        $valid = true;
        $errors = [];
        $validation = ValidationController::getInstance();
        try {
            $arguments = null;
            if (!$_POST) {
                \MS\Core\Utility\MsUtility::fe_dump('* You don\'t have permission to do this action.', 'danger', 1634330962);
            } else {
                if (!isset($_POST['firstname']) || empty($_POST['firstname'])) {
                    $errors[] = 'first name: first name can not be empty';
                    $valid = false;
                }
                if (!isset($_POST['lastname']) || empty($_POST['lastname'])) {
                    $errors[] = 'last name: last name can not be empty';
                    $valid = false;
                }

                if (!isset($_POST['username']) || empty($_POST['username']) || strlen($_POST['username']) < 5 || !preg_match("/^[a-z]+$/", $_POST['username'])) {
                    $errors[] = 'username: 5 or more characters, Alphabet';
                    $valid = false;
                }
                if (!isset($_POST['email']) || !$validation->validEmail($_POST['email'])) {
                    /** if email format false */
                    $errors[] = 'Email: Your email format is not correct, ex: {majd123@hotmail.com}';
                    $valid = false;
                }
                if (!isset($_POST['password']) || !$validation->validPassword($_POST['password'])) {
                    $errors[] = 'Password: 8 or more characters.';
                    $valid = false;
                }

                if (!$valid) {
                    \MS\Core\Utility\MsUtility::fe_dump($errors, 'danger');
                } else {
                    if ($this->isEmailExist($_POST['email'])) {
                        \MS\Core\Utility\MsUtility::fe_dump('* E-Mail already registered', 'danger', 1634330963);
                        $valid = false;
                    }
                    if ($this->isUsernameExist($_POST['username'])) {
                        \MS\Core\Utility\MsUtility::fe_dump('* username already taken', 'danger', 1634330964);
                        $valid = false;
                    }

                    if ($valid) {
                        $result = $this->userRepository->register(
                            $_POST['firstname'],
                            $_POST['lastname'],
                            $_POST['username'],
                            $_POST['email'],
                            $_POST['password'],
                            3,
                            (int)$_POST['phone'],
                            md5($_POST['privateAnswer']),
                            $_POST['address']
                        );
                        if (!empty($result)) {
                            $this->sendRegisterEmail(
                                (int)$result,
                                $_POST['firstname'] . ' ' . $_POST['firstname'],
                                $_POST['email']
                            );
                            return 1;
                        } else {
                            return 0;
                        }
                    }
                }

            }

            return 0;
        } catch (Exception $exception) {
            CoreException::writeError("User-Controller", $exception->getMessage(), "1634320864");

            return false;
        }
    }

    /**
     * @param $userId
     * @param $fullName
     * @param $email
     */
    public function sendRegisterEmail($userId, $fullName, $email)
    {
        try {
            $encHash = md5($userId . $email . time());
            $hashLink = '<a href="';
            $hashLink .= BASE_URL . '/mscms/ActiveAccount.php?hash=';
            $hashLink .= $encHash;
            $hashLink .= '" target="_blank">Click here to active your account.</a>';

            $msg = '<br> Hello Mr. / Mrs. ' . $fullName . ',<br>';
            $msg .= 'You have requested on this date ';
            $msg .= date('m.d.Y', TIME_STAMP);
            $msg .= ' to active your account. Is that correct? <br>If that is Correct click on this link:';

            $this->userRepository->updateField(
                'be_users',
                [
                    'hash_link' => $encHash,
                ],
                [
                    'uid' => $userId,
                ]
            );

            $endMsg = '<br> <br> Yours sincerely<br> MS - Cast Team';
            $contentType = 'Content-Type: text/html; charset=UTF-8';
            /** If email Sent */
            if (mail($email, 'MS-Cast, Active account', $msg . $hashLink . $endMsg, $contentType)) {
                MsUtility::fe_dump("You have a new E-Mail from us to active your account.", 1634331061);
            } else {
                MsUtility::fe_dump("Somthing wrong, please try again", 'danger', 1634331060);
            }
        } catch (Exception $exception) {
        }
    }

    /**
     * could be POST
     * allow just for $_POST
     * if update high-level user more  than me, don't Allow
     *
     * @return bool|int
     */
    public function updateUser()
    {
        $valid = true;
        $errors = [];
        $columns = [];
        try {
            $arguments = null;

            if (isset($_POST['firstname']) && !empty($_POST['firstname'])) {
                $columns['firstname'] = $_POST['firstname'];
            }
            if (isset($_POST['lastname']) && !empty($_POST['lastname'])) {
                $columns['lastname'] = $_POST['lastname'];
            }

            if (isset($_POST['address']) && !empty($_POST['address'])) {
                $columns['address'] = $_POST['address'];
            }

            if (isset($_POST['phone']) && !empty($_POST['phone'])) {
                $columns['phone'] = $_POST['phone'];
            }
            if (isset($_POST['info']) && !empty($_POST['info'])) {
                $columns['info'] = $_POST['info'];
            }

            if (isset($_POST['username']) && !empty($_POST['username']) && IS_ADMIN) {
                if ($this->isUsernameExist($_POST['username'])) {
                    $errors[] = 'username is already exist, please choose another username.';
                    $valid = false;
                } else {
                    $columns['username'] = $_POST['username'];
                }
            }

            if (isset($_POST['email']) && !empty($_POST['email']) && IS_ADMIN) {
                if (!(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {
                    /** if email format false */
                    $errors[] = 'Email: Your email format is not correct, ex: {majd123@web.com}';
                    $valid = false;
                } else {
                    if ($this->isEmailExist($_POST['email'])) {
                        $errors[] = 'E-Mail is already exist, please choose another E-Mail.';
                        $valid = false;
                    } else {
                        $columns['email'] = $_POST['email'];
                    }
                }
            }


            if (isset($_POST['password']) && strlen($_POST['password']) > 0) {
                $columns['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            if (isset($_POST['userGroup']) && IS_ADMIN) {
                if (empty($_POST['userGroup']) || (int)$_POST['userGroup'] <= 0) {
                    $errors[] = 'User Group: can not be empty';
                    $valid = false;
                } else {
                    /** if user is SuperAdmin */
                    if (USER_DATA['idUserGroups'] == 1 || (int)USER_DATA['idUserGroups'] == 2) {
                        /** if i have permission to update usergroup high-level user more  than me, don't Allow */
                        if ((int)$_POST['userGroup'] < (int)USER_DATA['idUserGroups']) {
                            \MS\Core\Utility\MsUtility::fe_dump(
                                '* You don\'t have permission to do this action.',
                                'danger',
                                1634331063
                            );
                            $valid = false;
                        } else {
                            $columns['idUserGroups'] = $_POST['userGroup'];
                        }
                    }
                }
            }

            if (!$valid) {
                \MS\Core\Utility\MsUtility::fe_dump($errors, 'danger', 1634331064);
            } else {
                if (isset($_POST['email']) && $this->isEmailExist($_POST['email'])) {
                    \MS\Core\Utility\MsUtility::fe_dump('* E-Mail is exist', 'danger', 1634331065);
                    $valid = false;
                }
                if (isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    \MS\Core\Utility\MsUtility::fe_dump('* E-Mail is not validate', 'danger', 1634331065);
                    $valid = false;
                }
                if (isset($_POST['username']) && $this->isUsernameExist($_POST['username'])) {
                    \MS\Core\Utility\MsUtility::fe_dump('* Username is exist', 'danger', 1634331066);
                    $valid = false;
                }
                $columns['updatedBy'] = (int)USER_DATA['uid'];

                if ($valid && !empty($columns)) {
                    $result = $this->userRepository->updateBeUser(
                        $columns
                    );

                    if (!empty($result)) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
            }


            return 0;
        } catch (Exception $exception) {
            CoreException::writeError("User-Controller", $exception->getMessage(), "1634320865");

            return false;
        }
    }

    /**
     * @param $uid
     * @return bool|int
     */
    public function findAll()
    {
        try {
            $findUser = $this->userRepository->findAllForAdmin();

            if (!empty($findUser)) {
                return $findUser;
            } else {
                return 0;
            }

        } catch (Exception $exception) {
            CoreException::writeError("User-Controller", $exception->getMessage(), "1554193707");

            return false;
        }
    }

    /**
     * @return bool|int
     */
    public function findAllActiveUsers()
    {
        try {
            $findUser = $this->userRepository->findAllActiveUsers('be_users');

            if (!empty($findUser)) {
                return $findUser;
            } else {
                return 0;
            }

        } catch (Exception $exception) {
            CoreException::writeError("User-Controller", $exception->getMessage(), "1554193709");

            return false;
        }
    }


    /**
     * @param $uid
     * @return bool|int
     */
    public function findUser($uid)
    {
        try {
            $findUser = $this->userRepository->findByUid('be_users', (int)$uid);
            if (!empty($findUser)) {
                return $findUser[0];
            } else {
                return 0;
            }

        } catch (Exception $exception) {
            CoreException::writeError("User-Controller", $exception->getMessage(), "1554193707");

            return false;
        }
    }

    /**
     * @param $uid
     * @return string
     */
    public function findFullNameOfUser($uid)
    {
        try {
            $findUser = $this->userRepository->findByUid('be_users', (int)$uid);
            if (!empty($findUser)) {
                return $findUser[0]['firstname'] . ' ' . $findUser[0]['lastname'];
            } else {
                return '';
            }

        } catch (Exception $exception) {
            CoreException::writeError("User-Controller", $exception->getMessage(), "1554193777");

            return false;
        }
    }

    /**
     * if email already exist
     *
     * @return bool
     */
    public function isEmailExist($email)
    {
        $return = false;
        $emailExist = $this->userRepository->getQueryBuilder()
            ->select()
            ->setTableName("be_users")
            ->columns(['email'])
            ->andWhere()
            ->eq(['email' => $email])
            ->execute();
        if (count($emailExist) > 0) {
            $return = true;
        }

        return $return;
    }

    /**
     * if username already exist
     *
     * @return bool
     */
    public function isUsernameExist($username)
    {
        $return = false;
        $usernameExist = $this->userRepository->getQueryBuilder()
            ->select()
            ->setTableName("be_users")
            ->columns(['username'])
            ->andWhere()
            ->eq(['username' => $username])
            ->execute();
        if (count($usernameExist) > 0) {
            $return = true;
        }

        return $return;
    }

    /**
     * look if user has logged in or not
     * 0 = is not logged in
     * 1 = is logged in
     *
     * @return int
     */
    protected function isLoggedIn()
    {
        if (isset($_COOKIE['uid']) && $_COOKIE['uid'] > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * look if user Activated or Not
     * 0 = is not lActivated
     * 1 = is Activated
     *
     * @return int
     */
    protected function isActivated()
    {
        if (isset($_COOKIE['isactive']) && $_COOKIE['isactive'] == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * set some userData in cookies and session
     *
     * @param UserModel $user
     */
    protected function setUserCookies(UserModel $user)
    {
        /** Set to Cookies */
        setcookie("uid", $user->getIdUsers(), time() + 31556926, '/');
        setcookie("isactive", $user->getisActive(), time() + 31556926, '/');
        setcookie("group", $user->getIdUserGroups(), time() + 31556926, '/');
        $token = md5(session_id() . $user->getIdUsers());
        setcookie("token", $token, time() + 31556926, '/');
    }

    /***
     * Send E-Mail to reset User Password
     */
    public function sendResetPasswordAction()
    {
        if ($_POST && isset($_POST['forgotEmail']) && isset($_POST['sendLink'])) {
            $user = $this->userRepository->findBy('be_users', ['email' => $_POST['forgotEmail']]);
            if (!empty($user)) {
                $user = $user[0];
                $encHash = md5($user['uid'] . $user['email'] . $user['lastvisitDate']);
                $hashLink = '<a href="';
                $hashLink .= BASE_URL . '/mscms/ResetPassword.php?hash=';
                $hashLink .= $encHash;
                $hashLink .= '" target="_blank">Click here to Reset password</a>';

                $msg = '<br> Dear User, <br>';
                $msg .= 'You have requested on this date ';
                $msg .= date('m.d.Y', TIME_STAMP);
                $msg .= ' to Reset your password. Is that correct? If that is Correct click on this link:';

                $this->userRepository->updateField(
                    'be_users',
                    [
                        'hash_link' => $encHash,
                    ],
                    [
                        'email' => $_POST['forgotEmail'],
                    ]
                );

                $endMsg = '<br> <br> Yours sincerely<br> MS Framework Team';
                $contentType = 'Content-Type: text/html; charset=UTF-8';
                /** If email Sent */
                if (mail($_POST['forgotEmail'], 'Reset password', $msg . $hashLink . $endMsg, $contentType)) {
                    header("Location:" . BASE_URL . "/mscms/login.php?resetPasswordType=success&resetPassword=We have sent a message with a link to reset your password, Please check your email.");
                } else {
                    header("Location:" . BASE_URL . "/mscms/login.php?resetPasswordType=danger&resetPassword=Not sent, please try again.");
                }

            } else {
                header("Location:" . BASE_URL . "/mscms/login.php?resetPasswordType=danger&resetPassword=Your E-Mail is incorrect.");
            }
        }
    }

    /***
     * isHashExist look if the hash to reset Password exist.
     *
     * @param $hash
     * @return bool
     */
    public function isHashExist($hash)
    {
        $isHash = $this->userRepository->findBy('be_users', ['hash_link' => $hash]);
        if (empty($isHash)) {
            return false;
        }

        return true;
    }

    /**
     * update User Password
     *
     * @return bool|int|string|void
     */
    public function resetPassword()
    {
        if ($_POST && isset($_POST['hash']) && !empty($_POST['hash'])) {
            if (isset($_POST['password']) && ValidationController::getInstance()->validPassword($_POST['re-password'])) {
                $result = $this->userRepository->updateField(
                    'be_users',
                    [
                        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                        'hash_link' => ' ',
                    ],
                    [
                        'hash_link' => $_POST['hash'],
                    ]
                );

                return $result;
            } else {
                MsUtility::fe_dump('Password: 8 or more characters.', 'warning', 1634331067);
            }
        }

        return false;
    }

    /**
     * Logout && clear Cookies
     */
    public function logoutAction()
    {

        $past = time() - 3600000;
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, $value, $past, '/');
            setcookie($key, $value, $past, '/mscms/');
        }

        if (!empty(USER_DATA['uid'])) {
            $this->userRepository->getQueryBuilder()
                ->update()
                ->setTableName("be_users")
                ->setColumnsAndValues(['isOnline' => '0'])
                ->where()
                ->eq(['uid' => USER_DATA['uid']])
                ->execute();
        }

        session_unset();
        session_destroy();
        $href = '';

        header("Location:" . rtrim(BASE_URL, '/') . "/mscms/login.php" . $href);
    }

    /**
     * check if Current User is Super admin or not
     * @return bool
     */
    public static function isSuperAdmin()
    {
        try {
            return (defined('IS_SUPER_ADMIN') && IS_SUPER_ADMIN == 1);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * check if Current User is admin or not
     * @return bool
     */
    public static function isAdmin()
    {
        try {
            return (defined('IS_ADMIN') && IS_ADMIN == 1);
        } catch (\Exception $exception) {
            return false;
        }
    }
}
