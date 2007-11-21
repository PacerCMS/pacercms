<?php

// The Version of PacerCMS You Are Running
define('CM_VERSION', "0.6.1 PR1");

if ( file_exists('../includes/config.php') ) { $config_file = "../includes/config.php"; }
if ( file_exists('cm-includes/config.php') ) { $config_file = "cm-includes/config.php"; }

if (!empty($config_file))
{
    // Determine which configuration file to  use
    include_once($config_file);

    // Include various classes
    include_once('classes.php');

    // Database connection
    $cm_db = ADONewConnection(DB_DRIVER);
    $cm_db->Connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Primary functions
    include_once('functions.php');

} else {
    header("Location: ../INSTALL/cm-config.php ");
    exit;
}
