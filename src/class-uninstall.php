<?php
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * uninstall
 */
class Uninstall_Logger
{
	/**
	 * uninstall - deletes all traces!
	 *
	 * @return void
	 */
	public static function uninstall(){
		delete_option('chipbug_logger_options');
	}
}