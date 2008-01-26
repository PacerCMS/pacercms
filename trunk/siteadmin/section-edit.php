<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "section-edit";
$pmodule = "section-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if (!empty($_GET['action']))
{
	$mode = $_GET['action'];
}

// These will be changed later if needed, set defaults.
$id = $_GET["id"];

// If action is edit, call edit function
if ($mode == "edit") { 
	if (is_numeric($_POST['id']))
	{
		$section['name'] = prep_string($_POST['name']);
		$section['editor'] = prep_string($_POST['editor']);
		$section['editor_title'] = prep_string($_POST['editor_title']);
		$section['editor_email'] = prep_string($_POST['editor_email']);
		$section['url'] = prep_string($_POST['url']);
		$section['sidebar'] = prep_string($_POST['sidebar']);
		$section['priority'] = $_POST['priority'];
		$section['feed_image'] = $_POST['feed_image'];	
		$id	= $_POST['id'];		

		$stat = cm_edit_section($section,$id);

		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_section' function.");
			exit;
		}
	} elseif (!empty($_POST)) {
		cm_error("Did not have a section to load.");
		exit;
	}

}

// If action is new, call add function
if ($mode == "new" && $_POST['name'] != "")
{ 
	$section['name'] = prep_string($_POST['name']);
	$section['editor'] = prep_string($_POST['editor']);
	$section['editor_title'] = prep_string($_POST['editor_title']);
	$section['editor_email'] = prep_string($_POST['editor_email']);
	$section['url'] = prep_string($_POST['url']);
	$section['sidebar'] = prep_string($_POST['sidebar']);
	$section['priority'] = $_POST['priority'];
	$section['feed_image'] = $_POST['feed_image'];	

	$stat = cm_add_section($name,$editor,$editor_title,$editor_email,$url,$sidebar,$feed_image,$priority);

	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {
		cm_error("Error in 'cm_add_section' function.");
		exit;
	}
}

// If action is delete, call delete function
if ($mode == "delete" && is_numeric($_POST['delete-id']))
{ 
	$id = $_POST['delete-id'];
	$move = $_POST['move-id'];

	$stat = cm_delete_section($id,$move);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {
		cm_error("Error in 'cm_delete_section' function.");
		exit;
	}	
}

// Only call database if in edit mode.
if ($mode == "edit" && is_numeric($id))
{
    $query = "SELECT * FROM cm_sections WHERE id = $id; ";
    
    $result = cm_run_query($query);
    
    $id = $result->Fields('id');
    $name = $result->Fields('section_name');
    $url = $result->Fields('section_url');
    $editor = $result->Fields('section_editor');
    $editor_title = $result->Fields('section_editor_title');
    $editor_email = $result->Fields('section_editor_email');
    $sidebar = $result->Fields('section_sidebar');
    $feed_image = $result->Fields('section_feed_image');
    $priority = $result->Fields('section_priority');

}

get_cm_header();

?>

<h2><a href="<?php echo "$pmodule.php"; ?>">Section Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Section Editor</legend>
  <p>
    <label for="name">Section Name</label>
    <br />
    <input type="text" name="name" id="name" value="<?php echo $name; ?>" class="text" />
  </p>
  <p>
    <label for="url">URL</label>
    <br />
    <input type="text" name="url" id="url" value="<?php echo $url; ?>" class="text" />
  </p>
  <p>
    <label for="editor">Editor Name</label>
    <br />
    <input type="text" name="editor" id="editor" value="<?php echo $editor; ?>" class="text" />
  </p>
  <p>
    <label for="editor_title">Editor Title</label>
    <br />
    <input type="text" name="editor_title" id="editor_title" value="<?php echo $editor_title; ?>" class="text" />
  </p>
  <p>
    <label for="editor_email">Editor Email</label>
    <br />
    <input type="text" name="editor_email" id="editor_email" value="<?php echo $editor_email; ?>" class="text" />
  </p>
  <p>
    <label for="feed_image">Feed Image URL</label>
    <br />
    <input type="text" name="feed_image" id="feed_image" value="<?php echo $feed_image; ?>" class="text" />
  </p>
  <p>
    <label for="sidebar">Sidebar Content</label>
    <br />
    <span class="article-edit-tags"><script type="text/javascript">edToolbar();</script></span>
    <textarea name="sidebar" id="sidebar" rows="10"><?php echo $sidebar; ?></textarea>
    <script type="text/javascript">var edCanvas = document.getElementById('sidebar');</script>
  </p>
  <p>
    <label for="priority">Section Priority</label>
    <br />
    <input type="text" name="priority" id="priority" value="<?php echo $priority; ?>" class="text" />
  </p>   
  <p>
  
<?php switch($mode) { case 'new': ?>
    <input type="submit" value="Add Section" name="update" id="update" class="button" />
<?php break; case 'edit': ?>
    <input type="submit" value="Update Section" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
<?php break; default: break; } ?>

    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>

<?php if ($mode != "new") { ?>
<h2>Delete Article <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p><label for="move-id">Move affected articles to<label> <select name="move-id" id="move-id">
    <?php cm_section_list($module, null, $id); ?>
    </select>
    <p>Are you sure you want to delete this section?</p>
    <input type="submit" name="submit-delete" id="submit-delete" value="Yes" class="button" />
    <input type="button" name="cancel-delete" id="cancel-delete" value="Cancel" onClick="javascript:toggleLayer('deleteArticle');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php } ?>

<?php get_cm_footer(); ?>