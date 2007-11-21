<?php

// The Version of PacerCMS You Are Running
define('CM_VERSION', "0.6.1 PR1");
define('DB_VERSION', "65");

// Make sure the config file exists
if ( file_exists('includes/config.php') )
{
    include_once('config.php'); // Define site

    // If constant is undefined
    if (DB_PLATFORM == 'DB_PLATFORM') { define('DB_PLATFORM', 'mysql'); }

    // Database Connection
    require( ADODB_DIR . '/adodb.inc.php');
    $db = ADONewConnection(DB_PLATFORM);
    $db->Connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    include_once('functions.php'); // Primary functions
    include_once('classes.php'); // For other functions       
    
} else {
    // Redirect to the installer
    header("Location: ./INSTALL/cm-config.php ");
    exit;
}
