<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * adds all WordPress actions needed for this plugin
 */
class Setup
{
    public function init()
    {
        try {
            define('aaaaa-chipbug-dev-logger', '3.0.1');

            $add_menus = new \Chipbug\Tools\Logger\Add_Menus();
            $add_menus->init();

            $load_scripts = new \Chipbug\Tools\Logger\Load_Scripts();
            $load_scripts->init(new \Chipbug\Tools\Logger\Set_Files());

            $php_error_handler = new \Chipbug\Tools\Logger\Php_Error_Handler();
            $php_error_handler->init(new \Chipbug\Tools\Logger\Manage_Logs(), new \Chipbug\Tools\Logger\Mysql_Log());

            $delete_logs = new Delete_Logs();
            $delete_logs->init();

            $manage_options = new Manage_Options();
            $manage_options->init();

        } catch (\Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
}