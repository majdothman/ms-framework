<?php

namespace MS\Fe\Controller;

use MS\Core\Controller\FeController;
use MS\Fe\Repository\HomeRepository;
use MS\Core\Controller\CoreException;

class HomeController extends FeController
{
    protected HomeRepository $homeRepository;

    public static function getInstance()
    {
        if (empty(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

    public function initialize()
    {
        $this->homeRepository = HomeRepository::getInstance();
    }

    public function mainAction(): bool
    {
        try {
            // Set which Layout, template and Folder of Template to render
            $this->view->setLayout('Default');
            $this->view->setTemplate('Main');
            $this->view->setTemplateFolder('Home');
            $this->view->setTemplateType('.php');
            $arguments = [
                'data' => $this->homeRepository->getData(),
            ];
            // Send Arguments to File
            $this->view->setArguments($arguments);
            // name of file in ViewFolder
            $this->view->render();

            return true;
        } catch (\Exception $exception) {
            CoreException::writeError("About", $exception->getMessage(), "1633984571");
            return false;
        }
    }

    public function aboutAction(): bool
    {
        try {
            $this->view->setTemplate('About');
            // Send Arguments to File
            $this->view->setArgument(['pageTitle' => 'About']);
            $this->view->setArgument(['message' => 'Hello, i am an Argument from AboutAction in HomeController :)']);

            // name of file in ViewFolder
            $this->view->render();

            return true;
        } catch (\Exception $exception) {
            CoreException::writeError("Home", $exception->getMessage(), "1633984571");
            return false;
        }
    }

}
