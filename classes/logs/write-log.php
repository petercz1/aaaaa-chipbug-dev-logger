<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

/**
 * sends log.json to client browser
 * fires every 500ms
 */
class Write_Log
{
    public $manage_logs;

    public function init(Manage_Logs $manage_logs)
    {
        $this->manage_logs = $manage_logs;
        $this->manage_logs->init(new \Chipbug\Tools\Logger\Write_Json(), new \Chipbug\Tools\Logger\Write_Human());

    }

    public function write($note)
    {
        try {
            //$manage_logs = new \Chipbug\Tools\Logger\Manage_Logs;
            $file_name = '';
            $line_no = '';
            $backtrace = debug_backtrace();
            if (!empty($backtrace[1]) && is_array($backtrace[1])) {
                $file_name = $backtrace[1]['file'];
                $line_no = $backtrace[1]['line'];
            }

            $message = array('error_name'=> 'developer', 'line_no'=>$line_no, 'file'=>$file_name);

            // check what type of error/message we have
            if (is_array($note) || is_object($note)) {
                // if the error is an array or object, format accordingly using print_r
                $message['details'] = print_r($note, true);
            } else {
                // ... otherwise just log it as-is
                $message['details'] = $note;
            }
            // send to writer
            $this->manage_logs->write($message);
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
}
