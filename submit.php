<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

if ($_GET['mode'] != "") {
	$mode = $_GET['mode'];
} else {;
	$mode = "letter";
};

// If posted
if (!empty($_POST['text'])) {
	if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['title'])) {
		header("Location: $PHP_SELF?msg=missing");
		exit;
	}
	$submitted_title = $_POST['title'];
	$submitted_text = $_POST['text'];
	$submitted_keyword = strtoupper($mode);
	$submitted_author = $_POST['name'];
	$submitted_author_email = $_POST['email'];
	$submitted_author_classification = $_POST['class'];	
	$submitted_author_major = $_POST['major'];
	$submitted_author_city = $_POST['hometown'];
	$submitted_author_telephone = $_POST['telephone'];
	$submitted_word_count = count_words($submitted_text);
	$issue_id = next_issue('id');

    $smarty->assign("submitted_title", $submitted_title );
    $smarty->assign("submitted_text", $submitted_text );
    $smarty->assign("submitted_keyword", $submitted_keyword );
    $smarty->assign("submitted_author", $submitted_author );
    $smarty->assign("submitted_author_email", $submitted_author_email );
    $smarty->assign("submitted_author_classification", $submitted_author_classification );
    $smarty->assign("submitted_author_major", $submitted_author_major );
    $smarty->assign("submitted_author_city", $submitted_author_city );
    $smarty->assign("submitted_author_telephone", $submitted_author_telephone );
    $smarty->assign("submitted_sent", $submitted_sent );
    $smarty->assign("submitted_word_count", $submitted_word_count );
    $smarty->assign("issue_id", $issue_id );

    $query = "INSERT INTO cm_submitted (submitted_title, submitted_text, submitted_keyword, submitted_author, submitted_author_email, submitted_author_classification, submitted_author_major, submitted_author_city, submitted_author_telephone, submitted_sent, submitted_words, issue_id) VALUES ('$submitted_title', '$submitted_text', '$submitted_keyword', '$submitted_author', '$submitted_author_email', '$submitted_author_classification', '$submitted_author_major', '$submitted_author_city', '$submitted_author_telephone', now(), '$submitted_word_count', '$issue_id');";

	$stat = $db->Execute($query);

	if ($stat == 1) {
	   
	   $recipient = site_info('email');
	   $subject = stripslashes($submitted_title);
	   $message = stripslashes($smarty->fetch("submit-email.tpl"));
	   $header = "From: $submitted_author_email";
	   
	   $sendit = mail($recipient, $subject, $message, $header);
	   
		header("Location: $PHP_SELF?msg=submitted");
		exit;
	} else {
		header("Location: $PHP_SELF?msg=failed");
		exit;
	};	
};

$page_title = "Submit";

// Assign variables
$smarty->assign("page_title", $page_title);


// Render
$smarty->display("submit.tpl");
