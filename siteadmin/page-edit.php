<?php include('cm-includes/config.php'); ?>
<?php

// Declare the current module
$module = "page-edit";
$pmodule = "page-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if ($_GET["action"] != "") {;
	$mode = $_GET["action"];
}

// These will be changed later if needed, set defaults.
$id = $_GET["id"];

// If action is delete, call delete function
if ($_GET['action'] == "delete" && $_POST['delete-id'] != "") {; 
	$id = $_POST['delete-id'];
	// Run function
	$stat = cm_delete_page($id);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {;
		cm_error("Error in 'cm_delete_page' function.");
		exit;
	};	
};

// If action is edit, call edit function
if ($_GET['action'] == "edit") {; 
	if ($_POST['id'] != "") {;
		// Get posted data
		$title = $_POST['title'];
		$short_title = $_POST['short_title'];
		$text = $_POST['text'];
		$side_text = $_POST['side_text'];
		$slug = $_POST['slug'];
		$edited = $_POST['edited'];
		$id	= $_POST['id'];
		
		// Run function
		$stat = cm_edit_page($title,$short_title,$text,$side_text,$slug,$edited,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {;
			cm_error("Error in 'cm_edit_page' function.");
			exit;
		};
	} else {;
		cm_error("Did not have a page to load.");
		exit;
	};
};

// If action is new, call add function
if ($_GET['action'] == "add" && $_POST['text'] != "") {; 
	// Get posted data
	$title = $_POST['title'];
	$short_title = $_POST['short_title'];
	$text = $_POST['text'];
	$side_text = $_POST['side_text'];
	$slug = $_POST['slug'];
	$edited = $_POST['edited'];	
	// Run function
	$stat = cm_add_page($title,$short_title,$text,$side_text,$slug,$edited);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {;
		cm_error("Error in 'cm_add_page' function.");
		exit;
	};
};

// Only call database if in edit mode.
if ($mode == "edit") {;
	
	// Query
	$query_CM_Array = "SELECT * FROM cm_pages ";
	$query_CM_Array .= " WHERE id = $id";
	
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
	// If Array comes back empty, produce error
	if ($totalRows_CM_Array != 1) {;
		cm_error("Page does not exist, or you do not have permission to edit it.");
		exit;
	};
		
	// Define variables
	$id = $row_CM_Array['id'];
	$title = $row_CM_Array['page_title'];
	$short_title = $row_CM_Array['page_short_title'];
	$text = $row_CM_Array['page_text'];
	$side_text = $row_CM_Array['page_side_text'];
	$slug = $row_CM_Array['page_slug'];
	$edited = $row_CM_Array['page_edited'];	
};

?>
<?php get_cm_header(); ?>

<h2><a href="<?php echo $pmodule; ?>.php?">Page Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Page Editor</legend>
  <div class="sidebar">
    <p>
      <label for="slug">Page Slug</label>
      <br />
      <input type="text" name="slug" id="slug" value="<?php echo $slug; ?>" />
    </p>
    <?php if ($mode == "edit") {; ?>
    <p> <strong>Last Edited</strong><br />
      <code><?php echo $edited; ?></code></p>
    <p> <strong>HTACCESS Entry</strong> (copy) <br />
      <input type="text" id="rewrite" value="<?php echo "RewriteRule ^$slug* page.php?id=$id" ?>" /></p>
    <?php }; ?>
  </div>
  <p>
    <label for="title">Title</label>
    <br />
    <input type="text" name="title" id="title" value="<?php echo $title; ?>" class="text" />
  </p>
  <p>
    <label for="short_title">Short Title</label>
    <br />
    <input type="text" name="short_title" id="short_title" value="<?php echo $short_title; ?>" class="text" />
  </p>
  <p>
    <label for="text">Text</label>
    <br />
    <span class="article-edit-tags"><script type="text/javascript">edToolbar();</script></span>
    <textarea name="text" rows="20" id="text"><?php echo $text; ?></textarea>
    <script type="text/javascript">var edCanvas = document.getElementById('text');</script>
  </p>
    <p>
    <label for="side_text">Side Text</label>
    <br />
    <span class="article-edit-tags"><script type="text/javascript">edToolbar();</script></span>
    <textarea name="side_text" rows="20" id="side_text"><?php echo $side_text; ?></textarea>
    <script type="text/javascript">var edCanvas = document.getElementById('side_text');</script>
  </p>
  <p>
    <?php
if ($mode == "add") {;
?>
    <input type="submit" value="Submit Page" name="submit" id="submit" class="button" />
    <?php
};
if ($mode == "edit") {;
?>
    <input type="submit" value="Update Page" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php }; ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php
// Show preview if not an add form
if ($mode == "edit") {; ?>
<h2><a name="preview"></a>Preview Page</h2>
<fieldset class="<?php echo "$module-preview" ?>">
<legend>Page Preview</legend>
<h2><?php echo $title; ?></h2>
<?php if ($side_text != "") {; ?>
<div style="width:20%;float:right;margin:0 0 5% 5%;padding:5%;background:#eee;"><?php echo autop($side_text); ?></div>
<?php }; ?>
<?php echo autop($text); ?>
</fieldset>
<h2>Delete Page <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p>Are you sure you want to delete this page?</p>
    <input type="submit" name="submit-delete" id="submit-delete" value="Yes" class="button" />
    <input type="button" name="cancel-delete" id="cancel-delete" value="Cancel" onClick="javascript:toggleLayer('deleteRecord');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php }; ?>
<?php get_cm_footer(); ?>
