<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * handles ajax call to delete logs
 * human-readable /wp-content/log.txt is reset to ""
 * json file /wp-content/log.json is reset to "[]"
 */
class Manage_Options
{
    /**
     * @var Manage_Options
     */
    private $manage_options;
    /**
     * initialise
     *
     * @return void
     */
    public function init(){
        $this->add_action_for_manage_options();
    }
    /**
     * adds ajax action
     */
    public function add_action_for_manage_options()
    {
        add_action('wp_ajax_get_options', array($this,'get_options'));
        add_action('wp_ajax_save_options', array($this,'save_options'));
    }

     /**
     * called by ajax, gets options
     * checks nonce first
     * @return void
      */
      public function get_options()
      {
          try {
              if ($this->check_nonce()) {
                  error_log(plugin_dir_path(__FILE__) . '/serialized_options.txt');
                  $options = \file_get_contents( plugin_dir_path(__FILE__) . 'serialized_options.txt');
                  $options = unserialize($options);
				  echo json_encode($options);
				  //echo json_encode(get_option('chipbug_logger_options'));
			  }
			  die();
          } catch (Exception $ex) {
              error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
          }
      }
      
       /**
     * called by ajax, sets options
     * checks nonce first
     * @return void
    */
    public function save_options()
    {
        // TODO - remove before release!
        \sleep(1);
        $error_message = '';
        try {
            if ($this->check_nonce()) {
                // TODO - split this out for each option!
                // TODO - code for size of log
                // TODO - code for refresh rate
				if(isset($_POST['size_of_log']) && \is_numeric($_POST['size_of_log'])){
					$options['refresh_rate'] = $_POST['refresh_rate'];
					$options['size_of_log'] = $_POST['size_of_log'];
					$options['include_trace'] = $_POST['include_trace'];
                    $options['include_file_path'] = $_POST['include_file_path'];
                    $options['file_path'] = get_home_path();
                    
                }
                error_log(print_r($_POST, true));
                file_put_contents( plugin_dir_path(__FILE__) . 'serialized_options.txt', serialize( $options ) );
				//update_option('chipbug_logger_options', $options);
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
	}
	
    /**
     * checks nonce from ajax call
     * @return bool
     */
    private function check_nonce()
    {
		if(isset($_POST['options_nonce'])){
			$nonce = $_POST['options_nonce'];
		} else if(isset($_GET['options_nonce'])){
			$nonce = $_GET['options_nonce'];
		}
        if (wp_verify_nonce($nonce, 'number_used_once')) {
            return true;
        } else {
            return false;
        }
    }
}
