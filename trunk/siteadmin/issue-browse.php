<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "issue-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);


if ($_COOKIE["$module-volume"] == "") {;
	setcookie("$module-volume", cm_current_issue('volume')); // Current Volume
	$volume = cm_current_issue('volume');
} else {;
	$volume = $_COOKIE["$module-volume"];
};

// If changing publishing settings
if ($_POST['id'] != "") {;
	// Get posted data
	$id = $_POST['id'];
	$current_issue = $_POST['current_issue'];
	$next_issue = $_POST['next_issue'];		
	// Run function
	$stat = cm_edit_publish_settings($current_issue,$next_issue,$id);
	if ($stat == 1) {
		header("Location: $module.php?msg=publish-updated");
		exit;
	} else {;
		cm_error("Error in 'cm_edit_settings' function.");
		exit;
	};
};

// Volume for display
if ($_GET['volume'] != "") {;
	setcookie("$module-volume", $_GET['volume']);
	header("Location: $module.php");
	exit;
};

class MyCalendar extends Calendar
{
    function getCalendarLink($month, $year)
    {
        // Redisplay the current page, but with some parameters
        // to set the new month and year
        $s = getenv('PHP_SELF');
        return "$s?month=$month&year=$year";
    }
}


?>
<?php get_cm_header(); ?>

<h2>Issue Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") {; echo "<p class=\"systemMessage\">Issue added.</p>"; };
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Issue updated.</p>"; };
if ($msg == "publish-updated") {; echo "<p class=\"systemMessage\">Publish settings updated.</p>"; };
?>
<form action="<?php echo "$module.php"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Publication Settings</legend>
  <div class="sidebar">
    <?
	if ($_GET['month'] == "") {; $month = date('d'); } else {; $month = $_GET['month']; };
	if ($_GET['year'] == "") {; $year = date('Y'); } else {; $year = $_GET['year']; };
	$cal = new MyCalendar;
	echo $cal->getMonthView($month, $year);
?>
  </div>
  <p>
    <label for="current_issue">Current Issue</label>
    <br />
    <select name="current_issue" id="current_issue">
      <?php cm_issue_list($module, cm_current_issue('id')); ?>
    </select>
    <strong>Stored: <?php echo cm_current_issue('date'); ?></strong> </p>
  <p>
    <label for="next_issue">Next Issue</label>
    <br />
    <select name="next_issue" id="next_issue">
      <?php cm_issue_list($module, cm_next_issue('id')); ?>
    </select>
    <strong>Stored: <?php echo cm_next_issue('date'); ?></strong> </p>
  <p>
    <input type="submit" value="Update Settings" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo cm_get_settings('id'); ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Browse Issue Archive</legend>
  <p>
    <label for="volume">Select Volume</label>
    <select name="volume" id="volume">
      <?php cm_volume_list($module, $volume); ?>
    </select>
    <input type="submit" id="submit" value="Open" class="button"/>
  </p>
  <?php

// Make sure soemthing loads
if ($volume == "") {;
	$volume = cm_current_issue('volume');
};

// Database Query
$query_CM_Array = "SELECT * FROM cm_issues WHERE issue_volume = \"$volume\" ORDER BY issue_number;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

if ($totalRows_CM_Array > 0) {;

?>
  <table class="<?php echo $module; ?>-table">
    <tr>
      <th>Issue Date</th>
      <th>Issue Number</th>
      <th>Circulation</th>
      <th>Tools</th>
    </tr>
    <?php

do {;

	$id = $row_CM_Array['id'];
	$date = $row_CM_Array['issue_date'];
	$volume = $row_CM_Array['issue_volume'];
	$number = $row_CM_Array['issue_number'];
	$circulation = $row_CM_Array['issue_circulation'];

  
?>
    <tr>
      <td><a href="issue-edit.php?id=<?php echo $id; ?>"><?php echo $date; ?></a></td>
      <td><?php echo $number; ?></td>
      <td><?php echo $circulation; ?></td>
      <td class="actionMenu">
        <ul class="center">
          <li class="command-edit"><a href="issue-edit.php?id=<?php echo $id; ?>">Edit</a></li>
          <li class="command-edit"><a href="article-browse.php?issue=<?php echo $id; ?>">Browse
              Articles</a></li>
        </ul>
      </td>
    </tr>
    <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
    <tr>
      <td class="center" colspan="3"><strong><a href="issue-edit.php?action=new">Add
            an Issue</a></strong></td>
      <td></td>
    </tr>
  </table>
  </fieldset>
</form>
<?php } else {; ?>
<p>This selected volume is empty. <a href="issue-edit.php?action=new">Add an
    issue</a>.</p>
<?php }; ?>
<?php get_cm_footer(); ?>
