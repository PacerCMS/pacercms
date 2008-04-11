<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

// Switch if in preview mode
if (!is_numeric(preview_mode()))
{
    $current_issue_id = current_issue('id');
    $current_issue_date = current_issue('date');
} else {
    $preview = preview_mode();
    $current_issue_id = $preview;
    $current_issue_date = issue_info('date', $preview) . " 00:00:01"; 
}

$smarty->assign("current_issue_date", $current_issue_date);
$smarty->assign("section_sidebar", section_info('sidebar', 1) );

/*=======================
    Cover Articles
=======================*/
$query = "SELECT id, article_title, article_subtitle, article_author, article_author_title, article_summary, article_publish ";
$query .= " FROM cm_articles ";	
$query .= " WHERE section_id = '1' AND issue_id = '$current_issue_id' ";
$query .= " ORDER BY article_priority ASC;";

// Run query
$result = run_query($query);

while ($array = $result->GetArray())
{    $cover_articles = $array;}

// Locates the first story
$top_story = $cover_articles[0][id]; 

// Assign variables
$smarty->assign("cover_articles", $cover_articles);

/*=======================
    Top Artice Images
=======================*/
$query = "SELECT * FROM cm_media ";	
$query .= " WHERE article_id = '$top_story' ";
$query .= " AND (media_type = 'jpg' OR media_type = 'png' OR media_type = 'gif') ";
$query .= " ORDER BY id ASC;";

// Run query
$result = run_query($query);
if (!empty($result))
{    while ($array = $result->GetArray())
    {        $top_article_images = $array;    }
}

// Assign variables
$smarty->assign("top_article_images", $top_article_images);

/*=======================
    Cover Article SWFs
=======================*/
$query = "SELECT * FROM cm_media ";	
$query .= " WHERE article_id = '$top_story' ";
$query .= " AND (media_type = 'swf') ";
$query .= " ORDER BY id ASC;";

// Run query
$result = run_query($query);

if (!empty($result))
{
    while ($array = $result->GetArray()) {        $top_article_swfs = $array;    }
}

// Assign variables
$smarty->assign("top_article_swfs", $top_article_swfs);

/*=======================
    Web Poll
=======================*/
$active_poll = site_info('active_poll');

$smarty->assign("poll_question", get_ballot('question', $active_poll) );
$smarty->assign("poll_r1", get_ballot('response_1', $active_poll) );
$smarty->assign("poll_r2", get_ballot('response_2', $active_poll) );
$smarty->assign("poll_r3", get_ballot('response_3', $active_poll) );
$smarty->assign("poll_r4", get_ballot('response_4', $active_poll) );
$smarty->assign("poll_r5", get_ballot('response_5', $active_poll) );
$smarty->assign("poll_r6", get_ballot('response_6', $active_poll) );
$smarty->assign("poll_r7", get_ballot('response_7', $active_poll) );
$smarty->assign("poll_r8", get_ballot('response_8', $active_poll) );
$smarty->assign("poll_r9", get_ballot('response_9', $active_poll) );
$smarty->assign("poll_r10", get_ballot('response_10', $active_poll) );

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

// Render
$smarty->display("home.tpl");