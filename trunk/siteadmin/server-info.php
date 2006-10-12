<?php include('cm-includes/config.php'); ?>
<?php
$module = "server-info";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>
<h2>Session Variables Set by Server</h2>
<?php
/*

cm_user_id
cm_user_fullname


*/
	echo "<pre>";
	echo "cm_user_id:\t\t" . $_SESSION['cm_user_id'] . "\n";
	echo "cm_user_fullname:\t" . $_SESSION['cm_user_fullname'] . "\n";
	echo "</pre>"
?>
<h2>Cookies Set by Server</h2>
<?php
/*

article-browse-issue
article-browse-section
issue-browse-volume
advertising-browse-issue


*/
	echo "<pre>";
	echo "article-browse-issue:\t\t" . $_COOKIE['article-browse-issue'] . "\n";
	echo "article-browse-section:\t\t" . $_COOKIE['article-browse-section'] . "\n";
	echo "issue-browse-volume:\t\t" . $_COOKIE['issue-browse-volume'] . "\n";
	echo "advertising-browse-issue:\t" . $_COOKIE['advertising-browse-issue'] . "\n";
	echo "submitted-browse-issue:\t\t" . $_COOKIE['submitted-browse-issue'] . "\n";
	echo "</pre>"
?>
<h2>Persisting Variables</h2>
<?php
/*

current_issue_id
current_issue_date
next_issue_id
next_issue_date

*/
	echo "<pre>";
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
	echo "$show_poll_browse\tshow_poll_browse\n";
	echo "$show_poll_edit\tshow_poll_edit\n";
	echo "$show_advertising_browse\tshow_advertising_browse\n";
	echo "$show_advertising_edit\tshow_advertising_edit\n";
	echo "$show_advertising_clients\tshow_advertising_clients\n";
	echo "$show_advertising_rates\tshow_advertising_rates\n";
	echo "</pre>"
?>
<h2>Your Access Rights</h2>
<?php
	$user_id = $_SESSION['cm_user_id'];
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT * FROM cm_access";
	$query_CM_Array .= " WHERE user_id = '$user_id'";
	$query_CM_Array .= " ORDER BY access_option ASC;";
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	echo "<pre>";
	do {
		$type = $row_CM_Array['access_type'];
		$option = $row_CM_Array['access_option'];
		$value = $row_CM_Array['access_value'];
		echo "$type\t$value\t$option\n";
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
	echo "</pre>"
?>


<h2>Server Information</h2>
<?php phpinfo(); ?>
<?php get_cm_footer(); ?>