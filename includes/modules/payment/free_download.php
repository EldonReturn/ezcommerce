<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class free_download {
    var $code, $title, $description, $enabled;

// class constructor
    function free_download() {
      global $order;

      $this->code = 'free_download';
      $this->title = MODULE_PAYMENT_FREE_DOWNLOAD_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_FREE_DOWNLOAD_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_FREE_DOWNLOAD_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_FREE_DOWNLOAD_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_FREE_DOWNLOAD_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_FREE_DOWNLOAD_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
    	global $order;
    	if(DOWNLOAD_ENABLED && $order->content_type == 'virtual' && (int)substr($order->info["total"], 1) == 0){
    		// Enabled when download option is on and
    		// the order only contains virtual products and
    		// they are free
    		// TODO: need a more robust way to get the total prize
    		$this->enabled = true;
    	}else{
    		$this->enabled = false;
    	}
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_FREE_DOWNLOAD_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Free Download Module', 'MODULE_PAYMENT_FREE_DOWNLOAD_STATUS', 'True', 'Do you want to accept Free Download?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_FREE_DOWNLOAD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_FREE_DOWNLOAD_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
   }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_FREE_DOWNLOAD_STATUS', 'MODULE_PAYMENT_FREE_DOWNLOAD_ORDER_STATUS_ID', 'MODULE_PAYMENT_FREE_DOWNLOAD_SORT_ORDER');
    }
  }
?>
