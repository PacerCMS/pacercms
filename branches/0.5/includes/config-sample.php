<?php
/* Database and Template Settings */
define("DB_HOSTNAME", "localhost");
define("DB_DATABASE", "pacercms");
define("DB_USERNAME", "username");
define("DB_PASSWORD", "password");
define("TEMPLATES_FOLDER", "default");


/* Debug and Cache Settings */
define("DEBUG_MODE", false);
define("USE_TEMPLATE_CACHE", false);
define("COMPILE_CHECK", true);


/* Below this line should work without customization */
$cm_path = substr(dirname(__FILE__),0,-9); // removes '/includes/'
define("SITE_ROOT", $cm_path);
define("ADODB_DIR", $cm_path . "/includes/adodb_lite/");
define("SMARTY_DIR", $cm_path . "/includes/Smarty/");
define("TEMPLATES_PATH", $cm_path . "/templates/" . TEMPLATES_FOLDER);
define("TEMPLATES_C_PATH", $cm_path . "/cache/");
define("CACHE_PATH", $cm_path . "/cache/");
define("CONFIGS_PATH", SMARTY_DIR . "/configs/");
