<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "server-info";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

get_cm_header();

?>
<h2><?php echo gettext("Session Variables Set by Server"); ?></h2>
<p class="infoMessage"><?php echo gettext("These are set when you login. Updates do not occur until you login again."); ?></p>
<?php

	print "<pre>\n";
	print "<strong>" . gettext("User Data:") . "</strong> ";
    print_r($_SESSION['user_data']);
	print "<strong>" . gettext("Access Data:") . "</strong> ";
    print_r($_SESSION['access_data']);
    print "<strong>" . gettext("Setting Data:") . "</strong> ";
    print_r($_SESSION['setting_data']);
    print "<strong>" . gettext("Issue Data:") . "</strong> ";
    print_r($_SESSION['issue_data']);
	print "</pre>\n"
?>
<h2><?php echo gettext("Cookies Set by Server"); ?></h2>
<p class="infoMessage"><?php echo gettext("Modules set cookies to keep everything easy to get to."); ?></p>
<?php

	echo "<pre>\n";
	echo "article-browse-issue:\t\t" . $_COOKIE['article-browse-issue'] . "\n";
	echo "article-browse-section:\t\t" . $_COOKIE['article-browse-section'] . "\n";
	echo "issue-browse-volume:\t\t" . $_COOKIE['issue-browse-volume'] . "\n";
	echo "submitted-browse-issue:\t\t" . $_COOKIE['submitted-browse-issue'] . "\n";
	echo "</pre>\n"

?>
<h2><?php echo gettext("Server Information"); ?></h2>
<p class="infoMessage"><?php echo gettext("A phpinfo() instance. Might be helpful in debugging server errors."); ?></p>
<?php phpinfo(); ?>
<?php get_cm_footer(); ?>