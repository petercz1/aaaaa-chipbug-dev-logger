<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

class Load_Scripts{

    public $set_files;
	/**
	 * adds all actions to load scripts/css
	 *
	 * @return void
	 */
	public function init(Set_Files $set_files){
        $this->set_files = $set_files;
        $this->set_files->set();
		add_action('admin_enqueue_scripts', array($this, 'hook_my_js'));
        add_action('admin_enqueue_scripts', array($this, 'hook_my_css'));
	}

	 /**
     * WordPress hooks all js correctly
     * also adds chipbug_dev object to client JavaScript
     *
     * @param [type] $hook
     * @return void
     */
    public function hook_my_js($hook)
    {
		// loads scripts for log logger
        if (is_admin() && $hook == 'developer_page_chipbug-logger') {

			$logger_nonce = wp_create_nonce("number_used_once");
			$chipbug_dev_settings = array( 'ajax_url' => admin_url('admin-ajax.php'),'logger_nonce'=>$logger_nonce);

            wp_enqueue_script('chipbug-logger-script', plugins_url() .'/aaaaa-chipbug-dev-logger/admin/js/logger.js', array('jquery'));
            wp_enqueue_script('chipbug-sse-connect-script', plugins_url() .'/aaaaa-chipbug-dev-logger/admin/js/sse_connect.js', array('jquery'));
            wp_localize_script('chipbug-logger-script', 'chipbug_dev', $chipbug_dev_settings);
        }

		// loads scripts for log options
        if (is_admin() && $hook == 'developer_page_chipbug-logger-options') {

            error_log(plugins_url() .'/aaaaa-chipbug-dev-logger/admin/js/options.js');
			$options_nonce = wp_create_nonce("number_used_once");
			$chipbug_dev_settings = array( 'ajax_url' => admin_url('admin-ajax.php'),'options_nonce'=>$options_nonce);

            wp_enqueue_script('chipbug-options-script', plugins_url() .'/aaaaa-chipbug-dev-logger/admin/js/options.js', array('jquery'));
            wp_enqueue_script('chipbug-validator-script', plugins_url() .'/aaaaa-chipbug-dev-logger/admin/js/form_validator.js', array('jquery'));
            wp_localize_script('chipbug-options-script', 'chipbug_dev', $chipbug_dev_settings);
        }
    }

    /**
     * WordPress hooks all css correctly
     *
     * @return void
     */
    public function hook_my_css($hook)
    {
        if (is_admin() && $hook == 'developer_page_chipbug-logger') {
            wp_enqueue_style('chipbug-errors-style', plugins_url() .('/admin/css/logger.css'));
        }
        if (is_admin() && $hook == 'developer_page_chipbug-logger-options') {
            wp_enqueue_style('chipbug-errors-style', plugins_url() .('/admin/css/options.css'));
        }
    }

}