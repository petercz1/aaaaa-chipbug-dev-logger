<?php
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * handles writing of message array to both json and human files
 */
class Manage_Logs
{
    public $write_json;
    public $write_human;
    public $check_log_length;
    /**
     * @var array $message contains default message.
     */
    //public $message = array('error_name'=> 'test error name', 'line_no'=>'test line no', 'file'=>'test file', 'details'=>'test details');
    
    public function init(Write_Json $write_json, Write_Human $write_human)
    {
        $this->write_json = $write_json;
        $this->write_json->init(new \Chipbug\Tools\Logger\Tidy_Json_Text());
        $this->write_human = $write_human;
        $this->write_human->init(new \Chipbug\Tools\Logger\Tidy_Text());
    }

    /**
     * writes message array to json and human-readable file.
     * @param array $message
     */
    public function write(array $message)
    {
        // if (isset($message) && is_array($message)) {
        //     $this->message = $message;
        // }
        $this->write_json->write_json($message);
        $this->write_human->write_human($message);
    }
}
