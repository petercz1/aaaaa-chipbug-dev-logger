<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * checks log.json exists - if not, creates an empty log
 */
class Set_Files
{
	   /**
     * checks for log files and creates if not present.
     * @return void
     */
    public function set():void
    {
        try {
            if (!\file_exists(ABSPATH . '/wp-content/log.json')) {
                \file_put_contents(ABSPATH . '/wp-content/log.json', '[]');
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . ' function ' . __FUNCTION__ . '() line ' . $ex->line . ': ' . $ex->message);
        }
    }
}
