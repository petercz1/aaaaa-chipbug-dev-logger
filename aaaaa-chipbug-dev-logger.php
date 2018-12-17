<?php
namespace Chipbug\Tools\Logger;

/*
Plugin name: WP Logger
Description: Logs all PHP errors and exceptions & allows simple error logging using writelog('your message').
Author: Peter Carroll
Author URI: https://chipbug.com
Version: 2.0.0
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

defined('ABSPATH') or die('No script kiddies please!');
require_once(__dir__ . '/vendor/autoload.php');

register_activation_hook(__FILE__, array('Chipbug\Tools\Logger\Activate_Logger', 'activate'));
register_deactivation_hook(__FILE__, array('Chipbug\Tools\Logger\Deactivate_Logger', 'deactivate'));
register_uninstall_hook(__FILE__, array('Chipbug\Tools\Logger\Uninstall_Logger', 'uninstall'));

$setup = new Setup();
$setup->init();

require_once(__dir__ . '/src/function-writelog.php');

// TODO - fix weird looping bug
// TODO - testing
// TODO - animated gif to demo