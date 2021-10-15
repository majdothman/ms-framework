<?php
if (!defined("MS_BE")) die("Access Denied");

if (!$_POST || !isset($_POST['post'])) {
    return;
}
if (!\MS\Core\Controller\UserController::isSuperAdmin()) {
    echo 'You must login as admin';
    return;
}
?>

<div class="row">
    <div class="col-12">
        <h5>Query</h5>
        <form method="post" class="p-2" action="<?= BASE_URL ?>/mscms/?controller=settings&action=command">
            <input type="hidden" name="post" value="1">
            <textarea class="form-control" name="command" id="command" cols="30" rows="5"><?= (isset($_POST['command']) && !empty($_POST['command'])) ? $_POST['command'] : '' ?></textarea>
            <input type="submit" class="btn btn-outline-success m-2" value="Run command">
            <a href="<?= BASE_URL ?>/mscms/?controller=settings"> Back</a>
        </form>
    </div>
    <div class="col-12">
        <?php if (isset($this->arguments['result'])) dump($this->arguments['result']) ?>
    </div>
</div>
