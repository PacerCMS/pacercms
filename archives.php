<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

$current_volume = current_issue('volume'); 
$current_issue_date = current_issue('date');
$current_issue_id = current_issue('id');
$next_issue_date = next_issue('date');
$next_issue_id = next_issue('id');

/*=======================
    Switch Cases
=======================*/
/* If nothing is given, assume current issue date and volume */
if ( empty($_GET['issue']) && empty($_GET['volume']) )
{
    $issue_date = current_issue('date');
    $volume = current_issue('volume');
}
   
/* If issue date given, extract volume number of that issue */
if (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $_GET['issue']))
{
    $issue_date = $_GET['issue'];
    $query = "SELECT issue_volume FROM cm_issues WHERE issue_date = '$issue_date';";
    $result = run_query($query);    
    $volume = $result->Fields(issue_volume);
}


/* If volume is given, extract first issue date in that volume */
if (is_numeric($_GET['volume']))
{
    $volume = $_GET['volume'];
    $query = "SELECT issue_date FROM cm_issues WHERE issue_volume = '$volume';";
    $result = run_query($query);    
    $issue_date = $result->Fields(issue_date);
}


/* If both are given, go with issue date */
if (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $_GET['issue']) && is_numeric($_GET['volume']))
{
    $issue_date = $_GET['issue'];
    $query = "SELECT issue_volume FROM cm_issues WHERE issue_date = '$issue_date';";
    $result = run_query($query);    
    $volume = $result->Fields(issue_volume);
}


/* These are the products */
//$issue_date
//$volume


/*=======================
    Volume List
=======================*/
$query = "SELECT DISTINCT(issue_volume) AS volume, COUNT(id) AS issue_count";	
$query .= " FROM cm_issues ";
$query .= " WHERE issue_date < '$next_issue_date' ";
$query .= " GROUP BY issue_volume";
$query .= " ORDER BY issue_volume DESC;";

// Run Query
$result = run_query($query);
while ($array = $result->GetArray())
{    $volume_list = $array;}

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
$result = run_query($query);

while ($array = $result->GetArray())
{    $issue_list = $array;}

// Assign variables
$smarty->assign("issue_list", $issue_list);

/*=======================
    Article List
=======================*/
$query = "SELECT cm_articles.id, cm_articles.id AS article_id, "; // article_id depreciated
$query .= " article_title, article_summary, article_author, article_word_count, section_name, issue_volume, issue_number ";
$query .= " FROM cm_articles INNER JOIN (cm_sections, cm_issues) ";
$query .= " ON (cm_sections.id = cm_articles.section_id AND cm_issues.id = cm_articles.issue_id)";
$query .= " WHERE issue_date = '$issue_date' AND issue_date < '$next_issue_date' ";
$query .= " ORDER BY section_priority ASC, article_priority ASC;";

// Run query
$result = run_query($query);

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
while ($array = $result->GetArray())
{    $article_list = $array;}

// Assign variables
$smarty->assign("article_list", $article_list);

$smarty->assign("page_title", "Archives");
$smarty->assign("section_name", "Archives");

// Render
$smarty->display("archives.tpl");