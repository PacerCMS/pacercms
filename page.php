<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

/* If id is numeric, use it; else 1 */
if (is_numeric($_GET['id']))
{
	$page_id = $_GET['id'];
} else {
	$page_id = 1; // Default
}

// Query
$query = "SELECT * FROM cm_pages ";
$query .= " WHERE id = $page_id ";
$query .= " LIMIT 0,1; ";

// Run Query
$result = run_query($query);

$page_title = $result->Fields(page_title);
$page_short_title = $result->Fields(page_short_title);
$page_text = autop($result->Fields(page_text));
$page_side_text = autop($result->Fields(page_side_text));

// Define variables
$smarty->assign("page_title", $page_title );
$smarty->assign("page_short_title", $page_short_title );
$smarty->assign("page_text", $page_text );
$smarty->assign("page_side_text", $page_side_text );

$smarty->display ("page.tpl");
