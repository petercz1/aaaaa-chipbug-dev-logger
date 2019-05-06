<?php
declare(strict_types=1);

defined('ABSPATH') or die('No script kiddies please!');

// not written in OOP as 'writelog' is designed as a global function

if (!function_exists('writelog')) {

    /**
     * sends string or variable to logger for output.
     * message can be any primitive, array or object
     * This function exists in the global namespace
     * so it can be called anywhere like so:
     * writelog('testing - code runs to here...');
     * writelog(45);
     * writelog(array("name"=> "bob", "age"=>38));
     * @param mixed $note
     * @return void
     */
    function writelog($note)
    {
        $writelog = new \Chipbug\Tools\Logger\Write_Log();
        $writelog->init(new \Chipbug\Tools\Logger\Manage_Logs());
        $writelog->write($note);
    }
}
