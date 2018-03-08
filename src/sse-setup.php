<?php
namespace Chipbug\Tools\Logger;

/**
 * called by sse_connect.js from the client
 */

//require(__dir__ . '/../vendor/autoload.php');
require('./class-sse-log.php');

$sse_log = new \Chipbug\Tools\Logger\Sse_Log();
$sse_log->init();