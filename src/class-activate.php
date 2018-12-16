<?php
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * install
 * sets up options, checks if writelog() function exists
 */
class Activate_Logger
{
    public static $message = '';
    public static $problems = false;
    /**
     * installs default options
     * Checks for non-MS browser, php > 5.3, WP > 4.0, non-use of writelog() function
     * @return void
     */
    public static function activate()
    {
        $php = '5.3';
        $wp_req = '4.0.0';

        if (version_compare(PHP_VERSION, $php, '<')) {
            self::$problems = true;
            deactivate_plugins(plugin_basename(__FILE__));
            self::$message .= '<h2>Your version of PHP is too old for this plugin</h2>';
            self::$message .= '<p>This <strong>chipbug-dev-logger</strong> plugin requires PHP version '.$php.' or greater.</p>';
            self::$message .= '<p>Your server is currently running PHP version ' . PHP_VERSION . '</p>';
            self::$message .= '<p>Consider <a href="https://wordpress.org/support/upgrade-php/">upgrading your installation of PHP</a>.';
            $args = array('back_link'=>true);
        }

        global $wp_version;
        if (version_compare($wp_version, $wp_req, '<')) {
            self::$problems = true;
            self::$message .= '<h2>Your version of WordPress is too old for this plugin</h2>';
            self::$message .= '<p>This <strong>chipbug-dev-logger</strong> plugin requires WordPress version '.$wp_req.' or greater.</p>';
            self::$message .= '<p>Your server is currently running WordPress version ' . $wp_version . '</p>';
            self::$message .= '<p>Consider <a href="https://codex.wordpress.org/Upgrading_WordPress">updating your WordPress installation</a></p>';
        }

        if (function_exists('writelog')) {
            $reflectionFunc = new \ReflectionFunction('writelog');
            $filename = $reflectionFunc->getFileName();
            if(strpos($filename, 'aaaaa-chipbug-dev-logger/src/function-writelog.php') == false){
                self::$problems = true;
                $filename = str_replace(ABSPATH, '', $filename);
                self::$message .= '<h2>Function writelog() is already in use</h2>';
                self::$message .= '<p>This <strong>chipbug-dev-logger</strong> plugin uses a function called writelog()</p>';
                self::$message .= '<p>writelog() is already used in ' . $filename . ' on line ' . $reflectionFunc->getStartLine() . '</p>';
                self::$message .= '<p>If this location is in a plugin, consider disabling that plugin.</p>';
            }
        }

        $browser = new \Chipbug\Tools\Logger\Check_Browser();
        $browser_info = $browser->get_browser_info();
        if($browser_info['abbreviation'] == 'MSIE' || $browser_info['abbreviation'] == 'EDGE'){
            self::$problems = true;
            self::$message .= '<h2>Microsoft browsers do not currently support SSE</h2>';         
            self::$message = '<p>This <strong>chipbug-dev-logger</strong> plugin uses ';
            self::$message .= '<a href="https://en.wikipedia.org/wiki/Server-sent_events">Server-Sent Events.</a></p>';
            self::$message .= '<p>At this point MicroSoft browsers do not support SSE\'s - please use Chrome/Firefox/Safari/Opera instead</p>';
            self::$message .= '<p>(developers - I am aware of ';
            self::$message .= '<a href="https://github.com/remy/polyfills/blob/master/EventSource.js">this polyfill</a>, ';
            self::$message .= 'but at this stage I\'m not implementing it)';
        }

        if(self::$problems){
            self::install_problems();
        }

        $options = array(
            'refresh_rate'=>500,
            "include_trace" => 'false',
            "size_of_log" => 100,
            "include_file_path" => 'false',
            "file_path" =>  get_home_path()
        );
        file_put_contents( __DIR__ . '/serialized_options.txt', serialize( $options ) );
    }

    public static function install_problems(){
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(self::$message, 'Plugin Activation Error', array('back_link'=>true));
    }
}
