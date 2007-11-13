<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

$active_poll = site_info('active_poll');

// Cast Vote
if (is_numeric($_POST['vote-cast'])) {;
	$vote = strip_tags($_POST['vote-cast']);
	$stat = cast_ballot($vote);
	if ($stat == 1) {;
		header("Location: $PHP_SELF?msg=counted");
		exit;
	} else {
		header("Location: $PHP_SELF?msg=failed");
		exit;
	};
};

$smarty->assign("poll_question", get_ballot('question',$active_poll) );
$smarty->assign("poll_r1", get_ballot('response_1',$active_poll) );
$smarty->assign("poll_r2", get_ballot('response_2',$active_poll) );
$smarty->assign("poll_r3", get_ballot('response_3',$active_poll) );
$smarty->assign("poll_r4", get_ballot('response_4',$active_poll) );
$smarty->assign("poll_r5", get_ballot('response_5',$active_poll) );
$smarty->assign("poll_r6", get_ballot('response_6',$active_poll) );
$smarty->assign("poll_r7", get_ballot('response_7',$active_poll) );
$smarty->assign("poll_r8", get_ballot('response_8',$active_poll) );
$smarty->assign("poll_r9", get_ballot('response_9',$active_poll) );
$smarty->assign("poll_r10", get_ballot('response_10',$active_poll) );
$smarty->assign("poll_v1", poll_results(1,$active_poll) );
$smarty->assign("poll_v2", poll_results(2,$active_poll) );
$smarty->assign("poll_v3", poll_results(3,$active_poll) );
$smarty->assign("poll_v4", poll_results(4,$active_poll) );
$smarty->assign("poll_v5", poll_results(5,$active_poll) );
$smarty->assign("poll_v6", poll_results(6,$active_poll) );
$smarty->assign("poll_v7", poll_results(7,$active_poll) );
$smarty->assign("poll_v8", poll_results(8,$active_poll) );
$smarty->assign("poll_v9", poll_results(9,$active_poll) );
$smarty->assign("poll_v10", poll_results(10,$active_poll) );

$poll_v1 = poll_results(1,$active_poll);
$poll_v2 = poll_results(2,$active_poll);
$poll_v3 = poll_results(3,$active_poll);
$poll_v4 = poll_results(4,$active_poll);
$poll_v5 = poll_results(5,$active_poll);
$poll_v6 = poll_results(6,$active_poll);
$poll_v7 = poll_results(7,$active_poll);
$poll_v8 = poll_results(8,$active_poll);
$poll_v9 = poll_results(9,$active_poll);
$poll_v10 = poll_results(10,$active_poll);

$poll_votes = $poll_v1 + $poll_v2 + $poll_v3 + $poll_v4 + $poll_v5 + $poll_v6 + $poll_v7 + $poll_v8 + $poll_v9 + $poll_v10;

$smarty->assign("poll_votes", $poll_votes);

$related_article = get_ballot('article_id', $active_poll);

// Get related article if set
$query = "SELECT id, article_title ";
$query .= " FROM cm_articles ";
$query .= " WHERE id = '$related_article' LIMIT 1;";

// Run query
$result = run_query($query);

$smarty->assign("related_article_id", $result->Fields(id) );
$smarty->assign("related_article_title", $result->Fields(article_title) );

// Assign variables
$smarty->assign("page_title", "Web Poll");
$smarty->assign("section_name", "Web Poll");
$smarty->display("poll.tpl");