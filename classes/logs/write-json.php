<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * writes to json file
 */
class Write_Json
{
    public $tidy;

    public function init(Tidy_Json_Text $tidy)
    {        
        $this->tidy = $tidy;
        $check_log_length = new \Chipbug\Tools\Logger\Check_Log_length();
        $check_log_length->init();
    }
    
    /**
     * write to wp_content/log.json.
     * turns $message array into json format
     * @param array $message
     * @return void
     */
    public function write_json(array $message)
    {
        // JSON-READABLE: add record to wp-content/log.json - sent to browser
        // color code message details, remove 'confusing' text
        if (isset($message['details']) && !is_array($message['details'])) {
            $message['details'] = $this->tidy->clean($message['details']);
            //$message['details'] = $this->tidy->remove_text($message['details']);
        }
        if (isset($message['file'])) {
            // $message['file'] = str_replace(ABSPATH, '', $message['file']);
            $message['file'] = $this->tidy->clean($message['file']);
        }
        try {
            if (!\file_exists(ABSPATH . '/wp-content/log.json')) {
                \file_put_contents(ABSPATH . '/wp-content/log.json', '[]');
            }
            $json_data = json_decode(\file_get_contents(ABSPATH . '/wp-content/log.json'));
            // prepend data
            if (isset($json_data)) {
                array_unshift($json_data, $message);
                // shove it back
                \file_put_contents(ABSPATH . '/wp-content/log.json', \json_encode($json_data));
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
}
