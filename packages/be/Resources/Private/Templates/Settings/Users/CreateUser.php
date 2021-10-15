<?php
/**
 * Created by PhpStorm.
 * User: majdothman
 * Date: 01.04.19
 * Time: 10:49
 */

if (!defined("MS_BE")) die("Access Denied");

/** if user is admin */
if (!defined('USER_DATA') || ((int)USER_DATA['idUserGroups'] != 1 && (int)USER_DATA['idUserGroups'] != 2)) {
    \MS\Core\Utility\MsUtility::fe_dump('* You don\'t have permission to do this action.', 'danger', 1634331420);

    return;
}
?>

<div class="be-list-users" style="width:100%">
    <div class="row">
        <div class="col-md-6">
            <h2>Create new User</h2>
        </div>
        <div class="col-md-6">
            <ul class="nav">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/mscms/?controller=settings&action=users" class="btn btn-link">Show all
                        users</a>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <form method="post" action="<?= BASE_URL ?>/mscms/?controller=settings&action=createUser">
                <div class="row">
                    <div class="form-group col">
                        <label for="firstname">First name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required
                               value="<?= isset($_POST['firstname'])
                                   ? $_POST['firstname']
                                   : ''; ?>">
                    </div>
                    <div class="form-group col">
                        <label for="lastname">Last name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname"
                               value="<?= isset($_POST['lastname'])
                                   ? $_POST['lastname']
                                   : ''; ?>"
                               required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label for="username">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($_POST['username'])
                            ? $_POST['username']
                            : ''; ?>" required>
                    </div>
                    <div class="form-group col">
                        <label for="email">E-Mail *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($_POST['email'])
                            ? $_POST['email']
                            : ''; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label for="password">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <?php
                        // $userGroupOption as Param to required_file
                        $userGroupOption = 0;
                        require_once PARTIALS_PATH . '/Users/UserGroupsOptions.php';
                        ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="<?= BASE_URL ?>/mscms/?controller=settings&action=users" class="btn btn-outline-dark">Cancel</a>
            </form>
        </div>
    </div>
</div>
