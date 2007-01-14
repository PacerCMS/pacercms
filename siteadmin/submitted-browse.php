<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "submitted-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if ($_COOKIE["$module-issue"] == "") {
	setcookie("$module-issue", $next_issue_id); // Current Issue
	$issue = $next_issue_id;
} else {
	$issue = $_COOKIE["$module-issue"];
}

// Switch issues
if (is_numeric($_GET['issue'])) {
	$issue = $_GET['issue'];
	if (cm_issue_info("id",$issue) == "") {
		cm_error("The selected issue could not be loaded.");
	}	
	setcookie("$module-issue", $issue);
	header("Location: $module.php");
	exit;
}


// Database Query
$query = "SELECT *, DATE_FORMAT(submitted_sent, '%b. %e, %Y at %l:%i %p')AS sent FROM cm_submitted WHERE issue_id = '$issue'  ORDER BY submitted_sent DESC;";

// Run Query
$result  = mysql_query($query, $CM_MYSQL) or die(mysql_error());
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);

?>
<?php get_cm_header(); ?>

<h2>Submitted Article Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"systemMessage\">Submitted article added.</p>"; }
if ($msg == "updated") { echo "<p class=\"systemMessage\">Submitted updated.</p>"; }
if ($msg == "deleted") { echo "<p class=\"systemMessage\">Submitted article deleted.</p>"; }
?>
<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Select Issue</legend>
  <div class="actionMenu">
    <ul>
      <li>
        <label>Select Issue:</label>
        <select name="issue" id="issue">
          <?php cm_issue_list($module, $issue); ?>
        </select>
        <input type="submit" name="submit" id="submit" value="Open" class="button" />
      </li>
      <li><a href="<?php echo "$module.php?issue=$current_issue_id";?>" <?php if ($issue == $current_issue_id) {	echo " class=\"selected\""; } ?>><strong>Current:</strong> <?php echo $current_issue_date; ?></a></li>
      <li><a href="<?php echo "$module.php?issue=$next_issue_id";?>" <?php if ($issue == $next_issue_id) {	echo " class=\"selected\""; } ?>><strong>Next:</strong> <?php echo $next_issue_date; ?></a></li>
    </ul>
  </div>
  </fieldset>
</form>
<form action="article-edit.php" method="get" name="QuickEdit" id="QuickEdit">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo "Viewing: " . cm_issue_info("issue_date", $issue) . " (Volume " . cm_issue_info("issue_volume", $issue) . ", No. " . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
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
    <td nowrap class="actionMenu">
      <ul class="center">
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#preview">Preview</a></li>
        <?php if ($show_submitted_delete == "true") { ?>
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#delete">Delete</a></li>
        <?php } ?>
      </ul>
    </td>
  </tr>
  <? } while ($result_array = mysql_fetch_assoc($result)); ?>
</table>
  <?php } else { ?>
  <p>There are no submitted articles for this issue.</p>
  <?php } ?>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
