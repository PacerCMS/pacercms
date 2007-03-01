<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "submitted-edit";
$pmodule = "submitted-browse";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if ($_GET["action"] != "") {
	$mode = $_GET["action"];
}

// These will be changed later if needed, set defaults.
$id = $_GET["id"];

// If action is delete, call delete function
if ($_GET['action'] == "delete" && $_POST['delete-id'] != "") { 
	$id = $_POST['delete-id'];
	// Run function
	$stat = cm_delete_submitted($id);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {
		cm_error("Error in 'cm_delete_submitted' function.");
		exit;
	}	
}


// Query
$query = "SELECT * FROM cm_submitted ";
$query .= " WHERE id = $id";
// Security Measure

// Run Query
$result = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);

// If Array comes back empty, produce error
if ($result_row_count != 1) {
	cm_error("Submitted article does not exist.");
	exit;
}
	
// Define variables
$id = $result_array['id'];
$issue = $result_array['issue_id'];
$title = $result_array['submitted_title'];
$text = $result_array['submitted_text'];
$keyword = $result_array['submitted_keyword'];
$author = $result_array['submitted_author'];
$email = $result_array['submitted_author_email'];
$major = $result_array['submitted_author_major'];
$city = $result_array['submitted_author_city'];
$telephone = $result_array['submitted_author_telephone'];
$sent = $result_array['submitted_sent'];
$words = $result_array['submitted_words'];


get_cm_header();

?>

<h2><a href="<?php echo $pmodule; ?>.php?">Submitted Article Manager</a></h2>
<fieldset class="<?php echo "$module-preview" ?>">
<legend>Story Preview</legend>
<h3><?php echo $title; ?></h3>
<p>By <strong><?php echo $author; ?></strong></p>
<?php echo autop($text); ?>
<h4>Contact Information</h4>

<p>
<?php echo $author; ?><br />
<?php echo $city; ?><br />
<?php echo $major; ?></p>
<p><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br />
<?php echo $telephone; ?>
</p>

<p><strong>Word Count:</strong> <?php echo $words; ?><br>
<strong>Keyword:</strong> <?php echo $keyword; ?></p>
</fieldset>

<?php
// Show preview if not an add form
if ($show_submitted_delete == "true") { ?>
<h2>Delete Submitted Article <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p>Are you sure you want to delete this submitted article?</p>
    <input type="submit" name="submit-delete" id="submit-delete" value="Yes" class="button" />
    <input type="button" name="cancel-delete" id="cancel-delete" value="Cancel" onClick="javascript:toggleLayer('deleteArticle');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php } ?>

<?php get_cm_footer(); ?>
