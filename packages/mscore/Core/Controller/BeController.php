<?php
declare(strict_types=1);

namespace MS\Core\Controller;

use MS\Core\View\Render;

abstract class BeController extends Controller
{
    public $view;

    public function __construct()
    {
        $this->view = Render::getInstance();
        $this->view->setLayout('BE-Default');
        $this->view->setTemplateFolder('');
        $this->view->setTemplate('Index');
        $this->view->setTemplateType('.php');
        $this->initialize();
    }
}