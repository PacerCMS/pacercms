<?php include('cm-includes/config.php'); ?>
<?php
$module = "poll-browse";
$cmodule = "poll-edit";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change active poll
if (is_numeric($_POST['id'])) {;
	$id = $_POST['id'];
	$stat = cm_edit_poll_settings($id);
	if ($stat = 1) {
		header("Location: $module.php?msg=active-updated");
		exit;
		} else {;
		cm_error("You have an error in the cm_edit_poll_settings function.");
		exit;
	};
};


// Database Query
$query_CM_Array = "SELECT * FROM cm_poll_questions ORDER BY poll_created DESC;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Poll Manager</h2>
<?php $msg = $_GET['msg'];
if ($msg == "added") {; echo "<p class=\"systemMessage\">Poll question added.</p>"; };
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Poll question updated.</p>"; };
if ($msg == "deleted") {; echo "<p class=\"systemMessage\">Poll question and ballots deleted.</p>"; };
if ($msg == "active-updated") {; echo "<p class=\"systemMessage\">Active poll setting changed.</p>"; };
?>

<form action="<?php echo "$module.php"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Active Poll</legend>
  <p>
    <label for="id">Current Poll Question</label>
    <select name="id" id="id">
	<option value="0" <?php if (cm_get_settings('active_poll') == 0) {; echo "SELECTED"; }; ?>>--- Polls Disabled ---</option>
	<?php cm_poll_list(cm_get_settings('active_poll')); ?>
	</select>
	<input type="submit" id="submit-active-poll" value="Change Active Poll" class="button" />
	</p>
  </fieldset>
</form>

<?php if ($totalRows_CM_Array > 0) {; // If there are any pages ?>

<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-table"; ?>">
  <legend>Active Poll</legend>
  <div class="actionMenu"><ul>
  <li><strong>Poll Options:</strong></li>
  <li><a href="<?php echo "$cmodule.php?action=add"; ?>">Add New Poll</a></li> 
  </ul></div>
  <table>
    <tr>
      <th>Question</th>
      <th>Created</th>
      <th>Tools</th>
    </tr>
    <?php

	// To mark active in table
	$active_poll = cm_get_settings('active_poll');

do {;
	$id = $row_CM_Array['id'];
	$question = $row_CM_Array['poll_question'];
	$created = $row_CM_Array['poll_created'];
?>
    <tr>
      <td><?php if ($active_poll == $id) {; echo "<strong>Current &raquo;&raquo;</strong> "; }; ?><a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo $question; ?></a></p>
	  
	  </td>
      <td><?php echo $created; ?></td>
      <td class="actionMenu" nowrap><ul class="center">
          <li><a href="<?php echo "$cmodule.php?id=$id"; ?>">Edit</a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id#results"; ?>">Results</a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id#delete"; ?>">Delete</a></li>
        </ul>
      </td>
    </tr>
    <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
  </table>
  </fieldset>
</form>

<?php } else {; ?>
	<p>You are not currently using polls. Why not <a href="<?php echo "$cmodule.php?action=add"; ?>">add one</a> now?</p>
<?php }; ?>

<?php get_cm_footer(); ?>
