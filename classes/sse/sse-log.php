<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

/**
 * sends log.json to client browser
 * fires every 500ms or user-set interval
 */
class Sse_Log
{
    private $data ="{}";

    /**
     * opens and sends log.json.
     * retries every 500ms ie half a second (default - change in settings)
     * deletes log.json after reading
     * @return void
     */
    public function init()
    {
        try {
            // required to use WP get_option() function
            // $path = preg_replace('/wp-content.*$/','',__DIR__);
            // include($path.'wp-load.php');

            $options = \file_get_contents( __DIR__ . '/../install/serialized_options.json');
            $options = json_decode($options, true);
            $refresh_rate = $options['refresh_rate'];

            if (\file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-content/log.json')) {
                $this->data = \file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/wp-content/log.json');
                unlink($_SERVER['DOCUMENT_ROOT'] . '/wp-content/log.json');
            }
            header("Cache-Control: no-cache");
            header("Content-Type: text/event-stream");
            echo "retry: " . $refresh_rate . PHP_EOL;
            echo "event: db_results" . PHP_EOL;
            echo "data: $this->data" . PHP_EOL;
            echo PHP_EOL;
            flush();
        } catch (Exception $ex) {
            error_log(get_class($this) . '::' . __FUNCTION__ . '()' . PHP_EOL . 'line ' . $ex->getLine() . ': ' . $ex->getMessage());
        }
    }
}