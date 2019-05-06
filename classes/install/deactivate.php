<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * uninstall
 */
class Deactivate
{
    /**
     * removes option settings from wp_options
     *
     * @return void
     */
    public static function deactivate()
    {
        delete_option('chipbug_logger_options');
    }
}
