<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "index";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
$issue = $next_issue_id;

// Database Query
$query = "SELECT *, DATE_FORMAT(submitted_sent, '%b. %e, %Y at %l:%i %p')AS sent FROM cm_submitted WHERE issue_id = '$issue'  ORDER BY submitted_sent DESC;";

// Run Query
$result = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);


get_cm_header();

?>

<h2>Welcome <?php echo $_SESSION['cm_user_fullname']; ?></h2>
<div class="sidebar" style="border:solid 1px #ccc;background: #fff;padding:10px;">
  <h3>Quick Links</h3>
  <ul>
    <?php if ($show_article_browse == "true") { ?>
    <li><a href="article-browse.php">Manage Articles</a></li>
    <?php } ?>
    <?php if ($show_article_edit == "true") { ?>
    <li><a href="article-edit.php?action=new">Add an Article</a></li>
    <?php } ?>
    <?php if ($show_poll_browse == "true") { ?>
    <li><a href="poll-browse.php">Manage Poll Questions</a></li>
    <?php } ?>
    <?php if ($show_poll_edit == "true") { ?>
    <li><a href="poll-edit.php?action=new">Add a Poll Question</a></li>
    <?php } ?>
  </ul>
</div>
<h3>Announcements</h3>
<?php echo autop(cm_get_settings('site_announcement')); ?>
<div style="clear:both;"></div>
<fieldset class="<?php echo "$module-form"; ?>">
<legend><a href="submitted-browse.php">Submitted Articles</a> for <?php echo cm_issue_info("issue_date", $issue) . " (Volume " . cm_issue_info("issue_volume", $issue) . ", No. " . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
<?php if ($result_row_count > 0) { ?>
<table class="<?php echo $module; ?>-table">
  <tr>
    <th>Sent</th>
    <th>From</th>
    <th>Subject</th>
    <?php if ($show_submitted_edit == "true") { ?>
    <th>Tools</th>
    <?php } ?>
  </tr>
  <?php

do {

	$id = $result_array['id'];
	$title = $result_array['submitted_title'];
	$keyword = $result_array['submitted_keyword'];
	$author = $result_array['submitted_author'];
	$email = $result_array['submitted_author_email'];
	$sent = $result_array['sent'];
  
?>
  <tr>
    <td><?php echo $sent; ?></td>
    <td><a href="mailto:<?php echo $email; ?>"><?php echo $author; ?></a></td>
    <td><p><a href="submitted-edit.php?id=<?php echo $id; ?>#preview"><strong><?php echo $title; ?></strong></a> - <em><?php echo $keyword;?></em></p>
    </td>
    <?php if ($show_submitted_edit == "true") { ?>
    <td nowrap class="actionMenu">
      <ul class="center">
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#preview">Preview</a></li>
        <?php if ($show_submitted_delete == "true") { ?>
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#delete">Delete</a></li>
        <?php } ?>
      </ul>
      <?php } ?>
    </td>
  </tr>
  <? } while ($result_array = mysql_fetch_assoc($result)); ?>
</table>
<?php } else { ?>
<p>There are no submitted articles for this issue.</p>
<?php } ?>
</fieldset>
<?php get_cm_footer(); ?>
