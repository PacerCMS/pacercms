<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "article-media";
$pmodule = "article-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// If action is delete, call delete function
if ($_GET['action'] == "delete" && $_POST['delete-id'] != "") { 
	$stat = cm_delete_media($pmodule, $_POST['delete-id']);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=media-deleted");
		exit;
	} else {
		cm_error("Error in 'cm_delete_media' function.");
		exit;
	}
}

// Change mode based on query string
if ($_GET["action"] != "") {
	$mode = $_GET["action"];
}

// Key variable
$id = $_GET["id"];
$article_id = $_GET['article_id'];

// If action is edit, call edit function
if ($_GET['action'] == "edit") { 
	if ($_POST['id'] != "") {
		// Get posted data
		$article_id = $_POST['article_id'];
		$title = prep_string($_POST['title']);
		$src = prep_string($_POST['src']);
		$type = prep_string($_POST['type']);
		$caption = prep_string($_POST['caption']);
		$credit = prep_string($_POST['credit']);
		$id	= $_POST['id'];		
		// Run function
		$stat = cm_edit_media($article_id,$title,$src,$type,$caption,$credit,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=media-updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_media' function.");
			exit;
		}
	} else {
		cm_error("Did not have a section to load.");
		exit;
	}
}

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['article_id'] != "") { 
	// Get posted data
	$article_id = $_POST['article_id'];
	$title = prep_string($_POST['title']);
	$src = prep_string($_POST['src']);
	$type = prep_string($_POST['type']);
	$caption = prep_string($_POST['caption']);
	$credit = prep_string($_POST['credit']);
	// Run function
	$stat = cm_add_media($article_id,$title,$src,$type,$caption,$credit);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=media-added");
		exit;
	} else {
		cm_error("Error in 'cm_add_media' function.");
		exit;
	}
}
?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"; ?>">Media Manager</a></h2>
<?php

// Database Query
$query = "SELECT * FROM cm_media WHERE id = '$id;'";

// Run Query
$result  = mysql_query($query, $CM_MYSQL) or die(mysql_error());
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);

if ($result_row_count == 1) {

	do {
		$id = $result_array['id'];
		$article_id = $result_array['article_id'];
		$title = $result_array['media_title'];
		$src = $result_array['media_src'];
		$type = $result_array['media_type'];
		$caption = $result_array['media_caption'];
		$credit = $result_array['media_credit'];
	} while ($result_array = mysql_fetch_assoc($result));

}

?>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Media Editor</legend>
  <p>
    <label for="title">Title / Description</label>
    <br />
    <input type="text" name="title" id="title" value="<?php echo $title; ?>" class="text" />
  </p>
  <p>
    <label for="src">Media URL</label>
    <br />
    <input type="text" name="src" id="src" value="<?php echo $src; ?>" class="text" />
  </p>
  <p>
    <label for="type">Type of Media</label>
    <br />
    <select name="type" id="type">
      <option value="jpg" <?php if ($type == 'jpg') { echo "SELECTED"; } ?> >JPEG Image</option>
      <option value="png" <?php if ($type == 'png') { echo "SELECTED"; } ?> >PNG Image</option>
      <option value="gif" <?php if ($type == 'gif') { echo "SELECTED"; } ?> >GIF Image</option>
      <option value="doc" <?php if ($type == 'doc') { echo "SELECTED"; } ?> >Word or RTF Document</option>
      <option value="pdf" <?php if ($type == 'pdf') { echo "SELECTED"; } ?> >PDF Document</option>
      <option value="swf" <?php if ($type == 'swf') { echo "SELECTED"; } ?> >Flash (SWF)</option>
      <option value="url" <?php if ($type == 'url') { echo "SELECTED"; } ?> >Related Link</option>
	</select> <strong>Determines display method.</strong>
  </p>
  <p>
    <label for="caption">Caption</label>
    <br />
    <textarea name="caption" id="caption"><?php echo $caption; ?></textarea>
  </p>
  <p>
    <label for="type">Credit / Source</label>
    <br />
    <input type="text" name="credit" id="credit" value="<?php echo $credit; ?>" class="text" />
  </p>
  <p>
    <?php if ($mode == "new") { ?>
    <input type="submit" value="Add Media" name="submit" id="submit" class="button" />
    <?php } if ($mode == "edit") { ?>
    <input type="submit" value="Update Media" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php } ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
    <input type="hidden" name="article_id" id="article_id" value="<?php echo $article_id; ?>" />
  </p>
  </fieldset>
</form>
<?php
// Show preview if not an add form
if ($mode != "new") { ?>
<h2><a name="preview"></a>Preview Media</h2>
<fieldset class="<?php echo "$module-preview" ?>">
<legend>Media Preview</legend>
<p><?php cm_display_media($src,$type,$title); ?></p>
<p><?php echo $caption; ?></p>
<p><?php echo $credit; ?></p>
</fieldset>
<h2>Delete Media <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p>Are you sure you want to delete this media item?</p>
    <input type="submit" name="submit_delete" id="submit_delete" value="Yes" class="button" />
    <input type="button" name="cancel_delete" id="cancel_delete" value="Cancel" onClick="javascript:toggleLayer('deleteMedia');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php } ?>
<?php get_cm_footer(); ?>
