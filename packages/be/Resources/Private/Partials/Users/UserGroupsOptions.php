<?php
if (!defined("MS_BE")) {
    die("Access Denied");
}
// var $userGroupOption come from who required this Options
if (!isset($userGroupOption) || empty($userGroupOption)) {
    $userGroupOption = 0;
}
//
/** if user is SuperAdmin */
if ((int)USER_DATA['idUserGroups'] != 1 && (int)USER_DATA['idUserGroups'] != 2) {
    return '';
}

?>
<label for="userGroup">Group *</label>
<select id="userGroup" name="userGroup" class="form-control">
    <option <?php echo ($userGroupOption == 0)
        ? 'selected'
        : ''; ?>>Choose...
    </option>

    <option value="1" <?php echo ($userGroupOption == 1)
        ? 'selected'
        : ''; ?>>Super Admin (have all permission)
    </option>
    <option value="2" <?php echo ($userGroupOption == 2)
        ? 'selected'
        : ''; ?>>Admin
    </option>
    <option value="3" <?php echo ($userGroupOption == 3)
        ? 'selected'
        : ''; ?>>User
    </option>
</select>