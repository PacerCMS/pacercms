<?php include('cm-includes/config.php'); ?>
<?php
$module = "submitted-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if ($_COOKIE["$module-issue"] == "") {;
	setcookie("$module-issue", $next_issue_id); // Current Issue
	$issue = $next_issue_id;
} else {;
	$issue = $_COOKIE["$module-issue"];
};

// Switch issues
if (is_numeric($_GET['issue'])) {;
	$issue = $_GET['issue'];
	if (cm_issue_info("id",$issue) == "") {;
		cm_error("The selected issue could not be loaded.");
	};	
	setcookie("$module-issue", $issue);
	header("Location: $module.php");
	exit;
};


// Database Query
$query_CM_Array = "SELECT *, DATE_FORMAT(submitted_sent, '%b. %e, %Y at %l:%i %p')AS sent FROM cm_submitted WHERE issue_id = '$issue'  ORDER BY submitted_sent DESC;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

?>
<?php get_cm_header(); ?>

<h2>Submitted Article Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") {; echo "<p class=\"systemMessage\">Submitted article added.</p>"; };
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Submitted updated.</p>"; };
if ($msg == "deleted") {; echo "<p class=\"systemMessage\">Submitted article deleted.</p>"; };
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
      <li><a href="<?php echo "$module.php?issue=$current_issue_id";?>" <?php if ($issue == $current_issue_id) {;	echo " class=\"selected\""; }; ?>><strong>Current:</strong> <?php echo $current_issue_date; ?></a></li>
      <li><a href="<?php echo "$module.php?issue=$next_issue_id";?>" <?php if ($issue == $next_issue_id) {;	echo " class=\"selected\""; }; ?>><strong>Next:</strong> <?php echo $next_issue_date; ?></a></li>
    </ul>
  </div>
  </fieldset>
</form>
<form action="article-edit.php" method="get" name="QuickEdit" id="QuickEdit">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo "Viewing: " . cm_issue_info("issue_date", $issue) . " (Volume " . cm_issue_info("issue_volume", $issue) . ", No. " . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
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
    <td nowrap class="actionMenu">
      <ul class="center">
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#preview">Preview</a></li>
        <?php if ($show_submitted_delete == "true") {; ?>
        <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#delete">Delete</a></li>
        <?php }; ?>
      </ul>
    </td>
  </tr>
  <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
</table>
  <?php } else {; ?>
  <p>There are no submitted articles for this issue.</p>
  <?php }; ?>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
