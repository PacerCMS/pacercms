<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

$id = $_GET['id'];

// Query
$query_CM_Array = "SELECT * FROM cm_articles ";
$query_CM_Array .= " WHERE id = '$id'";

// Run Query
$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
// Define variables
$id = $row_CM_Array['id'];
$section = $row_CM_Array['section_id'];
$issue = $row_CM_Array['issue_id'];
$title = $row_CM_Array['article_title'];
$subtitle = $row_CM_Array['article_subtitle'];
$summary = $row_CM_Array['article_summary'];
$text = $row_CM_Array['article_text'];
$keywords = $row_CM_Array['article_keywords'];
$author = $row_CM_Array['article_author'];
$author_title = $row_CM_Array['article_author_title'];
$priority = $row_CM_Array['article_priority']; 
$published = $row_CM_Array['article_publish'];
$edited = $row_CM_Array['article_edit'];

if (isset($_POST['recipient-email'])) {

// Build the e-mail message
	$sender = $_POST['sender'];
	$sender_email = $_POST['sender-email'];
	$recipient = $_POST['recipient-email'];
	$link = site_info('url') . "/article.php?id=$id";
	$header = "From: $sender_email";
	$subject = stripslashes(site_info('name') . " - $title");
	$message = "\n ";
	$message .= stripslashes("A story tip from $sender <$sender_email>\n");
	$message .= "\n ";
	$message .= stripslashes("$title \n");
	$message .= "========================================\n";
	$message .= stripslashes("$summary \n");
	$message .= "========================================\n";
	$message .= "\n ";
	$message .= stripslashes("Link: $link \n");
	$message .= "\n ";
	$message .= stripslashes("NOTE: You have received this e-mail because a visitor used a utility found on our Web site (" . site_info('url') . "/) to forward you this article summary and link. At no time was your e-mail address stored on our server. Please visit our site to learn more about our privacy policies.");
	
	// Send the e-mail notification
	$sendit = mail($recipient, $subject, $message, $header);
	if ($sendit == 1) {
		header("Location: $PHP_SELF?" . $_SERVER['QUERY_STRING'] . "&msg=sent");
		exit;
	} else {
		header("Location: $PHP_SELF?" . $_SERVER['QUERY_STRING'] . "&msg=fail");
		exit;
	};
}

/* Header Configuration */
$topBar = "<span class=\"floatLeft\"><strong>Published</strong> $published</span>";
if ($published < $edited) {;
	$topBar .= " <span class=\"floatRight\"><strong>Updated</strong> $edited</span>";
};
$pageTitle = " &raquo; $title";
$sectionTitle = $section;

?>
<?php get_header($topBar,$pageTitle,$section); ?>

<div id="content">
  <h2 class="sectionNameplate">E-mail Article</h2>
<?php
	$msg = $_GET['msg'];
	if ($msg == "sent") {; echo "<p class=\"systemMessage\">Article was e-mailed.</p>"; };
	if ($msg == "fail") {; echo "<p class=\"systemMessage\">Mailing failed. Check your recipient's e-mail address.</p>"; };
?>
  <div class="colWrap">
    <div class="bigCol"><h3>Preview Outgoing E-mail</h3>
	<blockquote>
	<p><?php echo $title; ?></p>
	<p>========================================</p>
	<p><?php echo $summary; ?></p>
	<p>========================================</p>
	<p>Link: <?php echo site_info('url') . "/article.php?id=$id"; ?></p>
	<p>-</p>
	<p>NOTE: You have received this e-mail because a visitor used a utility found on our Web site (<?php echo site_info('url'); ?>) to forward you this article summary and link. At no time was your e-mail address stored on our server. Please visit our site to learn more about our privacy policies.</p>
	</blockquote>
	</div>
    <div class="smallCol">
      <form action="<?php echo $PHP_SELF; ?>" method="post">
        <fieldset>
        <legend>Required Information</legend>
        <p>
          <label for="recipient">Recipient's
          Name</label>
          <br />
          <input type="text" name="recipient" id="recipient" />
        </p>
        <p>
          <label for="recipient-email">Recipient's
          E-mail Address</label>
          <br />
          <input type="text" name="recipient-email" id="recipient-email" />
        </p>
        <p>
          <label for="sender">Your Name</label>
          <br />
          <input type="text" name="sender" id="sender" />
        </p>
        <p>
          <label for="sender">Your E-mail Address</label>
          <br />
          <input type="text" name="sender-email" id="sender-email" />
        </p>
        <p>
          <input id="submit" type="submit" value="Send" />
          <input id="reset" type="reset" value="Reset" />
        </p>
        </fieldset>
      </form>
    </div>
  </div>
</div>
<?php get_footer(); ?>
