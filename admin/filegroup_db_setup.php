<?php
/*
  $Id: products_attributes.php,v 1.52 2003/07/10 20:46:01 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  $group_zero = 'No File Group'; // DO NOT change the name of file group #0 !!
  $feedback = '';

  for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
    $filegroup_initial_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " where download_group_id = 0 and language_id = '" . (int)$languages[$i]['id'] . "'");
    if(tep_db_num_rows($filegroup_initial_query) == 0) {
      tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD_GROUPS . " (download_group_id, download_group_name, language_id) values (0, '" . tep_db_input($group_zero) . "', '" . (int)$languages[$i]['id'] . "')");
      $feedback .= 'File group 0 for lauguage: ' . $languages[$i]['name'] . ' inserted.<br>';
    } else {
      $feedback .= 'File group 0 for lauguage: ' . $languages[$i]['name'] . ' already exists.<br>';
    }
    $feedback .= '<br>';
  }
  echo $feedback . 'Don\'t forget to delete filegroup_db_setup.php from your catalog/admin/ directory.';
?>
