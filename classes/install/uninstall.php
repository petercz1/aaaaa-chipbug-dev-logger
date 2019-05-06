<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * uninstall
 */
class Uninstall
{
	/**
	 * uninstall - deletes all traces!
	 *
	 * @return void
	 */
	public static function uninstall():void{
		delete_option('chipbug_logger_options');

		// TODO - delete plugin
	}
}