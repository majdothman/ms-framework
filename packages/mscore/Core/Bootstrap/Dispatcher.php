<?php

namespace MS\Core\Bootstrap;

use MS\Core\Controller\CoreException;
use MS\Core\Utility\DB;
use MS\Core\View\FERender;
use MS\Core\View\Render;

/**
 * Class Dispatcher
 *
 * @package Bootstrap
 */
class Dispatcher
{
    public static function setupDB(): bool
    {
        if (file_exists(rtrim(MS_ENV['SYS']['root_path'], '/') . '/web/SETUP_DB')) {
            try {
                return DB::getInstance()->firstImportDB();
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    public static function dispatchBE($vendorName = 'MS\\Core')
    {
        try {
            $view = Render::getInstance();
            $view->setLayout('BE-Default');

            if (!empty($_GET["controller"])) {
                $controller = ucfirst($_GET["controller"]) . "Controller";
                /** controller < not > in Core */
                if (class_exists('\\' . $vendorName . '\\Controller\\' . $controller)) {
                    $controller = '\\' . $vendorName . '\\Controller\\' . $controller;
                } /** controller in Core */
                elseif (class_exists('\\' . $vendorName . '\\Be\\Controller\\' . $controller)) {
                    $controller = '\\' . $vendorName . '\\Be\\Controller\\' . $controller;
                }
                /** Look If Controller founded */
                if (class_exists($controller)) {
                    if (method_exists($controller, 'getInstance')) {
                        $controller = $controller::getInstance();
                    } else {
                        $controller = new $controller();
                    }
                    /** If method exist */
                    if (!empty($_GET["action"]) && method_exists($controller, $_GET["action"] . 'Action')) {
                        $methodName = $_GET["action"] . 'Action';
                        $controller->$methodName();
                    } else {
                        /** If method NOT exist, if Controller -> Action not defined */
                        if (method_exists($controller, 'mainAction')) {
                            $controller->mainAction();
                        }
                    }
                } else {
                    $view->setTemplateFolder('Pages');
                    /** If Controller not founded, go to 404 Page */
                    if (isset($_GET['p']) && file_exists($view->getTemplateFolder() . '/' . ucfirst($_GET['p']) . '' . $view->getTemplateType())) {
                        switch ($_GET['p']) {
                            default:
                                $view->setTemplate(ucfirst($_GET['p']));
                                $view->render();
                                break;
                        }
                    } else {
                        /** GET[p]: it is mean page name, if empty go to home */
                        $view->setTemplate('404');
                        $view->render();
                    }
                }
            } else {
                /** If Controller not founded, go to 404 Page */
                if (isset($_GET['p']) && file_exists(TEMPLATE_PATH . '/Pages/' . ucfirst($_GET['p']) . '' . $view->getTemplateType())) {

                    $view->setTemplateFolder('Pages');
                    switch ($_GET['p']) {
                        default:
                            $view->setTemplate(ucfirst($_GET['p']));
                            break;
                    }
                } else {
                    /** GET[p]: it is mean page name, if empty go to home */
                    $view->setTemplateFolder('');
                    $view->setTemplate('Index');
                    $view->render();
                }
            }
            return true;
        } catch (\Exception $exception) {
            CoreException::writeError("Dispatcher", $exception->getMessage(), "1540465712");

            return false;
        }
    }

    public static function dispatchFE($vendorName = 'MS\\Fe')
    {
        try {
            $request = null;
            if (!empty($_SERVER["REQUEST_URI"])) {
                $request = Route::parse($_SERVER["REQUEST_URI"]);
            }
            $view = FERender::getInstance();
            $view->setLayout('Default');
            $controllerName = '';
            $actionName = '';
            if (!empty($request['controller'])) {
                $controllerName = $request['controller'];

            }
            if (!empty($request['action'])) {
                $actionName = $request['action'];
            }

            if (!empty($_GET["controller"])) {
                $controllerName = $_GET["controller"];
            }
            if (!empty($_GET["action"])) {
                $actionName = $_GET['action'];
            }

            if (!empty($controllerName)) {
                $controller = ucfirst($controllerName) . "Controller";
                /** controller < not > in Core */
                if (class_exists('\\' . $vendorName . '\\Controller\\' . $controller)) {
                    $controller = '\\' . $vendorName . '\\Controller\\' . $controller;
                }

                /** Look If Controller founded */
                if (class_exists($controller)) {
                    if (method_exists($controller, 'getInstance')) {
                        $controller = $controller::getInstance();
                    } else {
                        $controller = new $controller();
                    }
                    /** If method exist */
                    if (!empty($actionName) && method_exists($controller, $actionName . 'Action')) {
                        $methodName = $actionName . 'Action';
                        $controller->$methodName();
                    } else {
                        /** If method NOT exist, if Controller -> Action not defined */
                        $controller->mainAction();
                    }
                } else {
                    $view->setTemplateFolder('Pages');
                    /** If Controller not founded, go to 404 Page */
                    if (isset($_GET['p']) && file_exists($view->getTemplateFolder() . '/' . ucfirst($_GET['p']) . '' . $view->getTemplateType())) {
                        switch ($_GET['p']) {
                            default:
                                $view->setTemplate(ucfirst($_GET['p']));
                                $view->render();
                                break;
                        }
                    } else {
                        /** GET[p]: it is mean page name, if empty go to home */
                        $view->setTemplate('404');
                        $view->render();
                    }
                }
            } else {
                /** If Controller not founded, go to 404 Page */
                /** If Controller not founded, go to 404 Page */
                if (isset($_GET['p']) && file_exists(FE_TEMPLATE_PATH . '/Pages/' . ucfirst($_GET['p']) . '' . $view->getTemplateType())) {
                    $view->setTemplateFolder('Pages');
                    switch ($_GET['p']) {
                        default:
                            $view->setTemplate(ucfirst($_GET['p']));
                            break;
                    }
                } else {
                    /** GET[p]: it is mean page name, if empty go to home */
                    $view->setTemplate('index');
                    $view->render();
                }
            }

            return true;
        } catch (\Exception $exception) {
            CoreException::writeError("Dispatcher", $exception->getMessage(), "1540465712");
            return false;
        }
    }
}
