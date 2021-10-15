<?php
declare(strict_types=1);

namespace MS\Core\Controller;

use MS\Core\View\FERender;

abstract class FeController extends Controller
{
    public $view;

    public function __construct()
    {
        $this->view = FERender::getInstance();
        $this->view->setLayout('Default');
        $this->view->setTemplateFolder('Home');
        $this->view->setTemplate('Index');
        $this->view->setTemplateType('.php');
        $this->initialize();
    }
}