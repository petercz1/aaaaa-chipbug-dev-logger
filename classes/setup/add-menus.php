<?php
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * adds WordPress menus
 */
class Add_Menus
{
	/**
	 * adds actions for menus
	 *
	 * @return void
	 */
    public function init()
    {
		add_action('admin_menu', array($this, 'add_menu'));
		add_action('admin_head', array($this,'chipbug_dev_newtab'));
    }

      /**
     * adds chipbug logger to submenu
     * @return void
     */
    public function add_menu()
    {
        add_menu_page('developer', 'developer', 'manage_options', 'chipbug-developer');
        add_submenu_page('chipbug-developer', 'developer', '', 'manage_options', 'chipbug-developer');
        add_submenu_page('chipbug-developer', 'WP logger', 'Developer logger', 'manage_options', 'chipbug-logger', array($this,'include_logger_page'));
        add_submenu_page('chipbug-developer', 'WP logger options', 'Developer options', 'manage_options', 'chipbug-logger-options', array($this,'include_options_page'));
    }

    /**
     * loads html for the admin page
     * @return void
     */
    public function include_logger_page()
    {
        try {
            include plugin_dir_path(__DIR__) . '../admin/logger.html';
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }

    /**
     * loads html for the options page
     * @return void
     */
    public function include_options_page()
    {
        try {
            include plugin_dir_path(__DIR__) . '../admin/options.html';
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
	}
	
	 /**
     * forces opening of logger in new tab
     * hence logger will keep running if the original tab crashes.
     * @return void
     */
    public function chipbug_dev_newtab()
    {
        ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {   
            $("a[href*='chipbug-logger']").attr('target','_blank');  
        });
    </script>
    <?php
    }
}