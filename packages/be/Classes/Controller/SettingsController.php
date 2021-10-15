<?php

namespace MS\Core\Be\Controller;

use Exception;
use MS\Core\Be\Repository\SettingsRepository;
use MS\Core\Controller\BeController;
use MS\Core\Controller\CoreException;
use MS\Core\Controller\UserController;

class SettingsController extends BeController
{
    protected SettingsRepository $settingsRepository;

    public static function getInstance()
    {
        if (empty(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

    public function initialize()
    {
        $this->settingsRepository = SettingsRepository::getInstance();
    }

    public function mainAction(): bool
    {
        try {
            $this->view->setTemplate('Main');
            $this->view->setTemplateFolder('Settings');

            $this->view->render();

            return true;
        } catch (Exception $exception) {
            CoreException::writeError("Settings", $exception->getMessage(), "1634320894");

            return false;
        }
    }


    public function commandAction()
    {
        try {
            $this->view->setTemplate('Command');
            $this->view->setTemplateFolder('Settings/Command');
            if ((UserController::isSuperAdmin()) && $_POST && isset($_POST['post'])) {
                if (isset($_POST['command'])) {
                    $this->view->setArgument(['result' => $this->settingsRepository->runCommand($_POST['command'])]);
                }
            }

            $this->view->render();

            return true;
        } catch (Exception $exception) {
            CoreException::writeError("Settings", $exception->getMessage(), "1634320895");

            return false;
        }
    }

    public function usersAction()
    {
        try {
            $arguments = [];
            $userController = UserController::getInstance();

            /** if user is SuperAdmin */
            if ((int)USER_DATA['idUserGroups'] == 1 || (int)USER_DATA['idUserGroups'] == 2) {
                $arguments['users'] = $userController->findAll();
            } else {
                $arguments['users'] = $userController->findUser('be_users', (int)USER_DATA['uid']);
            }

            $this->view->setArguments($arguments);
            $this->view->setTemplateFolder('Settings/Users/');
            $this->view->setTemplate('Users');
            $this->view->render();

            return true;
        } catch (Exception $exception) {
            CoreException::writeError("Settings", $exception->getMessage(), "1634320896");

            return false;
        }
    }

    public function createUserAction()
    {
        try {
            $arguments = [];
            if ($_POST) {
                $userController = UserController::getInstance();
                $result = $userController->createUser();

                if (!empty($result)) {
                    header('Location:' . BASE_URL . '/mscms/?controller=settings&action=users');

                    return true;
                }
            }
            $this->view->setArguments($arguments);
            $this->view->setTemplateFolder('Settings/Users/');
            $this->view->setTemplate('CreateUser');
            $this->view->render();

            return true;
        } catch (Exception $exception) {
            CoreException::writeError("Settings", $exception->getMessage(), "1554119612");

            return false;
        }
    }


    public function editUserAction()
    {
        try {
            $arguments = [];
            if ($_POST) {
                $userController = UserController::getInstance();
                if (isset($_POST['update'])) {
                    $arguments['user'] = $userController->updateUser();
                    // if AJAX API
                    if (isset($_GET['ax'])) {
                        if ((int)$arguments['user'] != 0) {
                            echo '1';
                        } else {
                            echo '0';
                        }

                        return true;
                    }
                    // if NOT AJAX API
                    \MS\Core\Utility\MsUtility::fe_dump('User Updated', 'success', 1634331421);
                    $arguments['user'] = $userController->findUser($_POST['uid']);
                } else {
                    $arguments['user'] = $userController->findUser($_POST['uid']);
                }
            }

            $this->view->setArguments($arguments);
            $this->view->setTemplateFolder('Settings/Users');
            $this->view->setTemplate('EditUser');
            $this->view->render();

            return true;
        } catch (Exception $exception) {
            CoreException::writeError("Settings", $exception->getMessage(), "1554119612");

            return false;
        }
    }

    public function updateUserActiveAction()
    {
        try {
            $arguments = [];
            if ($_POST && IS_SUPER_ADMIN) {
                $userController = UserController::getInstance();
                $result = -1;
                if (isset($_POST['uid']) & !empty($_POST['uid']) & isset($_POST['value'])) {
                    $result = $userController->updateUserActive($_POST['uid'], $_POST['value']);
                }
                // if AJAX API
                if (isset($_GET['ax'])) {
                    if ((int)$result != 0) {
                        echo '1';
                    } else {
                        echo '0';
                    }

                    return true;
                }
            }

            $this->view->setArguments($arguments);
            $this->view->setTemplateFolder('Settings/Users');
            $this->view->setTemplate('EditUser');
            $this->view->render();

            return true;
        } catch (Exception $exception) {
            CoreException::writeError("Settings", $exception->getMessage(), "1554119612");

            return false;
        }
    }

    public function getOnlineUsers()
    {
        $analyse = $this->settingsRepository->getOnlineUsers();
        if (empty($analyse)) {
            return null;
        }

        return $analyse;
    }

}
