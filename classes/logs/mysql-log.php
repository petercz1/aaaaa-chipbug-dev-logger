<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * handles writing of message array to both json and human files
 */
class Mysql_Log
{
    /**
     * @var array $message contains default message.
     */
    public $message = array();
    // public $message = array('error_name'=> 'MySQL', 'line_no'=>'', 'file'=>'', 'details'=>'test details');

    // public function init(Manage_Logs $manage_logs)
    // {
    //     add_action('shutdown', array($this, 'db_log_log'));
    // }
    
    /**
     * returns contents of $EZSQL_ERROR
     *
     * @return array
     */
    public function log()
    {
        /**
         * Obscure WP error array for MySQL
         * This stores the last MySQL error message text
         * and the query that generated it.
         * It doesn NOT store the last 'successful' query!
         * @var array $EZSQL_ERROR
         */
        global $EZSQL_ERROR;
        try {
            //proceed if there were MySQL errors during runtime
            if (is_array($EZSQL_ERROR) && count($EZSQL_ERROR)) {
                $this->message['error_name'] = 'MySQL';
                $this->message['details'] =  $EZSQL_ERROR;
                return $this->message;
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
}
