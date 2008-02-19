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
	$stat = cm_delete_media($_POST['delete-id']);
	if ($stat) {
		header("Location: $pmodule.php?msg=media-deleted");
		exit;
	} else {
		cm_error(gettext("Error in 'cm_delete_media' function."));
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
	if (!empty($_POST['id'])) {
		// Get posted data
		$media['article_id'] = $_POST['article_id'];
		$media['title'] = prep_string($_POST['title']);
		$media['src'] = prep_string($_POST['src']);
		$media['type'] = prep_string($_POST['type']);
		$media['caption'] = prep_string($_POST['caption']);
		$media['credit'] = prep_string($_POST['credit']);
		$id	= $_POST['id'];		
		// Run function
		$stat = cm_edit_media($media,$id);
		if ($stat) {
			header("Location: $pmodule.php?msg=media-updated");
			exit;
		} else {
			cm_error(gettext("Error in 'cm_edit_media' function."));
			exit;
		}
	} else {
		cm_error(gettext("Did not have media to load."));
		exit;
	}
}

// If action is new, call add function
if ($_GET['action'] == "new" && !empty($_POST['article_id'])) { 
	// Get posted data
	$media['article_id'] = $_POST['article_id'];
	$media['title'] = prep_string($_POST['title']);
	$media['src'] = prep_string($_POST['src']);
	$media['type'] = prep_string($_POST['type']);
	$media['caption'] = prep_string($_POST['caption']);
	$media['credit'] = prep_string($_POST['credit']);
	// Run function
	$stat = cm_add_media($media);
	if ($stat) {
		header("Location: $pmodule.php?msg=media-added");
		exit;
	} else {
		cm_error(gettext("Error in 'cm_add_media' function."));
		exit;
	}
}


get_cm_header();

?>

<h2><a href="<?php echo "$pmodule.php"; ?>"><?php echo gettext("Media Manager"); ?></a></h2>
<?php

if ($mode == 'edit')
{
    $query = "SELECT * FROM cm_media WHERE id = $id; ";
    
    $result = cm_run_query($query);
    
    if ($result->RecordCount() == 1) {
    
    	$id = $result->Fields('id');
    	$article_id = $result->Fields('article_id');
    	$title = $result->Fields('media_title');
    	$src = $result->Fields('media_src');
    	$type = $result->Fields('media_type');
    	$caption = $result->Fields('media_caption');
    	$credit = $result->Fields('media_credit');
    }
}

?>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo gettext("Media Editor"); ?></legend>
  <p>
    <label for="title"><?php echo gettext("Title / Description"); ?></label>
    <br />
    <input type="text" name="title" id="title" value="<?php echo $title; ?>" class="text" />
  </p>
  <p>
    <label for="src"><?php echo gettext("Media URL"); ?></label>
    <br />
    <input type="text" name="src" id="src" value="<?php echo $src; ?>" class="text" />
  </p>
  <p>
    <label for="type"><?php echo gettext("Type of Media"); ?></label>
    <br />
    <select name="type" id="type">
      <option value="jpg" <?php if ($type == 'jpg') { echo "SELECTED"; } ?> ><?php echo gettext("JPEG Image"); ?></option>
      <option value="png" <?php if ($type == 'png') { echo "SELECTED"; } ?> ><?php echo gettext("PNG Image"); ?></option>
      <option value="gif" <?php if ($type == 'gif') { echo "SELECTED"; } ?> ><?php echo gettext("GIF Image"); ?></option>
      <option value="doc" <?php if ($type == 'doc') { echo "SELECTED"; } ?> ><?php echo gettext("Word or RTF Document"); ?></option>
      <option value="pdf" <?php if ($type == 'pdf') { echo "SELECTED"; } ?> ><?php echo gettext("PDF Document"); ?></option>
      <option value="swf" <?php if ($type == 'swf') { echo "SELECTED"; } ?> ><?php echo gettext("Flash (SWF)"); ?></option>
      <option value="url" <?php if ($type == 'url') { echo "SELECTED"; } ?> ><?php echo gettext("Related Link"); ?></option>
	</select> <strong><?php echo gettext("Determines display method."); ?></strong>
  </p>
  <p>
    <label for="caption"><?php echo gettext("Caption"); ?></label>
    <br />
    <textarea name="caption" id="caption"><?php echo $caption; ?></textarea>
  </p>
  <p>
    <label for="type"><?php echo gettext("Credit / Source"); ?></label>
    <br />
    <input type="text" name="credit" id="credit" value="<?php echo $credit; ?>" class="text" />
  </p>
  <p>
    <?php if ($mode == "new") { ?>
    <input type="submit" value="<?php echo gettext("Add Media"); ?>" name="submit" id="submit" class="button" />
    <?php } if ($mode == "edit") { ?>
    <input type="submit" value="<?php echo gettext("Update Media"); ?>" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php } ?>
    <input type="button" value="<?php echo gettext("Cancel"); ?>" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
    <input type="hidden" name="article_id" id="article_id" value="<?php echo $article_id; ?>" />
  </p>
  </fieldset>
</form>
<?php
// Show preview if not an add form
if ($mode != "new") { ?>
<h2><a name="preview"></a><?php echo gettext("Preview Media"); ?></h2>
<fieldset class="<?php echo "$module-preview" ?>">
<legend><?php echo gettext("Media Preview"); ?></legend>
<p><?php cm_display_media($src,$type,$title); ?></p>
<p><?php echo $caption; ?></p>
<p><?php echo $credit; ?></p>
</fieldset>
<h2><?php echo gettext("Delete Media"); ?> <a href="javascript:toggleLayer('deleteRecord');" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend><?php echo gettext("Confirm Delete"); ?></legend>
    <p><?php echo gettext("Are you sure you want to delete this media item?"); ?></p>
    <input type="submit" name="submit_delete" id="submit_delete" value="<?php echo gettext("Delete"); ?>" class="button" />
    <input type="button" name="cancel_delete" id="cancel_delete" value="<?php echo gettext("Cancel"); ?>" onClick="toggleLayer('deleteRecord');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php } ?>
<?php get_cm_footer(); ?>
