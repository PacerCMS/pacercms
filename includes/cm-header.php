<?php

// The Version of PacerCMS You Are Running
define('CM_VERSION', "0.7 Alpha");
define('DB_VERSION', "65");

// Make sure the config file exists
if ( file_exists('includes/config.php') )
{
    include_once('config.php'); // Define site
    include_once('classes.php'); // For other functions
    include_once('functions.php'); // Primary functions
} else {
    // Redirect to the installer
    header("Location: ./INSTALL/cm-config.php ");
    exit;
}
