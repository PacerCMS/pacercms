<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

// Switch sections
if (is_numeric($_GET['id'])) {
	$section = $_GET['id'];
} else {
	$section = 1; // Default
};

$issue = current_issue('id');


/*=======================
    Feed Articles
=======================*/
$query = "SELECT * ";
$query .= " FROM cm_articles ";	
$query .= " WHERE section_id = '$section' AND issue_id = '$issue' ";
$query .= " ORDER BY article_priority ASC;";

// Run Query
$result = $db->Execute($query);while ($array = $result->GetArray()) {    $feed_items = $array;}

// Assign variables
$smarty->assign("section_name", section_info('name', $section) );
$smarty->assign("feed_items", $feed_items);


// Render
$smarty->display("feed.tpl");