<?php

// The Version of PacerCMS You Are Running
define('CM_VERSION', "0.6.1 Alpha");

if ( file_exists('../includes/config.php') ) { $config_file = "../includes/config.php"; }
if ( file_exists('cm-includes/config.php') ) { $config_file = "cm-includes/config.php"; }

if (!empty($config_file))
{
    include_once($config_file); // Use chosen config file
    include_once('classes.php'); // For other functions
    include_once('functions.php'); // Primary functions
} else {
    header("Location: ../INSTALL/cm-config.php ");
    exit;
}
