<?php
if (!defined("MS_BE")) {
    die("Access Denied");
}
?>

<h4 class="text-dark text-center"><i class="fa fa-home"></i> Home</h4>
<hr>

<div class="row">
    <div class="col-12">
        Welcome in MsCMS, you logged in as: <br>
        <p class="text-info">
            <?= USER_DATA['firstname'] . ' ' . USER_DATA['lastname'] ?> (<?= \MS\Core\Enum\UserGroups::getGroupTitle((USER_DATA['idUserGroups'])) ?>)
        </p>
    </div>

    <div class="col-12 mt-5 p-2 text-center">
        <img src="<?php echo BASE_URL ?>/mscms/Resources/assets/images/logos/MsCMS.png" alt="MsCMS logo">
    </div>
</div>