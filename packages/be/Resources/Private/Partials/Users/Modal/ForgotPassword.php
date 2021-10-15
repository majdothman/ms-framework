<?php
if (!defined("MS_BE")) die("Access Denied");

?>

<div class="modal fade" id="forgot-password-Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= BASE_URL ?>/mscms/?controller=user&action=sendResetPassword">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="forgotEmail">E-Mail *</label>
                            <input type="email" class="form-control" id="forgotEmail" name="forgotEmail" value="" required>
                            <input type="hidden" name="sendLink" value="1">
                            <button type="submit" class="btn btn-outline-secondary mt-3d">Send link to E-Mail</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>