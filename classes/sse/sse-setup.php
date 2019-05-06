<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

/**
 * called by sse_connect.js from the client
 */

error_log('sse-setup called');
require('./sse-log.php');

$sse_log = new \Chipbug\Tools\Logger\Sse_Log();
$sse_log->init();