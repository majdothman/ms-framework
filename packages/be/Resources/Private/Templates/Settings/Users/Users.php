<?php
/**
 * Created by PhpStorm.
 * User: majdothman
 * Date: 28.03.19
 * Time: 16:19
 */
if (!defined("MS_BE")) die("Access Denied");

/** if user is admin */
if (!IS_ADMIN) {
    \MS\Core\Utility\MsUtility::fe_dump(PAGE_NOT_FOUND);

    return;
}
$anlyse = \MS\Core\Be\Controller\SettingsController::getInstance()->getOnlineUsers();

$onlineUsers = null;
if (!empty($anlyse['onlineUsers'])) {
    $onlineUsers = $anlyse['onlineUsers'];
}
?>
<div class="be-list-users" style="width:100%">
    <div class="row">
        <div class="col-md-6">
            <h2> Users </h2>
        </div>
        <div class="col-md-6">
            <ul class="nav">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/mscms/?controller=settings&action=createUser"
                       class="btn btn-outline-success">
                        [+]
                        New User</a>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <h5>Online members of the site</h5>
            <ul class="list-group list-group-horizontal">
                <?php
                if (!empty($onlineUsers)) {
                    foreach ($onlineUsers as $onlineUser) {
                        ?>
                        <li class="list-group-item">
                            <i class="fa fa-circle text-success"></i>
                            <?= $onlineUser['firstname'] . ' ' . $onlineUser['lastname'] ?>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <table class="table filterDataTable table-striped ">
                <thead>
                <tr>
                    <th>Edit</th>
                    <th>Name</th>

                    <?php
                    if (\MS\Core\Controller\UserController::isSuperAdmin()) {
                        ?>
                        <th>Level</th>
                        <th>Last visit</th>
                        <?php
                    }
                    ?>
                    <th>Username</th>
                    <th>E-Mail</th>
                    <th>Active</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if (isset($this->arguments['users']) && !empty($this->arguments['users'])) {
                    foreach ($this->arguments['users'] as $user) {
                        ?>
                        <tr>
                            <td>
                                <form method="post" action="<?= BASE_URL ?>/mscms/?controller=settings&action=editUser">
                                    <input type="hidden" name="uid" value="<?= $user['uid'] ?>">

                                    <button id="btn-edit-<?= $user['uid'] ?>" type="submit"
                                            class="btn btn-sm btn-outline-dark"
                                    >
                                        <i class="far fa-edit"></i>
                                    </button>
                                </form>
                            </td>
                            <td><?= $user['firstname'] ?> <?= $user['lastname'] ?></td>
                            <?php
                            if (\MS\Core\Controller\UserController::isSuperAdmin()) {
                                ?>
                                <td><?= \MS\Core\Enum\UserGroups::getGroupTitle($user['idUserGroups']) ?></td>
                                <td>
                                    <small><?= date("d.m.Y,  H:i", $user['lastvisitDate']); ?></small>
                                </td>
                                <?php
                            }
                            ?>
                            <td><?= $user['username'] ?></td>
                            <td><a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
                            <td>
                                <?php
                                if (USER_DATA['idUserGroups'] == 1 || ($user['idUserGroups'] > 2 || $user['uid'] == USER_DATA['uid'])) {
                                    ?>
                                    <label class="switch">
                                        <input type="checkbox" class="user-active-unactive"
                                               data-uid="<?= $user['uid'] ?>"
                                               data-status="<?= $user['isActive'] ?>" <?= ($user['isActive'])
                                            ? 'checked'
                                            : ''; ?>>
                                        <span class="slider "></span>
                                    </label>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.user-active-unactive').each(function () {
            var element = $(this);
            var value;
            element.on('change', function () {
                if (element.is(':checked')) {
                    value = 1;
                } else {
                    value = 0;
                }
                $.ajax({
                    url: "<?= BASE_URL;?>/mscms/?controller=settings&action=updateUserActive&ax",
                    method: "POST",
                    data: {
                        'uid': element.data('uid'),
                        'value': value
                    },
                    success: function (result) {
                    },
                    error: function () {
                        $(element.data('target')).attr('disabled', true)
                    },
                });
            });
        });
    });
</script>