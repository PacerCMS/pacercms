<?php

// The Version of PacerCMS You Are Running
define('CM_VERSION', "0.5 Alpha");

// Make sure the config file exists
if ( file_exists('includes/config.php') )
{
    include_once('includes/config.php'); // Define site
    include_once('classes.php'); // For other functions
    include_once('functions.php'); // Primary functions

} else {
    echo "You must first load a config.php into your <tt>./includes/</tt> folder. More information appears in the <tt>./INSTALL/</tt> folder.";
    exit;
}