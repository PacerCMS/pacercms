<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

if (!empty($_GET['msg'])) {
    $msg = $_GET['msg'];
    $smarty->assign("status_message", $msg );
}

// If posted
if (!empty($_POST['text'])) {
	if (empty($_POST['name']) || empty($_POST['email'])) {
		header("Location: $PHP_SELF?msg=missing");
		exit;
	}

	$feedback_name = $_POST['name'];
	$feedback_email = $_POST['email'];	
	$feedback_text = $_POST['text'];

    $smarty->assign("feedback_name", $feedback_name);
    $smarty->assign("feedback_email", $feedback_email);  
    $smarty->assign("feedback_text", $feedback_text);

    // Build the e-mail message
	$recipient = site_info('email');
	$header = "From: $feedback_email";
	$subject = "Web site feedback from $feedback_name";
	$message = $smarty->fetch("feedback-email.tpl");
	
	// Send the e-mail notification
	$sendit = mail($recipient, $subject, $message, $header);
	if ($sendit == 1) {
		header("Location: $PHP_SELF?" . $_SERVER['QUERY_STRING'] . "&msg=submitted");
		exit;
	} else {
		header("Location: $PHP_SELF?" . $_SERVER['QUERY_STRING'] . "&msg=failed");
		exit;
	};
};


/* Header Configuration */
$page_title = "Feedback";

$smarty->assign("section_title", $page_title);
$smarty->assign("page_title", $page_title);
$smarty->display("feedback.tpl");
