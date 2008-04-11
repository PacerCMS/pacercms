<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

/* If id is numeric, use it; else error */
if (is_numeric($_GET['id'])) {
	$section_id = $_GET['id'];
} else {
    // Error if not numeric
    cm_error("Could not find section");
	exit;
};

// Switch if in preview mode
if (!is_numeric(preview_mode()))
{
    $current_issue_id = current_issue('id');
    $current_issue_date = current_issue('date');
} else {
    $preview = preview_mode();
    $current_issue_id = $preview;
    $current_issue_date = issue_info('date', $preview); 
}


$smarty->assign("current_issue_date", $current_issue_date);
$smarty->assign("section_name", section_info('name', $section_id) );
$smarty->assign("section_id", $section_id);
$smarty->assign("section_editor", section_info('editor', $section_id) );
$smarty->assign("section_editor_title", section_info('editor_title', $section_id) );
$smarty->assign("section_editor_email", section_info('editor_email', $section_id) );
$smarty->assign("section_sidebar", section_info('sidebar', $section_id) );

/*=======================
    Section Articles
=======================*/
$query = "SELECT id, article_title, article_subtitle, article_author, article_author_title, article_summary ";
$query .= " FROM cm_articles ";	
$query .= " WHERE section_id = '$section_id' AND issue_id = '$current_issue_id' ";
$query .= " ORDER BY article_priority ASC;";

// Run query
$result = run_query($query);

if (!empty($result)) {
    while ($array = $result->GetArray()) {        $section_articles = $array;    }
}

// Assign variables
$smarty->assign("section_articles", $section_articles);

/*=======================
    Section Summaries
=======================*/
foreach ( section_list('array') as $section_info )
{
    $id = $section_info[id];
    $smarty->assign("section_name_$id", section_info('name', $id) );
    $smarty->assign("section_url_$id", section_info('url', $id) );
    $smarty->assign("section_summary_$id", section_headlines($id, $current_issue_id) );
}

$smarty->assign("page_title", section_info('name', $section_id) );

// Check if a 'section-#.tpl' file exists
if ( file_exists(TEMPLATES_PATH . "/section-$section_id.tpl") )
{
    // Use the customized section template
    $smarty->display("section-$section_id.tpl");
} else {
    // Use the default section template
    $smarty->display("section.tpl");
}