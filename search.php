<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

$string = strip_tags($_GET['s']);
$index = strip_tags($_GET['index']);
$sort_by = strip_tags($_GET['sort_by']);
$sort_dir = strip_tags($_GET['sort_dir']);

// These allow for short inline URLs
if ($_GET['s_by'] == "author") {
	$index = "author";
	$sort_by = "article_publish";
	$sort_dir = "DESC";

};
if ($_GET['s_by'] == "keyword") {
	$index = "keyword";
	$sort_by = "article_publish";
	$sort_dir = "DESC";
};

$next_issue_id = next_issue("id");
$current_issue_id = current_issue("id");

// Set search modeif ($index == "article") { $field = "article_text,article_title,article_subtitle"; };if ($index == "author") { $field = "article_author"; };if ($index == "keyword") { $field = "article_keywords"; };	$query = "SELECT cm_articles.id AS article_id, article_title, article_summary, article_author, article_word_count, article_publish, section_name ";$query .= " FROM cm_articles INNER JOIN cm_sections ON cm_articles.section_id = cm_sections.id ";
$query .= " WHERE MATCH ($field) AGAINST ('$string' IN BOOLEAN MODE) AND issue_id < $next_issue_id ";
$query .= " ORDER BY $sort_by $sort_dir;";

// Run query
$result = $db->Execute($query);
while ($array = $result->GetArray()) {    $article_list = $array;}

$smarty->assign("article_list", $article_list);

// Assign variables
$smarty->assign("search_string", stripslashes($string) );
$smarty->assign("page_title", "Search for ''$string''");

// Render
$smarty->display("search.tpl");