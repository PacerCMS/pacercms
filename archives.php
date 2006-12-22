<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

$current_volume = current_issue('volume'); 

$current_issue_date = current_issue('date');
$current_issue_id = current_issue('id');
$next_issue_date = next_issue('date');
$next_issue_id = next_issue('id');

/*=======================
    Switch Cookies
=======================*/

// Switch volumes
if (is_numeric($_GET['volume'])) {;
	$volume = $_GET['volume'];	
	setcookie("archive-volume", $volume);
	header("Location: " . site_info('url') . "/archives.php");
	exit;
}
if ($_COOKIE["archive-volume"] == "") {;
	setcookie("archive-volume", $current_volume); // Current Issue
	$volume = $current_volume;
} else {;
	$volume = $_COOKIE["archive-volume"];
};


// Switch issues
if (isset($_GET['issue'])) {;
	$issue = $_GET['issue'];	
	setcookie("archive-issue", $issue);
	header("Location: " . site_info('url') . "/archives.php");
	exit;
}
if ($_COOKIE["archive-issue"] == "") {;
	setcookie("archive-issue", $current_volume); // Current Issue
	$issue = $current_issue_date;
} else {;
	$issue = $_COOKIE["archive-issue"];
};


/*=======================
    Volume List
=======================*/
$query = "SELECT DISTINCT(issue_volume) AS volume, COUNT(id) AS issue_count";	
$query .= " FROM cm_issues ";
$query .= " WHERE issue_date < '$next_issue_date' ";
$query .= " GROUP BY issue_volume";
$query .= " ORDER BY issue_volume DESC;";

// Run Query
$result = $db->Execute($query);while ($array = $result->GetArray()) {    $volume_list = $array;}

// Assign variables
$smarty->assign("volume_list", $volume_list);


/*=======================
    Issue List
=======================*/
$query = "SELECT *";
$query .= " FROM cm_issues";
$query .= " WHERE issue_volume = '$volume'";
$query .= " AND issue_date < '$next_issue_date'";
$query .= " ORDER BY issue_date ASC;";

// Run query
$result = $db->Execute($query);while ($array = $result->GetArray()) {    $issue_list = $array;}

// Assign variables
$smarty->assign("issue_list", $issue_list);


/*=======================
    Article List
=======================*/
$query = "SELECT cm_articles.id AS article_id, ";
$query .= " article_title, article_summary, article_author, article_word_count, section_name, issue_volume, issue_number ";
$query .= " FROM cm_articles INNER JOIN (cm_sections, cm_issues) ";
$query .= " ON (cm_sections.id = cm_articles.section_id AND cm_issues.id = cm_articles.issue_id)";
$query .= " WHERE issue_date = '$issue' AND issue_date < '$next_issue_date' ";
$query .= " ORDER BY section_priority ASC, article_priority ASC;";

// Run query
$result = $db->Execute($query);

// Error if empty
if (empty($result))
{
    cm_error("Could not find selected issue");
    exit;
}

// Get results
$issue_volume = $result->Fields(issue_volume);
$issue_number = $result->Fields(issue_number);

// Assign variables
$smarty->assign("issue_volume", $issue_volume );
$smarty->assign("issue_number", $issue_number );
while ($array = $result->GetArray()) {    $article_list = $array;}

// Assign variables
$smarty->assign("article_list", $article_list);

$smarty->assign("page_title", "Archives");
$smarty->assign("section_name", "Archives");

// Render
$smarty->display("archives.tpl");
