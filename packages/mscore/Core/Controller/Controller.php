<?php
declare(strict_types=1);

namespace MS\Core\Controller;

use MS\Core\View\FERender;

abstract class Controller implements \MS\Core\Inerface\Controller
{
    public static $instance = null;

    public function __construct()
    {

    }

    /**
     * Initializes the current class.
     */
    public function initialize()
    {
    }

    public function mainAction(): bool
    {
        try {
            $this->view = FERender::getInstance();
            $this->view->setLayout('Default');
            $this->view->setTemplateFolder('Home');
            $this->view->setTemplate('Index');
            $this->view->setTemplateType('.php');

            $this->view->render();
            return true;
        } catch (Exception $exception) {
            CoreException::writeError("Settings", $exception->getMessage(), "1634320897");

            return false;
        }
    }
}