<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

if ($_GET['mode'] != "")
{
    $mode = $_GET['mode'];
} else {;
    $mode = "letter";
}

// If posted
if (!empty($_POST['text']))
{
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['title']))
    {
        header("Location: $PHP_SELF?msg=missing");
        exit;
    }

    $submitted_title = prep_string(strip_tags($_POST['title']));
    $submitted_text = prep_string(strip_tags($_POST['text']));
    $submitted_keyword = strtoupper(strip_tags($mode));
    $submitted_author = prep_string(strip_tags($_POST['name']));
    $submitted_author_email = prep_string(strip_tags($_POST['email']));
    $submitted_author_classification = prep_string(strip_tags($_POST['class']));    
    $submitted_author_major = prep_string(strip_tags($_POST['major']));
    $submitted_author_city = prep_string(strip_tags($_POST['hometown']));
    $submitted_author_telephone = prep_string(strip_tags($_POST['telephone']));
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

    $stat = run_query($query);

    if ($stat)
    {
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
    }    
}

$page_title = "Submit";

// Assign variables
$smarty->assign("page_title", $page_title);

// Render
$smarty->display("submit.tpl");