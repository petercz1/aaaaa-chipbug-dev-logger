<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * handles writing of message array from error_handler(), shutdown_handler() and writelog()
 */
class Manage_Logs
{
    /**
     * writes message array to json file.
     * @param array $message
     */
    public function write(array $error)
    {
        try {
            // write a blank json file if non exists
            if (!\file_exists(ABSPATH . '/wp-content/log.json')) {
                \file_put_contents(ABSPATH . '/wp-content/log.json', '[]');
            }
            // get all current json errors and prepend new $error
            $json_data = json_decode(\file_get_contents(ABSPATH . '/wp-content/log.json'));
            // prepend data
            if (isset($json_data)) {
                array_unshift($json_data, $error);
                // shove it back
                \file_put_contents(ABSPATH . '/wp-content/log.json', \json_encode($json_data));
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
}
