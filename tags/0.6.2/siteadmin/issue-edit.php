<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "issue-edit";
$pmodule = "issue-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if (!empty($_GET['action']))
{
	$mode = $_GET['action'];
}

// These will be changed later if needed, set defaults.
$volume = $_COOKIE['issue-browse-volume'];
$id = $_GET["id"];

// If action is edit, call edit function
if ($mode == "edit") { 
	if (is_numeric($_POST['id']))
	{
		$issue_year = $_POST['issue-year'];
		$issue_month = $_POST['issue-month'];
		$issue_day = $_POST['issue-day'];
		$issue['date'] = date('Y-m-d', mktime(0,0,0,$issue_month,$issue_day,$issue_year));
		$issue['volume'] = $_POST['volume'];
		$issue['number'] = $_POST['number'];
		$issue['circulation'] = $_POST['circulation'];
		$issue['online_only'] = $_POST['online_only'];
		$id	= $_POST['id'];		

		$stat = cm_edit_issue($issue,$id);
		if ($stat)
		{
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_issue' function.");
			exit;
		}
	} elseif (!empty($_POST)) {
		cm_error("Did not have a issue to load.");
		exit;
	}
}

// If action is new, call add function
if ($mode == "new" && $_POST['volume'] != "")
{ 
	$issue_year = $_POST['issue-year'];
	$issue_month = $_POST['issue-month'];
	$issue_day = $_POST['issue-day'];
	$issue['date'] = date('Y-m-d', mktime(0,0,0,$issue_month,$issue_day,$issue_year));
	$issue['volume'] = $_POST['volume'];
	$issue['number'] = $_POST['number'];
	$issue['circulation'] = $_POST['circulation'];
	$issue['online_only'] = $_POST['online_only'];

	$stat = cm_add_issue($issue);
	if ($stat)
	{
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {
		cm_error("Error in 'cm_add_issue' function.");
		exit;
	}
}

// Only call database if in edit mode.
if ($mode == "edit" && is_numeric($id))
{
	$query = "SELECT *,";
	$query .= " DATE_FORMAT(issue_date, '%Y') AS issue_year,";
	$query .= " DATE_FORMAT(issue_date, '%m') AS issue_month,";
	$query .= " DATE_FORMAT(issue_date, '%d') AS issue_day";
	$query .= " FROM cm_issues WHERE id = '$id;'";
	
	$result = cm_run_query($query);	
	
	if ($result->RecordCount() != 1)
	{
		cm_error("Could not load issue.");
	}	
	$id = $result->Fields('id');
	$issue_day =  $result->Fields('issue_day');
	$issue_month = $result->Fields('issue_month');
	$issue_year = $result->Fields('issue_year');
	$volume = $result->Fields('issue_volume');
	$number = $result->Fields('issue_number');
	$circulation = $result->Fields('issue_circulation');
	$online_only = $result->Fields('online_only');
}

if (!is_numeric($issue_day)) { $issue_day = date('d'); }
if (!is_numeric($issue_month)) { $issue_month = date('m'); }
if (!is_numeric($issue_year)) { $issue_year = date('Y'); }


get_cm_header();

?>


<h2><a href="<?php echo "$pmodule.php"; ?>">Issue Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Issue Editor</legend>
  <div class="sidebar">
    <?
	// Build Calendar
	$d = getdate(time());
	if ($_GET['month'] == "") { $month = $d["mon"]; } else { $month = $_GET['month'];	}
	if ($_GET['year'] == "") {  $year = $d["year"]; } else { $year = $_GET['year']; }
	$cal = new Calendar;
	echo $cal->getMonthView($month, $year);
?>
  </div>
  <p>
    <label for="date">Publication Date</label>
    <br />
    <select name="issue-month" id="issue-month">
      <option value="01" <?php if ($issue_month == "01") { echo "selected"; } ?>>January</option>
      <option value="02" <?php if ($issue_month == "02") { echo "selected"; } ?>>February</option>
      <option value="03" <?php if ($issue_month == "03") { echo "selected"; } ?>>March</option>
      <option value="04" <?php if ($issue_month == "04") { echo "selected"; } ?>>April</option>
      <option value="05" <?php if ($issue_month == "05") { echo "selected"; } ?>>May</option>
      <option value="06" <?php if ($issue_month == "06") { echo "selected"; } ?>>June</option>
      <option value="07" <?php if ($issue_month == "07") { echo "selected"; } ?>>July</option>
      <option value="08" <?php if ($issue_month == "08") { echo "selected"; } ?>>August</option>
      <option value="09" <?php if ($issue_month == "09") { echo "selected"; } ?>>September</option>
      <option value="10" <?php if ($issue_month == "10") { echo "selected"; } ?>>October</option>
      <option value="11" <?php if ($issue_month == "11") { echo "selected"; } ?>>November</option>
      <option value="12" <?php if ($issue_month == "12") { echo "selected"; } ?>>December</option>
    </select>
    <select name="issue-day" id="issue-day">
<?php
// Select Day
$day = 1;
while ($day <= 31)
{
    if ($day == $issue_day) { $selected = 'selected="selected"'; } else { unset($selected); }
    echo "      <option value=\"$day\" $selected>$day</option>\n";
    $day++;
}
?>
    </select>
    <select name="issue-year" id="issue-year">
<?php
// Select Year
$year = 1900;
$year_stop = 2025;
while ($year <= $year_stop)
{
    if ($year == $issue_year) { $selected = 'selected="selected"'; } else { unset($selected); }
    echo "      <option value=\"$year\" $selected>$year</option>\n";
    $year++;
}
?>
    </select>
  </p>
  <p>
    <label for="volume">Volume</label>
    <br />
    <input type="text" name="volume" id="volume" value="<?php echo $volume; ?>" class="text" />
  </p>
  <p>
    <label for="number">Issue Number</label>
    <br />
    <input type="text" name="number" id="number" value="<?php echo $number; ?>" class="text" />
  </p>
  <p>
    <label for="circulation">Circulation</label>
    <br />
    <input type="text" name="circulation" id="circulation" value="<?php echo $circulation; ?>" class="text" />
  </p>
  <p>
    <label for="online_only">Online Only?</label>
    <br />
    <input type="radio" name="online_only" id="online_only_yes" value="1" class="radio" <?php if ($online_only == '1') { echo "checked"; } ?> />
    <label for="online_only_yes">Yes</label>
    <br />
    <input type="radio" name="online_only" id="online_only_no" value="0" class="radio" <?php if ($online_only == '0') { echo "checked"; } ?> />
    <label for="online_only_no">No</label>
    <br />
  </p>
  <p>
    <?php if ($mode == "new") { ?>
    <input type="submit" value="Add Issue" name="submit" id="submit" class="button" />
    <?php } if ($mode == "edit") { ?>
    <input type="submit" value="Update Issue" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php } ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
