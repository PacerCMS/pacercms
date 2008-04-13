<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "poll-browse";
$cmodule = "poll-edit";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change active poll
if (is_numeric($_POST['id']))
{
	$id = $_POST['id'];

	$stat = cm_edit_poll_settings($id);

	if ($stat = 1) {
		header("Location: $module.php?msg=active-updated");
		exit;
		} else {
		cm_error(gettext("You have an error in the cm_edit_poll_settings function."));
		exit;
	}
}


// Database Query
$query = "SELECT * FROM cm_poll_questions ORDER BY poll_created DESC;";

// Run Query
$result = cm_run_query($query);
$records = $result->GetArray();

get_cm_header();

?>


<h2><?php echo gettext("Poll Manager"); ?></h2>
<?php $msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"infoMessage\">" . gettext("Poll question added.") . "</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">" . gettext("Poll question updated.") . "</p>"; }
if ($msg == "deleted") { echo "<p class=\"alertMessage\">" . gettext("Poll question and ballots deleted.") . "</p>"; }
if ($msg == "active-updated") { echo "<p class=\"infoMessage\">" . gettext("Active poll setting changed.") . "</p>"; }
?>

<form action="<?php echo "$module.php"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo gettext("Active Poll"); ?></legend>
  <p>
    <label for="id"><?php echo gettext("Current Poll Question"); ?></label>
    <select name="id" id="id">
	<option value="0" <?php if (cm_get_settings('active_poll') == 0) { echo "selected=\"selected\""; } ?>>-- <?php echo gettext("Polls Disabled"); ?> --</option>
	<?php cm_poll_list(cm_get_settings('active_poll')); ?>
	</select>
	<input type="submit" id="submit-active-poll" value="<?php echo gettext("Change Active Poll"); ?>" class="button" />
	</p>
  </fieldset>
</form>

<?php if ($result->RecordCount() > 0) { ?>

<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-table"; ?>">
  <legend><?php echo gettext("Browe Polls"); ?></legend>
  <div class="actionMenu"><ul>
  <li><strong><?php echo gettext("Poll Options:"); ?></strong></li>
  <li><a href="<?php echo "$cmodule.php?action=new"; ?>"><?php echo gettext("Add New Poll"); ?></a></li> 
  </ul></div>
  <table>
  <thead>
    <tr>
      <th><?php echo gettext("Question"); ?></th>
      <th><?php echo gettext("Created"); ?></th>
      <th><?php echo gettext("Tools"); ?></th>
    </tr>
  </thead>
  <tbody>
<?php

// To mark active in table
$active_poll = cm_get_settings('active_poll');

foreach ($records as $record)
{
	$id = $record['id'];
	$question = $record['poll_question'];
	$created = date('m/d/Y h:i a', strtotime($record['poll_created']));

    if ($rowclass == 'even') { $rowclass = 'odd'; } else { $rowclass = 'even'; }
	
?>
    <tr class="<?php echo $rowclass; ?>">
      <td><?php if ($active_poll == $id) { echo "<strong>" . gettext("Current") . " &raquo;&raquo;</strong> "; } ?><a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo $question; ?></a></p>
	  
	  </td>
      <td><?php echo $created; ?></td>
      <td class="actionMenu" nowrap><ul class="center">
          <li><a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo gettext("Edit"); ?></a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id#results"; ?>"><?php echo gettext("Results"); ?></a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id#delete"; ?>"><?php echo gettext("Delete"); ?></a></li>
        </ul>
      </td>
    </tr>
<?php } ?>
  </tbody>
  </table>
  </fieldset>
</form>

<?php } else { ?>
	<p><?php echo gettext("You are not currently using polls."); ?> <a href="<?php echo "$cmodule.php?action=new"; ?>"><?php echo gettext("Add Poll Question."); ?></a></p>
<?php } ?>

<?php get_cm_footer(); ?>
