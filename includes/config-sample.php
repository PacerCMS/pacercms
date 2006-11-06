<?php
// Quick and dirty configuration file for the public site.

define("DB_HOSTNAME", "localhost");
define("DB_DATABASE", "pacercms");
define("DB_USERNAME", "your_username");
define("DB_PASSWORD", "your_password");
define("SITE_TEMPLATE_ROOT", "/home/www/htdocs/pacercms/templates"); // No trailing slash!
define("SITE_URL", "http://localhost/pacercms"); // No trailing slash!

include_once('functions.php'); // Primary functions
include_once('classes.php'); // For other functions

?>