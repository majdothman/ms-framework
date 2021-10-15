<?php if (!defined("MS")) die("Access Denied"); ?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> <?php echo isset($this->getArguments()['pageTitle']) ? $this->getArguments()['pageTitle'] : BASE_URL; ?> </title>
    <?php $this->renderHeadCss(); ?>
    <?php $this->renderHeadJs(); ?>
</head>
<body>
<!-- MS Start -->
<?php
/**
 * You have to configure the application through the initialization file: config.yaml
 *  - The most important configurations are: DB, SYS[root_path], FE[BASE_URL]
 *
 * to render Partial or some files, you can use (require_once OR MsUtility::renderFile)
 *
 * You have to work with OOP:
 *
 * how to create an new page or feature:
 * - You can see the control for the home page, located in /packages/fe/Classes/Controller/HomeController.php
 * - please create Controller with right name, ex: SomeController.php, don't remove ...Controller.php
 * - if you get Data of Database for this Controller, you can see too /packages/fe/Classes/Repository/HomeRepository.php
 * - Repository is a provider to communication with Database, and there ist a query builder for select, update, insert
 * - to build a QueryBuilder look to help: -> mscore/Core/Utility/QueryBuilder.php
 *
 * Call Controller or page
 * - to call controller you can assigned in URL: www.example.com/Controller/ActionOfController Or  www.example.com/?controller=NameOfController&action=NameOfAction
 */
?>
<?php \MS\Core\Utility\MsUtility::renderFile(FE_PARTIALS_PATH . '/Navigation/MainMenu.php'); ?>
<?= $this->getMsBody() ?>
<?= $this->renderFooterJs() ?>
<!-- MS End -->
</body>
</html>

