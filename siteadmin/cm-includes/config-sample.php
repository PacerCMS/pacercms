<?php
/*

This file is optional if you intend to leave your
'siteadmin' folder in the same directory as the public
files. If you need to detatch the two for security
reasons, just copy this file as 'config.php' and Site
Administrator will detect this file and use it
instead. Make sure your configurations match.

You will also need to copy the database extraction class
(adodb_lite)

From your root folder:
$ cp ./includes/adodb_lite ./siteadmin/cm-includes/

*/

define("DB_HOSTNAME", "localhost");
define("DB_DATABASE", "pacercms");
define("DB_USERNAME", "your_username");
define("DB_PASSWORD", "your_password");

/* Below this line should work without customization */
$cm_path = substr(dirname(__FILE__),0,-12); // removes '/cm-includes/'
define("SITE_ROOT", $cm_path);
define("ADODB_DIR", $cm_path . "/includes/adodb_lite/");
