<?php

// The Version of PacerCMS You Are Running
define('CM_VERSION', "0.7");
define('DB_VERSION', "65");

unset($config_file);

if ( file_exists('../includes/config.php') ) { $config_file = "../includes/config.php"; }
if ( file_exists('cm-includes/config.php') ) { $config_file = "cm-includes/config.php"; }

if (!empty($config_file))
{
    // Determine which configuration file to  use
    include_once($config_file);

    // Starting sessions
    session_start();

    // If platform constant is undefined, assume MySQL
    if (!defined('DB_PLATFORM')) { define('DB_PLATFORM', 'mysql'); }

    // Database Connection
    require( ADODB_DIR . '/adodb.inc.php');
    $cm_db = ADONewConnection(DB_PLATFORM);
    $cm_db->Connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    include_once('functions.php');
    include_once('classes.php');

    // If locale constant is undefined, assume en_US.UTF8
    if (!defined('LOCALE')) { define('LOCALE', 'en_US.UTF8'); }

    include_once('cm-l10n.php');

} else {
    header('Location: ../../INSTALL/cm-config.php');
    exit;
}
