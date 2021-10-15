<?php
if (!defined("MS_BE")) die("Access Denied");
?>
<div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
    <a href="<?php echo BASE_URL ?>/mscms/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-5 d-sm-inline">MsCMS</span>
    </a>
    <ul class="ms-nav nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
        <li class="nav-item">
            <hr>
            <a href="<?php echo BASE_URL ?>/mscms/" class="nav-link align-middle px-0">
                <i class="fa fa-home"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
            </a>
        </li>
        <li class="nav-item">
            <hr>
            <a class="nav-link align-middle px-0" href="<?php echo BASE_URL ?>/mscms/?controller=settings">
                <i class="fa fa-tools"></i> <span class="ms-1 d-none d-sm-inline">Settings</span>
            </a>
        </li>

        <li class="nav-item">
            <hr>
            <a class="nav-link align-middle px-0" href="<?php echo BASE_URL ?>" target="_blank">
                <i class="fa fa-eye"></i> <span class="ms-1 d-none d-sm-inline">Show</span>
            </a>
        </li>
        <li class="nav-item">
            <hr>
            <a class="nav-link align-middle px-0 " style="width: 100%" href="<?php echo BASE_URL ?>/mscms/?controller=user&action=logout">
                <i class="fa fa-sign-out-alt"></i> <span class="ms-1 d-none d-sm-inline">Logout</span>
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown pb-4">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= BASE_URL . '/mscms/Resources/assets/images/avatar/avatar.png' ?>" alt="hugenerd" width="35" height="35" class="rounded-circle">
            <span class="d-none d-sm-inline mx-1"><?= USER_DATA['firstname'] . ' ' . USER_DATA['lastname'] ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow dropdown">
            <li><a class="dropdown-item" href="<?php echo BASE_URL ?>/mscms/?controller=settings"">Settings</a></li>
            <li><a class="dropdown-item" href="<?php echo BASE_URL ?>/mscms/?controller=user&action=logout">Logout</a></li>
        </ul>
    </div>
</div>
