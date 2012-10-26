<?php
require('includes/application_top.php');
require('includes/classes/captcha.php');

$cpt = new captcha();
$cpt->ext_num_type = '';
$cpt->ext_pixel = true;
$cpt->ext_line = true;
$cpt->ext_rand_y = true;
$cpt->create();
?>