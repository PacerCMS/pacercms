<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

/* If id is numeric, use it; else error */
if (is_numeric($_GET['id']))
{
	$article_id = $_GET['id'];
} else {
	cm_error("Could not find article");
	exit;
}

$next_issue = next_issue("id");
$current_issue = current_issue("id");

/*=======================
    Selected Article
=======================*/
$query = "SELECT * ";
$query .= " FROM cm_articles ";
$query .= " WHERE id = $article_id AND issue_id < $next_issue;";

// Run query
$result = run_query($query);

// Define variables
$article_id = $result->Fields(id);
$article_title = $result->Fields(article_title);
$article_summary = $result->Fields(article_summary);
$article_text = autop($result->Fields(article_text));
$article_author = $result->Fields(article_author);
$article_author_title = $result->Fields(article_author_title);
$article_publish = $result->Fields(article_publish);	
$article_edit = $result->Fields(article_edit);

// Used in later assignment
$article_section_id = $result->Fields(section_id);
$article_section_name = section_info('name', $article_section_id);
$article_section_url = section_info('url', $article_section_id);

// Assign variables
$smarty->assign("page_title", $article_title );
$smarty->assign("article_id", $article_id );
$smarty->assign("article_title", $article_title );
$smarty->assign("article_summary", $article_summary );
$smarty->assign("article_text", $article_text );
$smarty->assign("article_author", $article_author );
$smarty->assign("article_author_title", $article_author_title );
$smarty->assign("article_publish", $article_publish );	
$smarty->assign("article_edit", $article_edit );
$smarty->assign("article_section_name", section_info('name', $article_section_id) );
$smarty->assign("article_section_url", section_info('url', $article_section_id) );
$smarty->assign("section_name", section_info('name', $article_section_id) );
$smarty->assign("section_url", section_info('url', $article_section_id) );

// Render
$smarty->display("share.tpl");
