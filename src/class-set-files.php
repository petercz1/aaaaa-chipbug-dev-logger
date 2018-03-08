<?php
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * handles writing of message array to both json and human files
 */
class Set_Files
{
	   /**
     * checks for log files and creates if not present.
     * @return void
     */
    public function set()
    {
        try {
            if (!\file_exists(ABSPATH . '/wp-content/log.json')) {
                \file_put_contents(ABSPATH . '/wp-content/log.json', '[]');
            }
            if (!\file_exists(ABSPATH . '/wp-content/log.txt')) {
                \file_put_contents(ABSPATH . '/wp-content/log.txt', '');
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . ' function ' . __FUNCTION__ . '() line ' . $ex->line . ': ' . $ex->message);
        }
    }
}