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
if (!empty($_GET['action']))
{
	$mode = $_GET['action'];
}

// If action is delete, call delete function
if ($mode == "delete" && is_numeric($_POST['delete-id']))
{ 
	$id = $_POST['delete-id'];
	
	$stat = cm_delete_poll($id);
	
	if ($stat) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {
		cm_error(gettext("Error in 'cm_delete_poll' function."));
		exit;
	}	
}

// These will be changed later if needed, set defaults.
$volume = $_COOKIE['issue-browse-volume'];
$id = $_GET["id"];

// If action is edit, call edit function
if ($mode == "edit")
{ 
	if (is_numeric($_POST['id']))
	{
		$poll['question'] = prep_string($_POST['question']);
		$poll['r1'] = prep_string($_POST['r1']);
		$poll['r2'] = prep_string($_POST['r2']);
		$poll['r3'] = prep_string($_POST['r3']);		
		$poll['r4'] = prep_string($_POST['r4']);
		$poll['r5'] = prep_string($_POST['r5']);
		$poll['r6'] = prep_string($_POST['r6']);
		$poll['r7'] = prep_string($_POST['r7']);
		$poll['r8'] = prep_string($_POST['r8']);
		$poll['r9'] = prep_string($_POST['r9']);
		$poll['r10'] = prep_string($_POST['r10']);	
		$poll['article_id'] = $_POST['article'];	
		$id	= $_POST['id'];		

		$stat = cm_edit_poll($poll,$id);
		
		if ($stat) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error(gettext("Error in 'cm_edit_poll' function."));
			exit;
		}
	} elseif (!empty($_POST)) {
		cm_error(gettext("Did not have a poll question to load."));
		exit;
	}
}

// If action is new, call add function
if ($mode == "new" && !empty($_POST['question']))
{ 
	$poll['question'] = prep_string($_POST['question']);
	$poll['r1'] = prep_string($_POST['r1']);
	$poll['r2'] = prep_string($_POST['r2']);
	$poll['r3'] = prep_string($_POST['r3']);		
	$poll['r4'] = prep_string($_POST['r4']);
	$poll['r5'] = prep_string($_POST['r5']);
	$poll['r6'] = prep_string($_POST['r6']);
	$poll['r7'] = prep_string($_POST['r7']);
	$poll['r8'] = prep_string($_POST['r8']);
	$poll['r9'] = prep_string($_POST['r9']);
	$poll['r10'] = prep_string($_POST['r10']);	
	$poll['article_id'] = $_POST['article'];		

	$stat = cm_add_poll($poll);
	
	if ($stat) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {
		cm_error(gettext("Error in 'cm_add_poll' function."));
		exit;
	}
}

// Only call database if in edit mode.
if ($mode == "edit" && is_numeric($id))
{
	$query = "SELECT * FROM cm_poll_questions WHERE id = $id; ";
	$result = cm_run_query($query);
	
	if ($result->RecordCount() != 1)
	{
		cm_error(gettext("That poll question cannot be loaded."));
	}	
	
	$id = $result->Fields('id');
	$question = $result->Fields('poll_question');
	$r1 = $result->Fields('poll_response_1');
	$r2 = $result->Fields('poll_response_2');
	$r3 = $result->Fields('poll_response_3');		
	$r4 = $result->Fields('poll_response_4');
	$r5 = $result->Fields('poll_response_5');
	$r6 = $result->Fields('poll_response_6');
	$r7 = $result->Fields('poll_response_7');
	$r8 = $result->Fields('poll_response_8');
	$r9 = $result->Fields('poll_response_9');
	$r10 = $result->Fields('poll_response_10');
	$article = $result->Fields('article_id');
			
}


get_cm_header();

?>

<h2><a href="<?php echo "$pmodule.php"; ?>"><?php echo gettext("Poll Manager"); ?></a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo gettext("Question Editor"); ?></legend>
  <div class="sidebar">
    <p>
      <label for="article"><?php echo gettext("Related Article ID"); ?></label>
      <br />
      <input type="text" name="article" id="article" value="<?php echo $article; ?>" />
    </p>
<?php if ($mode == "edit") { ?>
	<h4><?php echo gettext("Audit"); ?></h4>
	<p style="width:200px:"><?php cm_poll_cleanup($id) ?></p>
	<h4><?php echo gettext("Current Results"); ?></h4>
	<?php cm_poll_results($id); ?>
<?php } ?>
  </div>
  <p>
    <label for="question"><?php echo gettext("Poll Question"); ?></label>
    <br/>
    <textarea name="question" id="question"><?php echo $question; ?></textarea>
  </p>
  <p>
    <label for="r1"><?php echo gettext("Option 1"); ?></label>
    <br />
    <input type="text" name="r1" id="r1" value="<?php echo htmlentities($r1, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r2"><?php echo gettext("Option 2"); ?></label>
    <br />
    <input type="text" name="r2" id="r2" value="<?php echo htmlentities($r2, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r3"><?php echo gettext("Option 3"); ?></label>
    <br />
    <input type="text" name="r3" id="r3" value="<?php echo htmlentities($r3, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r4"><?php echo gettext("Option 4"); ?></label>
    <br />
    <input type="text" name="r4" id="r4" value="<?php echo htmlentities($r4, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r5"><?php echo gettext("Option 5"); ?></label>
    <br />
    <input type="text" name="r5" id="r5" value="<?php echo htmlentities($r5, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r6"><?php echo gettext("Option 6"); ?></label>
    <br />
    <input type="text" name="r6" id="r6" value="<?php echo htmlentities($r6, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r7"><?php echo gettext("Option 7"); ?></label>
    <br />
    <input type="text" name="r7" id="r7" value="<?php echo htmlentities($r7, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r8"><?php echo gettext("Option 8"); ?></label>
    <br />
    <input type="text" name="r8" id="r8" value="<?php echo htmlentities($r8, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r9"><?php echo gettext("Option 9"); ?></label>
    <br />
    <input type="text" name="r9" id="r9" value="<?php echo htmlentities($r9, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <label for="r10"><?php echo gettext("Option 10"); ?></label>
    <br />
    <input type="text" name="r10" id="r10" value="<?php echo htmlentities($r10, ENT_QUOTES, 'UTF-8'); ?>" class="text" />
  </p>
  <p>
    <?php if ($mode == "new") { ?>
    <input type="submit" value="<?php echo gettext("Add Poll"); ?>" name="submit" id="submit" class="button" />
    <?php } if ($mode == "edit") { ?>
    <input type="submit" value="<?php echo gettext("Update Poll"); ?>" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php } ?>
    <input type="button" value="<?php echo gettext("Cancel"); ?>" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<h2><?php echo gettext("Delete Poll"); ?> <a href="javascript:toggleLayer('deleteRecord');" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend><?php echo gettext("Confirm Delete"); ?></legend>
    <p><?php echo gettext("Are you sure you want to delete this poll and associated ballots?"); ?></p>
    <input type="submit" name="submit-delete" id="submit-delete" value="<?php echo gettext("Delete"); ?>" class="button" />
    <input type="button" name="cancel-delete" id="cancel-delete" value="<?php echo gettext("Cancel"); ?>" onClick="toggleLayer('deleteRecord');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php get_cm_footer(); ?>
