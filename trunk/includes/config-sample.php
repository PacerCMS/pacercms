<?php
/* Database and Template Settings */
define("DB_PLATFORM", "mysql");
define("DB_CHARSET", 'utf8');
define("DB_HOSTNAME", "localhost");
define("DB_DATABASE", "pacercms");
define("DB_USERNAME", "username");
define("DB_PASSWORD", "password");
define("TEMPLATES_FOLDER", "default");


/* Debug and Cache Settings */
define("DEBUG_MODE", false);
define("USE_TEMPLATE_CACHE", false);
define("COMPILE_CHECK", true);


/* International Translations */
define("LOCALE", "en_US.UTF8");


/* Use a custom URL structure (Advanced and generally unsupported) */
// Note: Requires familiarity with mod_rewrite or similar configuration
// %article_id% -- ID of the article
// %article_date% -- Date of article publication
// %article_slug% -- Sanitized version of the article title
define("REWRITE_RULE", "/article.php?id=%article_id%");


/* Below this line should work without customization */
$cm_path = substr(dirname(__FILE__),0,-9); // removes '/includes/'
define("SITE_ROOT", $cm_path);
define("ADODB_DIR", $cm_path . "/includes/adodb_lite/");
define("SMARTY_DIR", $cm_path . "/includes/Smarty/");
define("TEMPLATES_PATH", $cm_path . "/templates/" . TEMPLATES_FOLDER);
define("TEMPLATES_C_PATH", $cm_path . "/cache/");
define("CACHE_PATH", $cm_path . "/cache/");
define("CONFIGS_PATH", SMARTY_DIR . "/configs/");
