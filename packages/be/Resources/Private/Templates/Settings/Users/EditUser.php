<?php
/**
 * Created by PhpStorm.
 * User: majdothman
 */

if (!defined("MS_BE")) die("Access Denied");
// THIS PAGE COULD WITH POST
/** if user is admin or this is my account */
if ($_POST) {
    if (isset($_POST['uid']) && (int)$_POST['uid'] != (int)USER_DATA['uid']) {
        if (!defined('USER_DATA') || ((int)USER_DATA['idUserGroups'] != 1 && (int)USER_DATA['idUserGroups'] != 2)) {
            \MS\Core\Utility\MsUtility::fe_dump('* You can\'t do this action.', 'danger', 1634331424);

            return;
        }
    }
} else {
    \MS\Core\Utility\MsUtility::fe_dump('* You can\'t do this action.', 'danger', 1634331425);

    return;
}

// Start
$user = null;
if (isset($this->arguments['user']) && !empty($this->arguments['user'])) {
    $user = $this->arguments['user'];
} else {
    \MS\Core\Utility\MsUtility::fe_dump('* There is no User', 'info', 1634331426);

    return;
}

/**
 * Only Super Admin can the admin editing.
 */
if ($user['uid'] != USER_DATA['uid']) {
    if ((((int)$user['idUserGroups'] == 2 || (int)$user['idUserGroups'] == 1)) && (USER_DATA['idUserGroups'] != 1)) {
        \MS\Core\Utility\MsUtility::fe_dump('* You can\'t do this action.', 'danger', 1634331427);

        return;
    }
}
?>
<div class="be-list-users" style="width:100%">
    <div class="row">
        <div class="col-md-6">
            <h2>Edit User</h2>
        </div>
        <div class="col-md-6">
            <ul class="nav">
                <?php
                if (IS_ADMIN) {
                    ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/mscms/?controller=settings&action=users" class="btn btn-link">
                            Show all users
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <b class="text-info">
                <?= $user['firstname'] . ' ' . $user['lastname'] ?>
                <small>[<?= \MS\Core\Enum\UserGroups::getGroupTitle($user['idUserGroups']) ?>]</small>
            </b>
            <p class="row m-2">
                <small>
                    Username: <?= $user['username'] ?> - E-Mail: <?= $user['email'] ?>
                    <br>
                    Created at: <?= date("d.m.y", $user['crdate']); ?>
                    <br>
                    Last Active at: <?= date("d.m.y,  H:i", $user['lastvisitDate']); ?>
                </small>
            </p>

            <hr>
            <form method="post" action="<?= BASE_URL ?>/mscms/?controller=settings&action=editUser">
                <div class="row">
                    <div class="form-group col">
                        <label for="firstname">First name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname"
                               value="<?= $user['firstname'] ?>" required>
                    </div>
                    <div class="form-group col">
                        <label for="lastname">Last name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname"
                               value="<?= $user['lastname'] ?>" required>
                    </div>
                </div>
                <?php
                if (IS_ADMIN) {
                    ?>
                    <div class="row">
                        <div class="form-group col">
                            <label for="username">Username *</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   placeholder="<?= $user['username'] ?>" autocomplete="off">
                        </div>
                        <div class="form-group col">
                            <label for="email">E-Mail *</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="<?= $user['email'] ?>" autocomplete="off">
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="form-group col">
                        <label for="password">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="*******" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                               placeholder="<?= $user['address'] ?>">
                    </div>
                    <div class="form-group col">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                               placeholder="<?= $user['phone'] ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label for="info">More Information</label>
                        <textarea class="form-control" id="info" name="info" cols="30"
                                  rows="6"><?= $user['info'] ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">

                        <?php
                        // $userGroupOption as Param to required_file
                        $userGroupOption = $user['idUserGroups'];
                        require_once PARTIALS_PATH . '/Users/UserGroupsOptions.php';
                        ?>
                    </div>
                </div>
                <?php
                if (IS_ADMIN) {
                    ?>
                    <div class="row">
                        <div class="form-group col text-info text-right">
                            <label for="hidden">
                                <?= ($user['isActive'] == 1)
                                    ? 'Account is Active'
                                    : 'Account is Deactivate' ?>
                            </label>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="hideThis">
                    <input type="hidden" name="uid" value="<?= $user['uid'] ?>">
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
