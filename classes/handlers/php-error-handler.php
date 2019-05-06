<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * replaces standard php error handlers
 */
class Php_Error_Handler
{
    public $manage_logs;
    public $mysql_log;
    /**
     * sets all error handlers
     *
     * @return void
     */
    public function init(Manage_Logs $manage_logs, Mysql_Log $mysql_log)
    {
        try {
            $this->manage_logs = $manage_logs;
            $this->manage_logs->init(new \Chipbug\Tools\Logger\Write_Json(), new \Chipbug\Tools\Logger\Write_Human());
            $this->mysql_log = $mysql_log;
            set_error_handler(array($this,'error_handler')); // handles errors within script
            register_shutdown_function(array($this, "shutdown_handler")); //executes if script shuts down
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }

    /**
     * overrides php standard runtime error handler
     * @return void
     */
    public function error_handler($errorno, $errstr, $errfile, $errline)
    {
        try {
            // fix to stop simplepie 'bug' as reported here: https://core.trac.wordpress.org/ticket/29204
            if (false !== strpos($errstr, 'Non-static method WP_Feed_Cache::create() should not be called statically')) {
                die;
            }
            $error_name = $this->check_error($errorno);
            // $file_path = str_replace(ABSPATH, '', $errfile);
            $file_path = $errfile;
            $message = array('error_name'=> $error_name, 'line_no'=>$errline, 'file'=>$file_path, 'details'=>$errstr);
            $this->manage_logs->write($message);
            if (\is_array($this->mysql_log->log())) {
                $this->manage_logs->write($this->mysql_log->log());
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
    
    /**
     * traps errors that cause shutdown before code has time to finish
     * also runs mysql_log->db_log_log at the end
     * @return void
     */
    public function shutdown_handler()
    {
        try {
            if (!is_null(error_get_last())) {
                error_log('shutdown_handler');
                $lasterror = error_get_last();
                $error_name = $this->check_error($lasterror['type']);
                // $file_path = str_replace(ABSPATH, '', $lasterror['file']);
                $file_path = $lasterror['file'];
                //$lasterror['message'] = str_replace(ABSPATH , '', $lasterror['message']);
                $message = array('error_name'=> $error_name, 'line_no'=>$lasterror['line'], 'file'=>$file_path, 'details'=>$lasterror['message']);
                $this->manage_logs->write($message);
                if (\is_array($this->mysql_log->log())) {
                    $this->manage_logs->write($this->mysql_log->log());
                }
            }
            if (\is_array($this->mysql_log->log())) {
                $this->manage_logs->write($this->mysql_log->log());
            }
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }

    /**
     * checks php error constant
     * @return string error name or 'UNKNOWN ERROR'
     */
    private function check_error($error_code)
    {
        $errtypes = array(
        E_COMPILE_ERROR => 'compile error',
        E_COMPILE_WARNING => 'compile warning',
        E_CORE_ERROR => 'core error',
        E_CORE_WARNING => 'core warning',
        E_DEPRECATED => 'deprecated',
        E_ERROR => 'error',
        E_NOTICE => 'notice',
        E_PARSE => 'parse',
        E_RECOVERABLE_ERROR => 'recoverable error',
        E_STRICT => 'strict',
        E_USER_DEPRECATED => 'user deprecated',
        E_USER_ERROR => 'user error',
        E_USER_NOTICE => 'user notice',
        E_USER_WARNING => 'user warning',
        E_WARNING => 'warning',
    );
        if (array_key_exists($error_code, $errtypes)) {
            return $errtypes[$error_code];
        } else {
            return 'unknown error';
        }
    }
}
