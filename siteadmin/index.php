<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "index";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
$issue = $next_issue_id;

// Database Query
$query_CM_Array = "SELECT *, DATE_FORMAT(submitted_sent, '%b. %e, %Y at %l:%i %p')AS sent FROM cm_submitted WHERE issue_id = '$issue'  ORDER BY submitted_sent DESC;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Welcome <?php echo $_SESSION['cm_user_fullname']; ?></h2>
<div class="sidebar" style="border:solid 1px #ccc;background: #fff;padding:10px;">
  <h3>Quick Links</h3>
  <ul>
    <?php if ($show_article_browse == "true") {; ?>
    <li><a href="article-browse.php">Manage Articles</a></li>
    <?php }; ?>
    <?php if ($show_article_edit == "true") {; ?>
    <li><a href="article-edit.php?action=new">Add an Article</a></li>
    <?php }; ?>
    <?php if ($show_poll_browse == "true") {; ?>
    <li><a href="poll-browse.php">Manage Poll Questions</a></li>
    <?php }; ?>
    <?php if ($show_poll_edit == "true") {; ?>
    <li><a href="poll-edit.php?action=new">Add a Poll Question</a></li>
    <?php }; ?>
  </ul>
</div>
<h3>Announcements</h3>
<?php echo autop(cm_get_settings('site_announcement')); ?>
<div style="clear:both;"></div>
<fieldset class="<?php echo "$module-form"; ?>">
<legend><a href="submitted-browse.php">Submitted Articles</a> for <?php echo cm_issue_info("issue_date", $issue) . " (Volume " . cm_issue_info("issue_volume", $issue) . ", No. " . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
<?php if ($totalRows_CM_Array > 0) {; ?>
<table class="<?php echo $module; ?>-table">
  <tr>
    <th>Sent</th>
    <th>From</th>
    <th>Subject</th>
    <?php if ($show_submitted_edit == "true") {; ?>
    <th>Tools</th>
    <?php }; ?>
  </tr>
  <?php

do {;

	$id = $row_CM_Array['id'];
	$title = $row_CM_Array['submitted_title'];
	$keyword = $row_CM_Array['submitted_keyword'];
	$author = $row_CM_Array['submitted_author'];
	$email = $row_CM_Array['submitted_author_email'];
	$sent = $row_CM_Array['sent'];
  
?>
  <tr>
    <td><?php echo $sent; ?></td>
    <td><a href="mailto:<?php echo $email; ?>"><?php echo $author; ?></a></td>
    <td><p><a href="submitted-edit.php?id=<?php echo $id; ?>#preview"><strong><?php echo $title; ?></strong></a> - <em><?php echo $keyword;?></em></p>
    </td>
    <?php if ($show_submitted_edit == "true") {; ?>
    <td nowrap class="actionMenu">
      <ul class="center">
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#preview">Preview</a></li>
        <?php if ($show_submitted_delete == "true") {; ?>
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#delete">Delete</a></li>
        <?php }; ?>
      </ul>
      <?php }; ?>
    </td>
  </tr>
  <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
</table>
<?php } else {; ?>
<p>There are no submitted articles for this issue.</p>
<?php }; ?>
</fieldset>
<?php get_cm_footer(); ?>
