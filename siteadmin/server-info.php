<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "server-info";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>
<h2>Session Variables Set by Server</h2>
<p class="infoMessage">These are set when you login. Updates do not occur until you login again.</p>
<?php

	echo "<pre>\n";
	echo "cm_user_id:\t\t" . $_SESSION['cm_user_id'] . "\n";
	echo "cm_user_fullname:\t" . $_SESSION['cm_user_fullname'] . "\n";
	echo "</pre>\n"
?>
<h2>Cookies Set by Server</h2>
<p class="infoMessage">Modules set cookies to keep everything easy to get to.</p>
<?php

	echo "<pre>\n";
	echo "article-browse-issue:\t\t" . $_COOKIE['article-browse-issue'] . "\n";
	echo "article-browse-section:\t\t" . $_COOKIE['article-browse-section'] . "\n";
	echo "issue-browse-volume:\t\t" . $_COOKIE['issue-browse-volume'] . "\n";
	echo "submitted-browse-issue:\t\t" . $_COOKIE['submitted-browse-issue'] . "\n";
	echo "</pre>\n"

?>
<h2>Persisting Variables</h2>
<p class="infoMessage">These are set from the cm_access table entries.</p>
<?php

	echo "<pre>\n";
	echo "current_issue_id:\t$current_issue_id\n";
	echo "current_issue_date:\t$current_issue_date\n";
	echo "next_issue_id:\t\t$next_issue_id\n";
	echo "next_issue_date:\t$next_issue_date\n";
	echo "\n";
	echo "$restrict_issue\trestrict_issue\n";
	echo "$restrict_section\trestrict_section\n";
	echo "\n";
	echo "$show_issue_browse\tshow_issue_browse\n";
	echo "$show_issue_edit\tshow_issue_edit\n";
	echo "$show_page_browse\tshow_page_browse\n";
	echo "$show_page_browse\tshow_page_edit\n";
	echo "$show_profile\tshow_profile\n";
	echo "$show_section_browse\tshow_section_browse\n";
	echo "$show_section_edit\tshow_section_edit\n";
	echo "$show_server_info\tshow_server_info\n";
	echo "$show_settings\tshow_settings\n";
	echo "$show_staff_access\tshow_staff_access\n";
	echo "$show_staff_browse\tshow_staff_browse\n";;
	echo "$show_staff_edit\tshow_staff_edit\n";
	echo "$show_index\tshow_index\n";
	echo "$show_article_media\tshow_article_media\n";
	echo "$show_article_edit\tshow_article_edit\n";
	echo "$show_article_browse\tshow_article_browse\n";
	echo "$show_submitted_browse\tshow_submitted_browse\n";
	echo "$show_submitted_edit\tshow_submitted_edit\n";
	echo "$show_submitted_delete\tshow_submitted_delete\n";
	echo "$show_poll_browse\tshow_poll_browse\n";
	echo "$show_poll_edit\tshow_poll_edit\n";
	echo "</pre>\n"
?>
<h2>Your Access Rights</h2>
<p class="infoMessage">This is the raw read-out of your cm_access entries.</p>
<?php
	$user_id = $_SESSION['cm_user_id'];
	
	$query = "SELECT * FROM cm_access";
	$query .= " WHERE user_id = '$user_id'";
	$query .= " ORDER BY access_option ASC;";
	$result = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
	$result_array  = mysql_fetch_assoc($result);
	$result_row_count = mysql_num_rows($result);
	echo "<pre>";
	do {
		$type = $result_array['access_type'];
		$option = $result_array['access_option'];
		$value = $result_array['access_value'];
		echo "$type\t$value\t$option\n";
	} while ($result_array = mysql_fetch_assoc($result));
	echo "</pre>"
?>


<h2>Server Information</h2>
<p class="infoMessage">A phpinfo() instance. Might be helpful in debugging server errors.</p>
<?php phpinfo(); ?>
<?php get_cm_footer(); ?>