<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "submitted-edit";
$pmodule = "submitted-browse";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if (!empty($_GET['action']))
{
	$mode = $_GET['action'];
}

// These will be changed later if needed, set defaults.
$id = $_GET["id"];

// If action is delete, call delete function
if ($mode == "delete" && is_numeric($_POST['delete-id'])) { 
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


$query = "SELECT * FROM cm_submitted ";
$query .= " WHERE id = $id";

$result = cm_run_query($query);

// If empty, error
if ($result->RecordCount() != 1) {
	cm_error("Submitted article does not exist.");
	exit;
}
	
// Define variables
$id = $result->Fields('id');
$issue = $result->Fields('issue_id');
$title = htmlentities($result->Fields('submitted_title'));
$text = htmlentities($result->Fields('submitted_text'));
$keyword = htmlentities($result->Fields('submitted_keyword'));
$author = htmlentities($result->Fields('submitted_author'));
$email = htmlentities($result->Fields('submitted_author_email'));
$major = htmlentities($result->Fields('submitted_author_major'));
$city = htmlentities($result->Fields('submitted_author_city'));
$telephone = htmlentities($result->Fields('submitted_author_telephone'));
$sent = $result->Fields('submitted_sent');
$words = $result->Fields('submitted_words');


get_cm_header();

?>

<h2><a href="<?php echo $pmodule; ?>.php?">Submitted Article Manager</a></h2>
<div class="actionMenu">
<ul>
    <?php if (cm_auth_restrict('article-edit') == "true") { ?>
    <li class="command-preview"><a href="article-edit.php?action=new&amp;submitted_id=<?php echo $id; ?>">Post to Article Manager</a></li>
    <?php } ?>    <?php if (cm_auth_restrict('submitted-delete') == "true") { ?>
    <li class="command-delete"><a href="#delete">Delete</a></li>
    <?php } ?></ul>
</div>

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
if (cm_auth_restrict('submitted-delete') == "true") { ?>
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
