<?php
// for cache files
$versionTimestamp = "1582202757";

if (!defined("MS_BE")) die("Access Denied");
?>
<html>
<head>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1,  width=device-width, height=device-height, target-densitydpi=device-dpi"/>
    <meta name="description" content="">
    <meta name="author" content="Majd Othman">
    <link rel="apple-touch-icon" sizes="57x57"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo BASE_URL ?>/mscms/Resources/assets/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title> <?php echo isset($this->getArguments()['pageTitle']) ? $this->getArguments()['pageTitle'] : BASE_URL; ?> </title>
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/mscms/Resources/assets/css/bootstrap.min.css?v=<?= $versionTimestamp ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/mscms/Resources/assets/css/all.min.css?v=<?= $versionTimestamp ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/mscms/Resources/assets/css/sidebars.css?v=<?= $versionTimestamp ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/mscms/Resources/assets/css/mscms.css?v=<?= $versionTimestamp ?>">
    <?php $this->renderHeadCss(); ?>

    <!-- Script -->
    <script src="<?php echo BASE_URL ?>/mscms/Resources/assets/js/fonts/all.min.js?v=<?= $versionTimestamp ?>" referrerpolicy="no-referrer"></script>
    <script src="<?php echo BASE_URL ?>/mscms/Resources/assets/js/jquery.js?v=<?= $versionTimestamp ?>" referrerpolicy="no-referrer"></script>
    <?php $this->renderHeadJs(); ?>
</head>
<body class="">
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <?= \MS\Core\Utility\MsUtility::renderFile(PARTIALS_PATH . '/Aside/Nav.php') ?>
        </div>
        <div class="col py-3">
            <?php
            $this->getMsBeBody();
            ?>
        </div>
    </div>
</div>
<?= $this->renderFooterJs() ?>
</body>
</html>

