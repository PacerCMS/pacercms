<?php

// The Version of PacerCMS You Are Running
define('CM_VERSION', "0.7 PR1");

// Make sure the config file exists
if ( file_exists('includes/config.php') )
{
    // Load configuration file
    include_once('config.php');

    // If platform constant is undefined, assume MySQL
    if (!defined('DB_PLATFORM')) { define('DB_PLATFORM', 'mysql'); }

    // Database Connection
    require( ADODB_DIR . '/adodb.inc.php');
    $db = ADONewConnection(DB_PLATFORM);
    $db->Connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    include_once('functions.php');
    include_once('classes.php');

    // If locale constant is undefined, assume en_US.UTF8
    if (!defined('LOCALE')) { define('LOCALE', 'en_US.UTF8'); }
    
    include_once('l10n.php');

} else {
    // Redirect to the installer
    header("Location: ./INSTALL/cm-config.php ");
    exit;
}
