<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

$issue = current_issue('id');

// Switch sections
if (is_numeric($_GET['id'])) {
	// Grab selected section
	$section = $_GET['id'];
    $smarty->assign("section_name", section_info('name', $_GET['id']) );
	$feed_wc .= " WHERE section_id = '$section' AND issue_id = '$issue' ";
} elseif ($_GET['show'] == 'all') {
	// Show all sections
	$feed_wc .= " WHERE issue_id = '$issue' ";
} else {
	// Default to cover section
	$feed_wc .= " WHERE section_id = '1' AND issue_id = '$issue' ";
};

/*=======================
    Feed Articles
=======================*/
$query = "SELECT a.id, a.article_title, a.article_summary, a.article_author, a.article_publish, s.section_name ";
$query .= " FROM cm_articles a INNER JOIN cm_sections s ON a.section_id = s.id ";	
$query .= $feed_wc;
$query .= " ORDER BY s.section_priority, a.article_priority ASC;";

// Run Query
$result = $db->Execute($query);while ($array = $result->GetArray()) {    $feed_items = $array;}

// Assign variables
$smarty->assign("feed_items", $feed_items);

// Add headers for XML
header("Content-type: text/xml; charset=UTF-8");

// Render
$smarty->display("feed.tpl");