<?php
if (!defined("MS")) die("Access Denied");

$args = $this->arguments;
dump($args);
?>
<h1>About Template'</h1>
<h3><?= $args['message'] ?></h3>
