<?php

/**
 * cURL Library
 *
 * PHP Version 5.3
 *
 * @category  Library
 * @package   BCA/CURL
 * @author    Brodkin CyberArts <support@brodkinca.com>
 * @copyright 2012 Brodkin CyberArts.
 * @license   GPL-3.0 http://www.gnu.org/licenses/gpl.txt
 * @version   GIT: $Id$
 * @link      https://github.com/brodkinca/BCA-PHP-CURL
 */

// Load Dependencies
require_once 'vendor/autoload.php';

// Set URL to Server for Use in Tests
define('REMOTE_TEST_SERVER', 'http://'.WEB_SERVER_HOST.':'.WEB_SERVER_PORT.'/');
define('REMOTE_TEST_SERVER_SSL', 'https://'.WEB_SERVER_HOST.':'.WEB_SERVER_PORT_SSL.'/');
define('SSL_CERT_PATH', __DIR__.'/remote/server.pem');

/**
 * Start PHP's Built-in Web Server
 */

echo PHP_EOL;

// Command that starts the built-in web server
$command_server = sprintf(
    PHP_PATH.' -S %s:%d -t %s >/dev/null 2>&1 & echo $!',
    WEB_SERVER_HOST,
    WEB_SERVER_PORT,
    __DIR__.'/remote'
);

// Execute the command and store the process ID
echo 'Starting PHP server...'.PHP_EOL;
$output = array();
exec($command_server, $output);

if (!isset($output[0])) {
    die('Failed to start php server. Aborting.'.PHP_EOL);
}

$pid_server = (int) $output[0];

echo sprintf(
    'Web server started on %s:%d with PID %d',
    WEB_SERVER_HOST,
    WEB_SERVER_PORT,
    $pid_server
).PHP_EOL.PHP_EOL;

/**
 * Start Stunnel
 */

// Generate configuration file

$config_stunnel = sprintf(
    'debug = debug'.PHP_EOL
    .'foreground = yes'.PHP_EOL
    .'pid ='.PHP_EOL
    .'[php]'.PHP_EOL
    .'accept = %d'.PHP_EOL
    .'connect = %s:%d'.PHP_EOL
    .'cert = %s',
    WEB_SERVER_PORT_SSL,
    WEB_SERVER_HOST,
    WEB_SERVER_PORT,
    SSL_CERT_PATH
);

$temp = tmpfile();
fwrite($temp, $config_stunnel);
$config_stunnel_meta = stream_get_meta_data($temp);

if (!isset($config_stunnel_meta['uri'])) {
    die('Failed to write stunnel configuration file. Aborting.'.PHP_EOL);
}

echo 'Starting SSL Tunnel...'.PHP_EOL;

// Command that starts the built-in web server
$command_stunnel = sprintf(
    '%s %s  >/dev/null 2>&1 & echo $!',
    STUNNEL_PATH,
    $config_stunnel_meta['uri']
);

echo sprintf(
    'stunnel configuration written to %s',
    $config_stunnel_meta['uri']
).PHP_EOL;

// Execute the command and store the process ID
$output = array();
exec($command_stunnel, $output);

if (!isset($output[0])) {
    //die('Failed to start stunnel. Aborting.'.PHP_EOL);
}

$pid_stunnel = (int) $output[0];

echo sprintf(
    'SSL tunnel started on port %d with PID %d',
    WEB_SERVER_PORT_SSL,
    $pid_stunnel
).PHP_EOL.PHP_EOL;

// Kill the web server when the process ends
register_shutdown_function(function () use ($pid_server, $pid_stunnel) {
    echo sprintf(
        'Killing php process with ID %d',
        $pid_server
    ).PHP_EOL;
    exec('kill '.$pid_server.' >/dev/null 2>&1');

    echo sprintf(
        'Killing stunnel process with ID %d',
        $pid_stunnel
    ).PHP_EOL;
    exec('kill '.$pid_stunnel.' >/dev/null 2>&1');
});

sleep(1);
