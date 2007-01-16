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
if ($_GET["action"] != "") {
	$mode = $_GET["action"];
}

// Key variable
$id = $_GET["id"];

// If action is edit, call edit function
if ($_GET['action'] == "edit") { 
	if ($_POST['id'] != "") {
		// Get posted data
		$name = prep_string($_POST['name']);
		$editor = prep_string($_POST['editor']);
		$editor_title = prep_string($_POST['editor_title']);
		$editor_email = $_POST['editor_email'];
		$url = $_POST['url'];
		$sidebar = prep_string($_POST['sidebar']);
		$priority = $_POST['priority'];
		$feed_image = $_POST['feed_image'];	
		$id	= $_POST['id'];		
		// Run function
		$stat = cm_edit_section($name,$editor,$editor_title,$editor_email,$url,$sidebar,$feed_image,$priority,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_section' function.");
			exit;
		}
	} else {
		cm_error("Did not have a section to load.");
		exit;
	}
}

?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"; ?>">Section Manager</a></h2>
  <?php

// Database Query
$query = "SELECT * FROM cm_sections WHERE id = $id;";

// Run Query
$result  = mysql_query($query, $CM_MYSQL) or die(mysql_error());
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);

$id = $result_array['id'];
$name = $result_array['section_name'];
$url = $result_array['section_url'];
$editor = $result_array['section_editor'];
$editor_title = $result_array['section_editor_title'];
$editor_email = $result_array['section_editor_email'];
$sidebar = $result_array['section_sidebar'];
$feed_image = $result_array['section_feed_image'];
$priority = $result_array['section_priority'];
	
?>
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
    <input type="submit" value="Update Section" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php get_cm_footer(); ?>