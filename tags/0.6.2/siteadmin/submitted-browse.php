<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "submitted-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if (!is_numeric($_COOKIE["$module-issue"]))
{
	setcookie("$module-issue", cm_next_issue('id')); // Current Issue
	$issue = cm_next_issue('id');
} else {
	$issue = $_COOKIE["$module-issue"];
}

// Switch issues
if (is_numeric($_GET['issue']))
{
	$issue = $_GET['issue'];
	if (cm_issue_info("id",$issue) == "") {
		cm_error("The selected issue could not be loaded.");
	}	
	setcookie("$module-issue", $issue);
	header("Location: $module.php");
	exit;
}

// Moderate submitted articles
if (is_array($_POST['moderate']) && cm_auth_restrict('submitted-edit'))
{
    foreach ($_POST['moderate'] as $id)
    {
        if (is_numeric($id)) { $stat = cm_delete_submitted($id); }
    }
    if ($stat)
    {
        header("Location: $module?msg=moderate");
        exit;
    } else {
        cm_error("There was an error in the 'cm_delete_submitted' function.");
        exit;
    }
}



// Database Query
$query = "SELECT *, DATE_FORMAT(submitted_sent, '%b. %e, %Y at %l:%i %p')AS sent FROM cm_submitted WHERE issue_id = '$issue'  ORDER BY submitted_sent DESC;";

// Run Query
$result = cm_run_query($query);
$records = $result->GetArray();

get_cm_header();

?>

<h2>Submitted Article Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"infoMessage\">Submitted article added.</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">Submitted updated.</p>"; }
if ($msg == "deleted") { echo "<p class=\"alertMessage\">Submitted article deleted.</p>"; }
if ($msg == "moderate") { echo "<p class=\"alertMessage\">Submitted article(s) deleted.</p>"; }

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
      <li><a href="<?php echo "$module.php?issue=" . cm_current_issue('id'); ?>" <?php if ($issue == cm_current_issue('id')) {	echo " class=\"selected\""; } ?>><strong>Current:</strong> <?php echo cm_current_issue('date'); ?></a></li>
      <li><a href="<?php echo "$module.php?issue=" . cm_next_issue('id'); ?>" <?php if ($issue == cm_next_issue('id')) {	echo " class=\"selected\""; } ?>><strong>Next:</strong> <?php echo cm_next_issue('date'); ?></a></li>
    </ul>
  </div>
  </fieldset>
</form>
<form action="<?php echo $module; ?>.php" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo "Viewing: " . cm_issue_info("issue_date", $issue) . " (Volume " . cm_issue_info("issue_volume", $issue) . ", No. " . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
<?php if ($result->RecordCount() > 0) { ?>

<table class="<?php echo $module; ?>-table">
  <tr>
  <?php if (cm_auth_restrict('submitted-edit') == "true") { ?>
    <th>ID</td>
  <?php } ?>  
    <th>Sent</th>
    <th>From</th>
    <th>Subject</th>
    <?php if (cm_auth_restrict('submitted-edit') == "true") { ?>
    <th>Tools</th>
    <?php } ?>
  </tr>
  <?php

foreach ($records as $record) {

	$id = $record['id'];
	$title = $record['submitted_title'];
	$keyword = $record['submitted_keyword'];
	$author = $record['submitted_author'];
	$email = $record['submitted_author_email'];
	$sent = $record['sent'];
  
?>
  <tr>
  <?php if (cm_auth_restrict('submitted-edit') == "true") { ?>
    <td><input type="checkbox" name="moderate[]" id="moderate-<?php echo $id; ?>" value="<?php echo $id; ?>" /></td>
  <?php } ?>
    <td><?php echo $sent; ?></td>
    <td><a href="mailto:<?php echo $email; ?>"><?php echo $author; ?></a></td>
    <td><p><a href="submitted-edit.php?id=<?php echo $id; ?>#preview"><strong><?php echo $title; ?></strong></a> - <em><?php echo $keyword;?></em></p>
    </td>
    <td nowrap class="actionMenu">
      <ul class="center">
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#preview">Preview</a></li>
        <li class="command-preview"><a href="article-edit.php?action=new&amp;submitted_id=<?php echo $id; ?>">Post</a></li>
        <?php if (cm_auth_restrict('submitted-delete') == "true") { ?>
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#delete">Delete</a></li>
        <?php } ?>
      </ul>
    </td>
  </tr>
<? } ?>
</table>
<?php if (cm_auth_restrict('submitted-edit') == "true") { ?>
<div style="text-align:right;">
    <input type="submit" value="Delete Checked Articles" />
    <input type="reset" value="Clear Selection" />
</div>
<?php } ?>
  <?php } else { ?>
  <p>There are no submitted articles for this issue.</p>
  <?php } ?>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
