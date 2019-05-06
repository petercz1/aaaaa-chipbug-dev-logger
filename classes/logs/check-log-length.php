<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * checks log length
 */
class Check_Log_length
{
	   /**
     * checks human log size (log.txt).
     * make sure it is less than $options['size_of_log']
     *
     * @return void
     */
    public function init(){
        try{
            $options = get_option('chipbug_logger_options');
            $max_records = $options['size_of_log'];
            $log_data = \file(ABSPATH . '/wp-content/log.txt');
            if (count($log_data) > $max_records) {
                $trim_human_data = count($log_data) - $max_records;
                $log_data = \array_slice($log_data, $trim_human_data);
            }
            // shove it back
            \file_put_contents(ABSPATH . '/wp-content/log.txt', $log_data);
        }
        catch (\Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
}