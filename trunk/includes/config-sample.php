<?php
/* Quick and dirty configuration file for the public site */
define("DB_HOSTNAME", "yourhost");
define("DB_DATABASE", "yourdb");
define("DB_USERNAME", "youruser");
define("DB_PASSWORD", "yourpass");
define("TEMPLATES_FOLDER", "default"); // Switch to 'local' for custom templates
define("SITE_URL", "http://www.example.com/pacercms"); // No trailing slash!


/* Debug and Cache Settings */
define("DEBUG_MODE", false);
define("USE_TEMPLATE_CACHE", false);
define("COMPILE_CHECK", true);


/* Below this line should work without customization */
$cm_path = substr(dirname(__FILE__),0,-9); // removes '/includes/'
define("SITE_ROOT", $cm_path);
define("SMARTY_DIR", $cm_path . "/includes/smarty/");
define("TEMPLATES_PATH", $cm_path . "/templates/" . TEMPLATES_FOLDER);
define("TEMPLATES_C_PATH", $cm_path . "/cache/");
define("CACHE_PATH", $cm_path . "/cache/");
define("CONFIGS_PATH", SMARTY_DIR . "configs/");
