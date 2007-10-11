<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "poll-edit";
$pmodule = "poll-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if ($_GET["action"] != "") {
	$mode = $_GET["action"];
}

// If action is delete, call delete function
if ($_GET['action'] == "delete" && $_POST['delete-id'] != "") { 
	$id = $_POST['delete-id'];
	// Run function
	$stat = cm_delete_poll($id);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {
		cm_error("Error in 'cm_delete_poll' function.");
		exit;
	}	
}

// Default value for 'volume' field
$volume = $_COOKIE['issue-browse-volume'];

// If action is edit, call edit function
if ($_GET['action'] == "edit") { 
	if ($_POST['id'] != "") {
		// Get posted data
		$question = prep_string($_POST['question']);
		$r1 = prep_string($_POST['r1']);
		$r2 = prep_string($_POST['r2']);
		$r3 = prep_string($_POST['r3']);		
		$r4 = prep_string($_POST['r4']);
		$r5 = prep_string($_POST['r5']);
		$r6 = prep_string($_POST['r6']);
		$r7 = prep_string($_POST['r7']);
		$r8 = prep_string($_POST['r8']);
		$r9 = prep_string($_POST['r9']);
		$r10 = prep_string($_POST['r10']);	
		$article = $_POST['article'];	
		$id	= $_POST['id'];		
		// Run function
		$stat = cm_edit_poll($question,$r1,$r2,$r3,$r4,$r5,$r6,$r7,$r8,$r9,$r10,$article,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_poll' function.");
			exit;
		}
	} else {
		cm_error("Did not have a poll question to load.");
		exit;
	}
}

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['question'] != "") { 
	// Get posted data
	$question = prep_string($_POST['question']);
	$r1 = prep_string($_POST['r1']);
	$r2 = prep_string($_POST['r2']);
	$r3 = prep_string($_POST['r3']);		
	$r4 = prep_string($_POST['r4']);
	$r5 = prep_string($_POST['r5']);
	$r6 = prep_string($_POST['r6']);
	$r7 = prep_string($_POST['r7']);
	$r8 = prep_string($_POST['r8']);
	$r9 = prep_string($_POST['r9']);
	$r10 = prep_string($_POST['r10']);
	$article = $_POST['article'];		
	$id	= $_POST['id'];
	// Run function
	$stat = cm_add_poll($question,$r1,$r2,$r3,$r4,$r5,$r6,$r7,$r8,$r9,$r10,$article);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {
		cm_error("Error in 'cm_add_poll' function.");
		exit;
	}
}

if ($mode == "edit") {
	$id = $_GET["id"];
	$query = "SELECT *";
	$query .= " FROM cm_poll_questions WHERE id = '$id;'";
	$result = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
	$result_array  = mysql_fetch_assoc($result);
	$result_row_count = mysql_num_rows($result);	
	if ($result_row_count != 1) {
		cm_error("That poll question cannot be loaded.");
	}	
	$id = $result_array['id'];
	$question = $result_array['poll_question'];
	$r1 = $result_array['poll_response_1'];
	$r2 = $result_array['poll_response_2'];
	$r3 = $result_array['poll_response_3'];		
	$r4 = $result_array['poll_response_4'];
	$r5 = $result_array['poll_response_5'];
	$r6 = $result_array['poll_response_6'];
	$r7 = $result_array['poll_response_7'];
	$r8 = $result_array['poll_response_8'];
	$r9 = $result_array['poll_response_9'];
	$r10 = $result_array['poll_response_10'];
	$article = $result_array['article_id'];		
} // End database call if in edit mode.


get_cm_header();

?>

<h2><a href="<?php echo "$pmodule.php"; ?>">Poll Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Question Editor</legend>
  <div class="sidebar">
    <p>
      <label for="article">Related Article ID</label>
      <br />
      <input type="text" name="article" id="article" value="<?php echo $article; ?>" />
    </p>
	<?php if ($mode == "edit") { ?>
	<h4>Audit</h4>
	<p><?php cm_poll_cleanup($id) ?></p>
	<h4>Current Results</h4>
	<?php cm_poll_results($id); ?>
	<?php } ?>
  </div>
  <p>
    <label for="question">Poll Question</label>
    <br/>
    <textarea name="question" id="question"><?php echo $question; ?></textarea>
  </p>
  <p>
    <label for="r1">Option 1</label>
    <br />
    <input type="text" name="r1" id="r1" value="<?php echo htmlentities($r1, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r2">Option 2</label>
    <br />
    <input type="text" name="r2" id="r2" value="<?php echo htmlentities($r2, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r3">Option 3</label>
    <br />
    <input type="text" name="r3" id="r3" value="<?php echo htmlentities($r3, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r4">Option 4</label>
    <br />
    <input type="text" name="r4" id="r4" value="<?php echo htmlentities($r4, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r5">Option 5</label>
    <br />
    <input type="text" name="r5" id="r5" value="<?php echo htmlentities($r5, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r6">Option 6</label>
    <br />
    <input type="text" name="r6" id="r6" value="<?php echo htmlentities($r6, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r7">Option 7</label>
    <br />
    <input type="text" name="r7" id="r7" value="<?php echo htmlentities($r7, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r8">Option 8</label>
    <br />
    <input type="text" name="r8" id="r8" value="<?php echo htmlentities($r8, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r9">Option 9</label>
    <br />
    <input type="text" name="r9" id="r9" value="<?php echo htmlentities($r9, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r10">Option 10</label>
    <br />
    <input type="text" name="r10" id="r10" value="<?php echo htmlentities($r10, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <?php if ($mode == "new") { ?>
    <input type="submit" value="Add Poll" name="submit" id="submit" class="button" />
    <?php } if ($mode == "edit") { ?>
    <input type="submit" value="Update Poll" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php } ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<h2>Delete Poll <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p>Are you sure you want to delete this poll and associated ballots?</p>
    <input type="submit" name="submit-delete" id="submit-delete" value="Yes" class="button" />
    <input type="button" name="cancel-delete" id="cancel-delete" value="Cancel" onClick="javascript:toggleLayer('deleteRecord');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php get_cm_footer(); ?>
