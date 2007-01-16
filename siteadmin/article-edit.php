<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "article-edit";
$pmodule = "article-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if ($_GET["action"] != "") {
	$mode = $_GET["action"];
}

// Make sure we have operating parameters set.
if ($_COOKIE["$pmodule-section"] == "" || $_COOKIE["$pmodule-issue"] == "") { // Send back to parent
	header("Location: $pmodule.php");
	exit;
}

// These will be changed later if needed, set defaults.
$section = $_COOKIE["$pmodule-section"];
$issue = $_COOKIE["$pmodule-issue"];
$id = $_GET["id"];

// If action is quick edit, call quick edit function
if ($_GET['action'] == "quick-edit") { 
		if ($_POST['quick-edit-id'] != "") {
		cm_quick_edit($_POST['quick-edit-id']);
		exit;
	} else {
		cm_error("You must enter a number.");
	}
}

// If action is delete, call delete function
if ($_GET['action'] == "delete" && $_POST['delete-id'] != "") { 
	$id = $_POST['delete-id'];
	// Run function
	$stat = cm_delete_article($id);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {
		cm_error("Error in 'cm_delete_article' function.");
		exit;
	}	
}

// If action is edit, call edit function
if ($_GET['action'] == "edit") { 
	if ($_POST['id'] != "") {
		// Get posted data
		$title = prep_string($_POST['title']);
		$subtitle = prep_string($_POST['subtitle']);
		$author = prep_string($_POST['author']);
		$author_title = prep_string($_POST['author_title']);
		$summary = prep_string($_POST['summary']);
		$text = prep_string($_POST['text']);
		$keywords = prep_string($_POST['keywords']);
		$priority = $_POST['priority'];
		$section = $_POST['section'];
		$issue = $_POST['issue'];
		$published = $_POST['published'];
		$id	= $_POST['id'];
		
		// Run function
		$stat = cm_edit_article($title,$subtitle,$author,$author_title,$summary,$text,$keywords,$priority,$section,$issue,$published,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_article' function.");
			exit;
		}
	} else {
		cm_error("Did not have an article to load.");
		exit;
	}
}

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['text'] != "") { 
	// Get posted data
	$title = prep_string($_POST['title']);
	$subtitle = prep_string($_POST['subtitle']);
	$author = prep_string($_POST['author']);
	$author_title = prep_string($_POST['author_title']);
	$summary = prep_string($_POST['summary']);
	$text = prep_string($_POST['text']);
	$keywords = prep_string($_POST['keywords']);
	$priority = $_POST['priority'];
	$section = $_POST['section'];
	$issue = $_POST['issue'];
	$published = $_POST['published'];		
	// Run function
	$stat = cm_add_article($title,$subtitle,$author,$author_title,$summary,$text,$keywords,$priority,$section,$issue,$published);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {
		cm_error("Error in 'cm_add_article' function.");
		exit;
	}
}

// Only call database if in edit mode.
if ($mode == "edit") {
	
	// Query
	$query = "SELECT * FROM cm_articles ";
	$query .= " WHERE id = $id";
	// Security Measure
	if ($restrict_section != "false") {
		$section = $restrict_section;
		$query .= " AND section_id = '$section'";
	}
	if ($restrict_issue == "current") {
		$issue = $current_issue_id;
		$query .= " AND issue_id = '$issue'";
	}
	if ($restrict_issue == "next") {
		$issue = $next_issue_id;
		$query .= " AND issue_id = '$issue'";
	}
	
	// Run Query
	$result  = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
	$result_array  = mysql_fetch_assoc($result);
	$result_row_count = mysql_num_rows($result);
	
	// If Array comes back empty, produce error
	if ($result_row_count != 1) {
		cm_error("Article doesn't exist, or you do not have permission to edit it.");
		exit;
	}
		
	// Define variables
	$id = $result_array['id'];
	$section = $result_array['section_id'];
	$issue = $result_array['issue_id'];
	$title = $result_array['article_title'];
	$subtitle = $result_array['article_subtitle'];
	$summary = $result_array['article_summary'];
	$text = $result_array['article_text'];
	$keywords = $result_array['article_keywords'];
	$author = $result_array['article_author'];
	$author_title = $result_array['article_author_title'];
	$priority = $result_array['article_priority']; 
	$published = $result_array['article_publish'];
	$edited = $result_array['article_edit'];
}

?>
<?php get_cm_header(); ?>

<h2><a href="<?php echo $pmodule; ?>.php?">Article Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Article Editor</legend>
  <div class="sidebar">
    <p>
      <label for="keywords">Keywords</label>
      <br />
      <input type="text" name="keywords" id="keywords" value="<?php echo htmlentities($keywords); ?>" />
    </p>
    <?php if ($restrict_section == "false") { ?>
    <p>
      <label for="section">Section</label>
      <br />
      <select name="section" id="section">
        <?php cm_section_list($module, $section); ?>
      </select>
    </p>
    <?php } else { ?>
    <input type="hidden" name="section" id="section" value="<?php echo $section; ?>" />
    <?php } ?>
<?php if ($restrict_issue == "false") { ?>
    <p>
      <label for="issue">Issue</label>
      <br />
      <select name="issue" id="issue">
        <?php cm_issue_list($module, $issue); ?>
      </select>
    </p>
    <?php } else { ?>
    <input type="hidden" name="issue" id="issue" value="<?php echo $issue; ?>" />
    <?php } ?>
<?php if ($mode != "new") { ?>
    <p> <strong>Last Edited</strong><br />
      <code><?php echo $edited; ?></code> </p>
    <?php } ?>
<?php if ($restrict_issue == "false") { ?>
    <p>
      <label for="published">Publish Timestamp</label>
      <br />
      <input type="text" name="published" id="published" value="<?php if ($published == "") { echo date("Y-m-d h:i:s",time()); } else { echo $published; }?>" />
    </p>
    <?php } else { ?>
    <input type="hidden" name="published" id="published" value="<?php echo $published; ?>" />
    <?php } ?>
    <p>
      <label for="priority">Assigned Priority</label>
      <br />
	  <select name="priority" id="priority">
	  	<option value="1" <?php if ($priority == 1) { echo "selected"; } ?>>1 -- Highest</option>
	  	<option value="2" <?php if ($priority == 2) { echo "selected"; } ?>>2</option>
	  	<option value="3" <?php if ($priority == 3) { echo "selected"; } ?>>3</option>
	  	<option value="4" <?php if ($priority == 4) { echo "selected"; } ?>>4</option>
	  	<option value="5" <?php if ($priority == 5) { echo "selected"; } ?>>5</option>
	  	<option value="6" <?php if ($priority == 6) { echo "selected"; } ?>>6</option>
	  	<option value="7" <?php if ($priority == 7) { echo "selected"; } ?>>7</option>
	  	<option value="8" <?php if ($priority == 8) { echo "selected"; } ?>>8</option>
	  	<option value="9" <?php if ($priority == 9) { echo "selected"; } ?>>9</option>
	  	<option value="10" <?php if ($priority == 10) { echo "selected"; } ?>>10</option>
	  	<option value="11" <?php if ($priority == 11) { echo "selected"; } ?>>11</option>
	  	<option value="12" <?php if ($priority == 12) { echo "selected"; } ?>>12</option>
	  	<option value="13" <?php if ($priority == 13) { echo "selected"; } ?>>13</option>										
	  	<option value="14" <?php if ($priority == 14) { echo "selected"; } ?>>14</option>
	  	<option value="15" <?php if ($priority == 15 || $priority == "") { echo "selected"; } ?>>15 -- Lowest</option>		
	  </select>
    </p>
    <strong>Linked Media</strong>
    <ul>
      <?php if ($mode != "new") { ?>
<?php cm_list_media($id); ?>
      <li><strong><a href="article-media.php?action=new&amp;article_id=<?php echo $id; ?>" onClick="return confirmLink(this, 'leave this page and lose all changes')">Add
            New Media</a></strong></li>
      <?php } else { ?>
      <li>You must first publish the article<br /> before adding linked media.</li>
      <?php } ?>
    </ul>
  </div>
  <p>
    <label for="title">Title</label>
    <br />
    <input type="text" name="title" id="title" value="<?php echo htmlentities($title); ?>" class="text" />
  </p>
  <p>
    <label for="subtitle">Subtitle</label>
    <br />
    <input type="text" name="subtitle" id="subtitle" value="<?php echo htmlentities($subtitle); ?>" class="text" />
  </p>
  <p>
    <label for="author_id">Author</label>
    <br />
    <input type="text" name="author" id="author" value="<?php echo htmlentities($author); ?>" class="text" />
  </p>
  <p>
    <label for="author_title">Author Title</label>
    <br />
    <input type="text" name="author_title" id="author_title" value="<?php echo htmlentities($author_title); ?>" class="text" />
  </p>
  <p>
    <label for="summary">Summary</label>
    <br />
    <textarea name="summary" rows="5" id="summary"><?php echo $summary; ?></textarea>
  </p>
  <p>
    <label for="text">Text</label>
    <br />
	<span class="article-edit-tags"><script type="text/javascript">edToolbar();</script></span>
    <textarea name="text" rows="20" id="text"><?php echo $text; ?></textarea>
    <script type="text/javascript">var edCanvas = document.getElementById('text');</script>
  </p>
  <p>
    <?php
if ($mode == "new") {
?>
    <input type="submit" value="Submit Article" name="submit" id="submit" class="button" />
    <?php
}
if ($mode == "edit") {
?>
    <input type="submit" value="Update Article" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php } ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php
// Show preview if not an add form
if ($mode != "new") { ?>
<h2><a name="preview"></a>Preview Article</h2>
<fieldset class="<?php echo "$module-preview" ?>">
<legend>Story Preview</legend>
<h2><?php echo $title; ?></h2>
<h4><em><?php echo $subtitle; ?></em></h4>
<p><?php echo $author; if ($author_title != "") { echo ", $author_title"; } ?></p>
<?php echo autop($text); ?>
</fieldset>
<h2>Delete Article <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p>Are you sure you want to delete this article?</p>
    <input type="submit" name="submit-delete" id="submit-delete" value="Yes" class="button" />
    <input type="button" name="cancel-delete" id="cancel-delete" value="Cancel" onClick="javascript:toggleLayer('deleteArticle');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php } ?>
<?php get_cm_footer(); ?>
