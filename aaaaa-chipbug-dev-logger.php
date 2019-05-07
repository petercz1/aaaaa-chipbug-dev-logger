<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

/*
Plugin name: WP Logger
Description: Logs all PHP errors and exceptions & allows simple error logging using writelog('your message').
Author: Peter Carroll
Author URI: https://github.com/petercz1
Version: 3.0.0
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

defined('ABSPATH') or die('No script kiddies please!');
require_once(__dir__ . '/classes/autoloader.php');
(new Autoloader)->init();

register_activation_hook(__FILE__, array('\Chipbug\Tools\Logger\Activate', 'activate'));
register_deactivation_hook(__FILE__, array('\Chipbug\Tools\Logger\Deactivate', 'deactivate'));
register_uninstall_hook(__FILE__, array('\Chipbug\Tools\Logger\Uninstall', 'uninstall'));

$setup = new Setup();
$setup->init();

require_once(__dir__ . '/classes/logs/function-writelog.php');

// TODO - fix weird looping bug
// TODO - testing
// TODO - animated gif to demo
// TODO - log objects as well as arrays
// TODO - make php 5.3+ compatible