<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * writes to human-readable file
 */
class Write_Human
{
	public $tidy;

    public function init(Tidy_Text $tidy)
    {
        $this->tidy = $tidy;
        $check_log_length = new \Chipbug\Tools\Logger\Check_Log_length();
        $check_log_length->init();
    }
  
   /**
     * write to wp_content/log.txt.
     * human-readable format of message array
     * added to wp-content/log.txt
     * @param array $message
     * @return void
     */
    public function write_human(array $message)
    {
        try {
            $human_string = '';

            // fire off new log
            if ('NEW LOG' == $message['error_name']) {
                $human_string .= '------------------------------' . PHP_EOL . 'NEW LOG' . PHP_EOL;
            }

            // loop through any MySQL errors - details will be an array from $EZSQL_ERROR
            if ('MySQL' == $message['error_name']) {
                $human_string .= 'MySQL: ';
                for ($mysql_counter = 0; $mysql_counter < sizeof($message['details']); $mysql_counter++) {
                    $human_string .= $message['details'][$mysql_counter]['query'] . PHP_EOL;
                    $human_string .= $message['details'][$mysql_counter]['error_str'] . PHP_EOL;
                }
            }

            // if line no is set, use it
            if (isset($message['line_no']) && $message['line_no'] != '') {
                $human_string .= $message['error_name'] . ", line " . $message['line_no'] . ': ';
            }

            // check what type of message we have
            // if set and is not an array or object, clean it and add it
            if (isset($message['details']) && !(is_array($message['details']) || is_object($message['details']))) {
                $human_string .= $this->tidy->remove_text($message['details']) . PHP_EOL;
            }

            // if the error is an array or object, format accordingly using print_r
            if ('MySQL' != $message['error_name'] && isset($message['details']) && (is_array($message['details']) || is_object($message['details']))) {
                $human_string .= print_r($message['details'], true);
                $human_string .= PHP_EOL;
            }
            
            // file details on next line
            if (isset($message['file']) && '' != $message['file']) {
                // $human_string .= "from file: " . str_replace(ABSPATH, '', $message['file']) . PHP_EOL . PHP_EOL;
                $human_string .= "from file: " . $this->tidy->remove_text($message['file']) . PHP_EOL . PHP_EOL;
            }
            \file_put_contents(ABSPATH . '/wp-content/log.txt', $human_string, FILE_APPEND);

            // trim data older than $max_records
            
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }

}