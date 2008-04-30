<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "issue-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if ($_COOKIE["$module-volume"] == "") {
	setcookie("$module-volume", cm_current_issue('volume')); // Current Volume
	$volume = cm_current_issue('volume');
} else {
	$volume = $_COOKIE["$module-volume"];
}

// If changing publishing settings
if (is_numeric($_POST['id'])) {
	// Get posted data
	$id = $_POST['id'];
	$current_issue = $_POST['current_issue'];
	$next_issue = $_POST['next_issue'];		
	// Run function
	$stat = cm_edit_publish_settings($current_issue,$next_issue,$id);
	if ($stat) {
		header("Location: $module.php?msg=publish-updated");
		exit;
	} else {
		cm_error(gettext("Error in 'cm_edit_publish_settings' function."));
		exit;
	}
}

// If previewing an issue on public site
if (is_numeric($_REQUEST['preview']))
{
    unset($_SESSION['cm_preview_issue']);
    $_SESSION['cm_preview_issue'] = $_REQUEST['preview'];
	header("Location: $module.php?msg=preview-issue&id=" . $_REQUEST['preview']);
	exit;    
}

// Volume for display
if (is_numeric($_GET['volume'])) {
	setcookie("$module-volume", $_GET['volume']);
	header("Location: $module.php");
	exit;
}


get_cm_header();

?>

<h2><?php echo gettext("Issue Manager"); ?></h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"infoMessage\">" . gettext("Issue added.") . "</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">" . gettext("Issue updated.") . "</p>"; }
if ($msg == "publish-updated") { echo "<p class=\"infoMessage\">" . gettext("Publish settings updated.") . "</p>"; }
if ($msg == "preview-issue") { echo "<p class=\"infoMessage\">" . gettext("Issue preview mode enabled.") . " <a href=\"" . cm_get_settings('site_url') . "\" class=\"icon_window_new\" target=\"_blank\">". gettext("Open") . "</a></p>"; }
?>
<form action="<?php echo "$module.php"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo gettext("Publication Settings"); ?></legend>
  <div class="sidebar">
    <?
	if ($_GET['month'] == "") { $month = date('m'); } else { $month = $_GET['month']; }
	if ($_GET['year'] == "") { $year = date('Y'); } else { $year = $_GET['year']; }
	$cal = new MyCalendar;
	echo $cal->getMonthView($month, $year);
?>
  </div>
  <p>
    <label for="current_issue"><?php echo gettext("Current Issue"); ?></label>
    <br />
    <select name="current_issue" id="current_issue">
      <?php cm_issue_list($module, cm_current_issue('id')); ?>
    </select>
    <strong><?php echo gettext("Stored:"); ?> <?php echo cm_current_issue('date'); ?></strong> </p>
  <p>
    <label for="next_issue"><?php echo gettext("Next Issue"); ?></label>
    <br />
    <select name="next_issue" id="next_issue">
      <?php cm_issue_list($module, cm_next_issue('id')); ?>
    </select>
    <strong><?php echo gettext("Stored:"); ?> <?php echo cm_next_issue('date'); ?></strong> </p>
  <p>
    <input type="submit" value="<?php echo gettext("Update Settings"); ?>" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo cm_get_settings('id'); ?>" />
    <input type="button" value="<?php echo gettext("Cancel"); ?>" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo gettext("Browse Issue Archive"); ?></legend>
  <p>
    <label for="volume"><?php echo gettext("Select Volume"); ?></label>
    <select name="volume" id="volume">
      <?php cm_volume_list($module, $volume); ?>
    </select>
    <input type="submit" id="submit" value="Open" class="button"/>
  </p>
  <?php

// Make sure soemthing loads
if (!is_numeric($volume))
{
	$volume = cm_current_issue('volume');
}

$query = "SELECT * FROM cm_issues WHERE issue_volume = $volume ORDER BY issue_number; ";

// Run Query
$result = cm_run_query($query);
$records = $result->GetArray();

if ($result->RecordCount() > 0) {

?>
  <table class="<?php echo $module; ?>-table">
  <thead>
    <tr>
      <th><?php echo gettext("Issue Date"); ?></th>
      <th><?php echo gettext("Issue Number"); ?></th>
      <th><?php echo gettext("Circulation"); ?></th>
      <th><?php echo gettext("Tools"); ?></th>
    </tr>
  </thead>
  <tbody>
<?php

foreach ($records as $record)
{

	$id = $record['id'];
	$date = date('F j, Y', strtotime($record['issue_date']));
	$volume = $record['issue_volume'];
	$number = $record['issue_number'];
	$circulation = $record['issue_circulation'];

    if ($rowclass == 'even') { $rowclass = 'odd'; } else { $rowclass = 'even'; }
  
?>
    <tr class="<?php echo $rowclass; ?>">
      <td><a href="issue-edit.php?id=<?php echo $id; ?>"><?php echo $date; ?></a></td>
      <td><?php echo $number; ?></td>
      <td><?php echo $circulation; ?></td>
      <td class="actionMenu">
        <ul class="center">
          <li class="command-edit"><a href="issue-edit.php?id=<?php echo $id; ?>"><?php echo gettext("Edit"); ?></a></li>
          <li class="command-edit"><a href="article-browse.php?issue=<?php echo $id; ?>"><?php echo gettext("Browse Articles"); ?></a></li>
          <li class="command-edit"><a href="?preview=<?php echo $id; ?>"><?php echo gettext("Live Preview"); ?></a></li>
        </ul>
      </td>
    </tr>

<?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <td class="center" colspan="3"><strong><a href="issue-edit.php?action=new"><?php echo gettext("Add Issue"); ?></a></strong></td>
      <td></td>
    </tr>
  </tfoot>
  </table>
  </fieldset>
</form>
<?php } else { ?>
<p><?php echo gettext("The selected volume is empty."); ?> <a href="issue-edit.php?action=new"><?php echo gettext("Add an issue"); ?></a>.</p>
<?php } ?>
<?php get_cm_footer(); ?>
