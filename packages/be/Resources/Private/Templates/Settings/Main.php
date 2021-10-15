<?php
/**
 * Created by PhpStorm.
 * User: majdothman
 * Date: 14.10.2021
 */
if (!defined("MS_BE")) die("Access Denied");
?>

<h4 class="text-dark text-center"><i class="fa fa-tools"></i> Settings</h4>
<hr>
<div class="row">
    <div class="col-md-6">
        <ul class="list-unstyled">
            <li class="p-1">
                <form method="post" class="m-0" action="<?= BASE_URL ?>/mscms/?controller=settings&action=editUser">
                    <input type="hidden" name="uid" value="<?= USER_DATA['uid'] ?>">
                    <a href="#" onclick="$(this).parent().submit();return false;">
                        <i class="fa fa-user-edit"></i>
                        Edit Your Account
                    </a>
                </form>
            </li>
            <?php
            if (\MS\Core\Controller\UserController::isAdmin()) {
                ?>
                <li class="p-1">
                    <a href="<?= BASE_URL ?>/mscms/?controller=settings&action=users">
                        <i class="fa fa-users"></i>
                        All users accounts [edit]
                    </a>
                </li>
                <li class="p-1">
                    <a href="<?= BASE_URL ?>/mscms/?controller=settings&clear_cache=1">
                        <i class="fa fa-broom"></i>
                        Clear
                    </a>
                </li>
                <?php
            }
            ?>
            <li class="p-1">
                <a href="<?php echo BASE_URL ?>/mscms/?controller=user&action=logout">
                    <i class="fa fa-sign-out-alt " style="font-size: 25px"></i> Logout
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-5">
        <ul class="settings-list">
            <?php
            if (\MS\Core\Controller\UserController::isSuperAdmin()) {
                ?>
                <li>
                    <form action="<?= BASE_URL ?>/mscms/?controller=settings&action=command" method="post">
                        <input type="hidden" name="post" value="1">
                        <i class="fa fa-terminal " aria-hidden="true"></i>
                        <input class="btn btn-link" type="submit" value="Run SQL Command as Admin">
                    </form>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
