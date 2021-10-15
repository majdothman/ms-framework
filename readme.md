# Ms Framework

- build it with PHP
- easy install and easy build website

# Install

- clone git repository: ` git clone https://github.com/majdothman/ms-framework.git`
- run `composer install`
- adjust config.yaml:
    - DB setup connection with database.
    - SYS[root_path]: root of server path. ex: /var/www/html
    - SYS[enable_log]: 0: production, 1: Developers can see and track bugs
    - FE[BASE_URL]: the URL of frontend or web directory
- Database:
    - Automatic will import the Database after add connection data in file config.yaml
        - create this file in web: /web/SETUP_DB
    - Manuel import DB to your server /packages/mscore/Core/sql.sql

# Create Controller or Page

## Frontend

1. Controller

<code>
    packages/fe/Classes/Controller/CostumController.php

    namespace MS\Fe\Controller;
    
    use MS\Core\Controller\FeController;
    use MS\Fe\Repository\CustomRepository;
    use MS\Core\Controller\CoreException;
    
    class CustomController extends FeController
    {
    // Repository to get data from Database
    protected CustomRepository $customRepository;
    
        public static function getInstance()
        {
            if (empty(self::$instance)) self::$instance = new self();
            return self::$instance;
        }
    
        public function initialize()
        {
            $this->customRepository = CustomRepository::getInstance();
        }
    
        /**
         * this action has a Template under /Resources/Templates/Custom/Main.php
         * @return bool
         */
        public function mainAction(): bool
        {
            try {
                // Set which Layout, template and Folder of Template to render
                $this->view->setLayout('Default');
                $this->view->setTemplate('Main');
                $this->view->setTemplateFolder('Custom');
                $this->view->setTemplateType('.php');
                $arguments = [
                    'data' => $this->customRepository->getData(),
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
    }

</code>

2. Repository: CustomRepository, to connect to database and get Data, then passed to Controller

<code>
    packages/fe/Classes/Repository/CustomRepository.php 

    namespace MS\Fe\Repository;

    use MS\Core\Model\Repositories;
    
    class CustomRepository extends Repositories
    {
    protected static $instance = null;
    
        /**
         * Get instance of this Class
         *
         * @return CustomRepository
         */
        public static function getInstance()
        {
            if (empty(self::$instance)) self::$instance = new self();
            return self::$instance;
        }
    
        /**
         * You can here connect to DB and get data.
         * @return array
         */
        public function getData(): array
        {
            $data = [];
            /**
             * You can here a query building to get Data from Database and returned to Controller
             * ex:
             * $onlineUsers = $this->getQueryBuilder()
             * ->select()
             * ->setTableName('be_users')
             * ->columns(['uid', 'firstname', 'lastname', 'lastvisitDate'])
             * ->andWhere()
             * ->eq(['isOnline' => 1])
             * ->andWhere()
             * ->biggerThen(['lastvisitDate' => (time() - 60)])
             * ->limit(50)
             * ->execute();
             */
            return $data;
        }
    }

</code>

3. Template for Custom, The name of Template should be like action in the Controller CustomController->MainAction(). in our case the template name is "Main.php"

- you can easy way to create a Template with MS structure:
    - Create Main.php in `/web/Resources/Private/Templates/Custom/Main.php`
    - you can use Arguments, they come from CustomController `$this->arguments`

<code>
    Main.php

    echo 'Hello wolrd from Main Template';
    var_dump($this->arguments);

</code>

4. Call this Controller and method from Frontend:

- Your_Domain.com/Controller/Action
    - `https://msframework.ddev.site/Custom/main`
    - OR `https://msframework.ddev.site/?controller=custom&action=main`

## How to Create Page without Controller

- Easy way,
    - create a Page in: `/web/Resources/Private/Templates/Pages/MyPage.php`
    - Call the page in FE: `https://msframework.ddev.site/?p=MyPage`

# Backend?

- you can open Backend: /web/mscms/login.php

## you can create Controller for Backend like Frontend with small different:

- Backend Controller extends from BeeController: ex: `class CustomController extends BeController`
- and the Template are in /web/mscms/Resources/

## Note:
use security syntax of every Template or file:

<code>
    PHP

    For BE: if (!defined("MS_BE")) die("Access Denied");
    For FE: if (!defined("MS")) die("Access Denied");
</code>
