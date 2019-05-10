<?php
declare(strict_types=1);

defined('ABSPATH') or die('No script kiddies please!');

// OK you've found the secret code.
//
// I use writelog() when debugging and it slots right into the existing messaging system
// It can be used to log out pretty much anything you want, anywhere you want, such as 
//
// writelog('testing - code runs to here...');
// writelog(45);
// writelog($myVar);
// writelog(array("name"=> "bob", "age"=>38));
//
// These will pop up in the logger complete with file/line numbers
// Super-handy for debugging but with one problem - if you delete this plugin
// (which contains the function) and if you leave writelog() statements
// in your code then you will get a fatal error:
//
// PHP Fatal error:  Uncaught Error: Call to undefined function /path/to/your/code/writelog()
//
// p.s it's not written in OOP as it's designed as a global function

if (!function_exists('writelog')) {

    /**
     * sends string or variable to logger for output.
     * message can be any primitive, array or object
     * 
     * @param mixed $note
     * @return void
     */
    function writelog($note):void
    {
        $writelog = new \Chipbug\Tools\Logger\Write_Log();
        $writelog->init(new \Chipbug\Tools\Logger\Manage_Logs());
        $writelog->write($note);
    }
}
