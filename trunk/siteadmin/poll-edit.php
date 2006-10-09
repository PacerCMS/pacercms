<?php include('cm-includes/config.php'); ?>
<?php
// Declare the current module
$module = "poll-edit";
$pmodule = "poll-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if ($_GET["action"] != "") {;
	$mode = $_GET["action"];
};

// Default value for 'volume' field
$volume = $_COOKIE['issue-browse-volume'];

// If action is edit, call edit function
if ($_GET['action'] == "edit") {; 
	if ($_POST['id'] != "") {;
		// Get posted data
		$question = $_POST['question'];
		$r1 = $_POST['r1'];
		$r2 = $_POST['r2'];
		$r3 = $_POST['r3'];		
		$r4 = $_POST['r4'];
		$r5 = $_POST['r5'];
		$r6 = $_POST['r6'];
		$r7 = $_POST['r7'];
		$r8 = $_POST['r8'];
		$r9 = $_POST['r9'];
		$r10 = $_POST['r10'];	
		$article = $_POST['article'];	
		$id	= $_POST['id'];		
		// Run function
		$stat = cm_edit_poll($question,$r1,$r2,$r3,$r4,$r5,$r6,$r7,$r8,$r9,$r10,$article,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {;
			cm_error("Error in 'cm_edit_poll' function.");
			exit;
		};
	} else {;
		cm_error("Did not have a poll question to load.");
		exit;
	};
};

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['question'] != "") {; 
	// Get posted data
	$question = $_POST['question'];
	$r1 = $_POST['r1'];
	$r2 = $_POST['r2'];
	$r3 = $_POST['r3'];		
	$r4 = $_POST['r4'];
	$r5 = $_POST['r5'];
	$r6 = $_POST['r6'];
	$r7 = $_POST['r7'];
	$r8 = $_POST['r8'];
	$r9 = $_POST['r9'];
	$r10 = $_POST['r10'];
	$article = $_POST['article'];		
	$id	= $_POST['id'];
	// Run function
	$stat = cm_add_poll($question,$r1,$r2,$r3,$r4,$r5,$r6,$r7,$r8,$r9,$r10,$article);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {;
		cm_error("Error in 'cm_add_poll' function.");
		exit;
	};
};

if ($mode == "edit") {;
	$id = $_GET["id"];
	$query_CM_Array = "SELECT *";
	$query_CM_Array .= " FROM cm_poll_questions WHERE id = '$id;'";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);	
	if ($totalRows_CM_Array != 1) {;
		cm_error("That poll question cannot be loaded.");
	};	
	$id = $row_CM_Array['id'];
	$question = $row_CM_Array['poll_question'];
	$r1 = $row_CM_Array['poll_response_1'];
	$r2 = $row_CM_Array['poll_response_2'];
	$r3 = $row_CM_Array['poll_response_3'];		
	$r4 = $row_CM_Array['poll_response_4'];
	$r5 = $row_CM_Array['poll_response_5'];
	$r6 = $row_CM_Array['poll_response_6'];
	$r7 = $row_CM_Array['poll_response_7'];
	$r8 = $row_CM_Array['poll_response_8'];
	$r9 = $row_CM_Array['poll_response_9'];
	$r10 = $row_CM_Array['poll_response_10'];
	$article = $row_CM_Array['article_id'];		
}; // End database call if in edit mode.


?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"; ?>">Poll Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Question Editor</legend>
  <div class="sidebar">
    <p>
      <label for="article">Related Article</label>
      <br />
      <input type="text" name="article" id="article" value="<?php echo $article; ?>" />
    </p>
	<?php if ($mode == "edit") {; ?>
	<h4>Audit</h4>
	<p><?php cm_poll_cleanup($id) ?></p>
	<h4>Current Results</h4>
	<?php cm_poll_results($id); ?>
	<?php }; ?>
  </div>
  <p>
    <label for="question">Poll Question</label>
    <br/>
    <textarea name="question" id="question"><?php echo $question; ?></textarea>
  </p>
  <p>
    <label for="r1">Option 1</label>
    <br />
    <input type="text" name="r1" id="r1" value="<?php echo $r1; ?>" />
  </p>
  <p>
    <label for="r2">Option 2</label>
    <br />
    <input type="text" name="r2" id="r2" value="<?php echo $r2; ?>" />
  </p>
  <p>
    <label for="r3">Option 3</label>
    <br />
    <input type="text" name="r3" id="r3" value="<?php echo $r3; ?>" />
  </p>
  <p>
    <label for="r4">Option 4</label>
    <br />
    <input type="text" name="r4" id="r4" value="<?php echo $r4; ?>" />
  </p>
  <p>
    <label for="r5">Option 5</label>
    <br />
    <input type="text" name="r5" id="r5" value="<?php echo $r5; ?>" />
  </p>
  <p>
    <label for="r6">Option 6</label>
    <br />
    <input type="text" name="r6" id="r6" value="<?php echo $r6; ?>" />
  </p>
  <p>
    <label for="r7">Option 7</label>
    <br />
    <input type="text" name="r7" id="r7" value="<?php echo $r7; ?>" />
  </p>
  <p>
    <label for="r8">Option 8</label>
    <br />
    <input type="text" name="r8" id="r8" value="<?php echo $r8; ?>" />
  </p>
  <p>
    <label for="r9">Option 9</label>
    <br />
    <input type="text" name="r9" id="r9" value="<?php echo $r9; ?>" />
  </p>
  <p>
    <label for="r10">Option 10</label>
    <br />
    <input type="text" name="r10" id="r10" value="<?php echo $r10; ?>" />
  </p>
  <p>
    <?php if ($mode == "new") {; ?>
    <input type="submit" value="Add Poll" name="submit" id="submit" class="button" />
    <?php }; if ($mode == "edit") {; ?>
    <input type="submit" value="Update Poll" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php }; ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
