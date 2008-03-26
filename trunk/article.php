<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

/* If id is numeric, use it; else error */
if (is_numeric($_GET['id']))
{
	$article_id = $_GET['id'];
} else {
    // Error if not numeric
    cm_error("Could not find article");
	exit;
}

$current_issue_id = current_issue('id');
$next_issue_date = next_issue('date');

/*=======================
    Selected Article
=======================*/
$query = "SELECT * ";
$query .= " FROM cm_articles ";
$query .= " WHERE id = $article_id AND article_publish < '$next_issue_date'; ";

// Run query
$result = run_query($query);

// Error if empty
if (empty($result))
{
    cm_error("Could not find article");
    exit;
}

// Define variables
$article_id = $result->Fields(id);
$article_title = $result->Fields(article_title);
$article_summary = $result->Fields(article_summary);
$article_text = autop($result->Fields(article_text));
$article_author = $result->Fields(article_author);
$article_author_title = $result->Fields(article_author_title);
$article_keywords = $result->Fields(article_keywords);
$article_publish = $result->Fields(article_publish);	
$article_edit = $result->Fields(article_edit);

// Used in later assignment
$article_issue_id = $result->Fields(issue_id);
$article_section_id = $result->Fields(section_id);

// Assign variables
$smarty->assign("page_title", $article_title );
$smarty->assign("page_description", strip_tags($article_summary) );
$smarty->assign("article_id", $article_id );
$smarty->assign("article_title", $article_title );
$smarty->assign("article_summary", $article_summary );
$smarty->assign("article_text", $article_text );
$smarty->assign("article_author", $article_author );
$smarty->assign("article_author_title", $article_author_title );
$smarty->assign("article_keywords", $article_keywords );
$smarty->assign("article_publish", $article_publish );	
$smarty->assign("article_edit", $article_edit );

$smarty->assign("section_name", section_info('name', $article_section_id) );
$smarty->assign("section_url", section_info('url', $article_section_id) );

$smarty->assign("issue_date", issue_info('date', $article_issue_id) );
$smarty->assign("issue_volume", issue_info('volume', $article_issue_id) );
$smarty->assign("issue_number", issue_info('number', $article_issue_id) );

/*=======================
    Article Section
=======================*/
$query = "SELECT * ";
$query .= " FROM cm_articles ";
$query .= " WHERE section_id = $article_section_id AND issue_id = $current_issue_id;";

// Run query
$result = run_query($query);
if (!empty($result))
{
    while ($array = $result->GetArray())
    {        $section_headlines = $array;    }
}

// Assign variables
$smarty->assign("section_headlines", $section_headlines );


/*=======================
    Article Images
=======================*/
$query = "SELECT * FROM cm_media ";	
$query .= " WHERE article_id = '$article_id' ";
$query .= " AND (media_type = 'jpg' OR media_type = 'png' OR media_type = 'gif') ";
$query .= " ORDER BY id ASC; ";

// Run query
$result = run_query($query);
if (!empty($result)) {    while ($array = $result->GetArray()) {        $article_images = $array;    }
}

// Assign variables
$smarty->assign("article_images", $article_images );

/*=======================
    Article SWFs
=======================*/
$query = "SELECT * FROM cm_media ";	
$query .= " WHERE article_id = '$article_id' ";
$query .= " AND (media_type = 'swf') ";
$query .= " ORDER BY id ASC; ";

// Run query
$result = run_query($query);
if (!empty($result)) {    while ($array = $result->GetArray()) {        $article_swfs = $array;
    }
}

// Assign variables
$smarty->assign("article_swfs", $article_swfs );


/*=======================
    Article Related
=======================*/
$query = "SELECT * FROM cm_media ";	
$query .= " WHERE article_id = '$article_id' ";
$query .= " AND (media_type = 'pdf' OR media_type = 'doc' OR media_type = 'wav' OR media_type = 'url') ";
$query .= " ORDER BY id ASC; ";

// Run query
$result = run_query($query);
if (!empty($result))
{    while ($array = $result->GetArray())
    {        $article_related = $array;    }
}

// Assign variables
$smarty->assign("article_related", $article_related );

/*=======================
    Section Summaries
=======================*/
foreach ( section_list('array') as $section_info )
{
    $id = $section_info[id];
    $smarty->assign("section_name_$id", $section_info['section_name'] );
    $smarty->assign("section_url_$id", $section_info['section_url'] );
    $smarty->assign("section_summary_$id", section_headlines($id, $current_issue_id) );
}

// Render
$smarty->display("article.tpl");