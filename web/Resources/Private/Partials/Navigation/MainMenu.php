<?php
if (!defined("MS")) die("Access Denied");

?>
<nav class="main-menu">
    <ul>
        <li>
            <a href="<?= BASE_URL ?>/Home/">
                Home
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/Home/About">
                about
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/?p=page-1">
                Normal page without controller
            </a>
        </li>
    </ul>
</nav>
