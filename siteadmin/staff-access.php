<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "staff-access";
$pmodule = "staff-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

$mode = $_GET['action'];
$user_id = $_GET['id'];


if ($_POST['user_id'] != "") {;
	// Get modules
	$user_id = $_POST['user_id'];
	$article_browse = $_POST['article-browse'];
	$article_edit = $_POST['article-edit'];
	$article_media = $_POST['article-media'];
	$index = $_POST['index'];
	$issue_browse = $_POST['issue-browse'];
	$issue_edit = $_POST['issue-edit'];	
	$page_browse = $_POST['page-browse'];
	$page_edit = $_POST['page-edit'];
	$profile = $_POST['profile'];
	$section_browse = $_POST['section-browse'];
	$section_edit = $_POST['section-edit'];
	$server_info = $_POST['server-info'];
	$settings = $_POST['settings'];
	$staff_access = $_POST['staff-access'];
	$staff_browse = $_POST['staff-browse'];
	$staff_edit = $_POST['staff-edit'];
	$submitted_browse = $_POST['submitted-browse'];
	$submitted_edit = $_POST['submitted-edit'];
	$submitted_delete = $_POST['submitted-delete'];
	$poll_browse = $_POST['poll-browse'];
	$poll_edit = $_POST['poll-edit'];
	$restrict_issue = $_POST['restrict-issue'];
	$restrict_section = $_POST['restrict-section'];

	// Do actions
	$stat = cm_clear_access($user_id);
	if ($stat != 1) {;
		cm_error("Error in 'cm_clear_access' function.");
		exit;
	};
	$stat = cm_add_access($user_id,"module","article-browse",$article_browse);
	$stat = cm_add_access($user_id,"module","article-edit",$article_edit);
	$stat = cm_add_access($user_id,"module","article-media",$article_media);
	$stat = cm_add_access($user_id,"module","index",$index);
	$stat = cm_add_access($user_id,"module","issue-browse",$issue_browse);
	$stat = cm_add_access($user_id,"module","issue-edit",$issue_edit);
	$stat = cm_add_access($user_id,"module","profile",$profile);
	$stat = cm_add_access($user_id,"module","section-browse",$section_browse);
	$stat = cm_add_access($user_id,"module","section-edit",$section_edit);
	$stat = cm_add_access($user_id,"module","server-info",$server_info);
	$stat = cm_add_access($user_id,"module","settings",$settings);
	$stat = cm_add_access($user_id,"module","page-browse",$page_browse);
	$stat = cm_add_access($user_id,"module","page-edit",$page_edit);
	$stat = cm_add_access($user_id,"module","staff-access",$staff_access);
	$stat = cm_add_access($user_id,"module","staff-browse",$staff_browse);
	$stat = cm_add_access($user_id,"module","staff-edit",$staff_edit);
	$stat = cm_add_access($user_id,"module","submitted-browse",$submitted_browse);
	$stat = cm_add_access($user_id,"module","submitted-edit",$submitted_edit);
	$stat = cm_add_access($user_id,"module","submitted-delete",$submitted_delete);
	$stat = cm_add_access($user_id,"module","poll-browse",$poll_browse);
	$stat = cm_add_access($user_id,"module","poll-edit",$poll_edit);
	$stat = cm_add_access($user_id,"string","restrict_section",$restrict_section);
	$stat = cm_add_access($user_id,"string","restrict_issue",$restrict_issue);

	if ($stat == 1) {
		header("Location: $pmodule.php?msg=access-updated");
		exit;
	} else {;
		cm_error("Error in 'cm_add_access' function.");
		exit;
	};
};

if ($user_id == "") {;
	header("Location: $pmodule.php");
	exit;
};

$query_CM_Array = "SELECT * FROM cm_users ";
$query_CM_Array .= " WHERE id = $user_id ";
$query_CM_Array .= " LIMIT 1;";
	
// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
// If Array comes back empty, produce error
if ($totalRows_CM_Array != 1) {;
	cm_error("User $user_id doesn't exist, or you cannot edit access permisions.");
	exit;
};
		
// Define variables
$id = $row_CM_Array['id'];
$first_name = $row_CM_Array['user_first_name'];
$last_name = $row_CM_Array['user_last_name'];
$job_title = $row_CM_Array['user_job_title'];


?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"?>">Staff Manager</a></h2>
<p class="systemMessage"><?php echo "Setting user access for $first_name $last_name, $job_title"; ?></p>
<form action="<?php echo "$module.php"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Module Access</legend>
  <h3>Basic</h3>
  <p>
    <input type="checkbox" name="index" id="index" value="true" class="checkbox" <?php if(cm_get_access('index', $user_id) == "true" || $mode == "add") {; echo "checked"; }; ?> />
    <label for="">Home Page</label>
  </p>
  <p>
    <input type="checkbox" name="profile" id="profile" value="true" class="checkbox" <?php if(cm_get_access('profile',$user_id) == "true" || $mode == "add") {; echo "checked"; }; ?> />
    <label for="">User Profile</label>
  </p>
  <h3>Manage</h3>
  <div>
    <h4>Article Manager</h4>
    <p>
      <input type="checkbox" name="article-browse" id="article-browse" value="true" class="checkbox" <?php if(cm_get_access('article-browse',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Browse</label>
    </p>
    <p>
      <input type="checkbox" name="article-edit" id="article-edit" value="true" class="checkbox" <?php if (cm_get_access('article-edit',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Edit / Delete</label>
    </p>
    <p>
      <input type="checkbox" name="article-media" id="article-media" value="true" class="checkbox" <?php if (cm_get_access('article-media',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="">Media</label>
    </p>
  </div>
  <div>
    <h4>Restrict User</h4>
    <p>
      <select name="restrict-section" id="restrict-section">
        <option value="false">-- No Restrictions --</option>
        <?php cm_section_list($module, cm_get_restrict('restrict_section',$user_id)); ?>
      </select>
      <label for="restrict-section">Section</label>
    </p>
    <p>
      <select name="restrict-issue" id="restrict-issue">
        <option value="false" <?php if (cm_get_restrict('restrict_issue',$user_id) == "false") { echo "selected"; }; ?>>--
        No Restrictions --</option>
        <option value="next" <?php if (cm_get_restrict('restrict_issue',$user_id) == "next") { echo "selected"; }; ?>>Next
        Issue Only</option>
        <option value="current" <?php if (cm_get_restrict('restrict_issue',$user_id) == "current") { echo "selected"; }; ?>>Current
        Issue Only</option>
      </select>
      <label for="restrict-issue">Issue</label>
    </p>
  </div>
  <div>
    <h4>Submitted Article Manager</h4>
    <p>
      <input type="checkbox" name="submitted-browse" id="submitted-browse" value="true" class="checkbox" <?php if (cm_get_access('submitted-browse',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="submitted-browse">Browse</label>
    </p>
    <p>
      <input type="checkbox" name="submitted-edit" id="submitted-edit" value="true" class="checkbox" <?php if (cm_get_access('submitted-edit',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="submitted-edit">View</label>
    </p>
    <p>
      <input type="checkbox" name="submitted-delete" id="submitted-delete" value="true" class="checkbox" <?php if (cm_get_access('submitted-delete',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="submitted-delete">Delete</label>
    </p>
  </div>
  <div>
    <h4>Poll Manager</h4>
    <p>
      <input type="checkbox" name="poll-browse" id="poll-browse" value="true" class="checkbox" <?php if (cm_get_access('poll-browse',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="poll-browse">Browse</label>
    </p>
    <p>
      <input type="checkbox" name="poll-edit" id="poll-edit" value="true" class="checkbox" <?php if (cm_get_access('poll-edit',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="poll-edit">Edit</label>
    </p>
  </div>
  <div>
    <h4>Issue Manager</h4>
    <p>
      <input type="checkbox" name="issue-browse" id="issue-browse" value="true" class="checkbox" <?php if (cm_get_access('issue-browse',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="">Browse</label>
    </p>
    <p>
      <input type="checkbox" name="issue-edit" id="issue-edit" value="true" class="checkbox" <?php if (cm_get_access('issue-edit',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Edit</label>
    </p>
  </div>
<div>
    <h4>Page Manager</h4>
    <p>
      <input type="checkbox" name="page-browse" id="page-browse" value="true" class="checkbox" <?php if (cm_get_access('page-browse',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="">Browse</label>
    </p>
    <p>
      <input type="checkbox" name="page-edit" id="page-edit" value="true" class="checkbox" <?php if (cm_get_access('page-edit',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Edit</label>
    </p>
  </div>
  <h3>Configuration</h3>
  <div>
    <h4>Users</h4>
    <p>
      <input type="checkbox" name="staff-browse" id="staff-browse" value="true" class="checkbox" <?php if (cm_get_access('staff-browse',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Browse</label>
    </p>
    <p>
      <input type="checkbox" name="staff-access" id="staff-access" value="true" class="checkbox" <?php if (cm_get_access('staff-access',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Access</label>
    </p>
    <p>
      <input type="checkbox" name="staff-edit" id="staff-edit" value="true" class="checkbox" <?php if (cm_get_access('staff-edit',$user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Edit</label>
    </p>
  </div>
  <div>
    <h4>Sections</h4>
    <p>
      <input type="checkbox" name="section-browse" id="section-browse" value="true" class="checkbox" <?php if (cm_get_access('section-browse',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="">Browse</label>
    </p>
    <p>
      <input type="checkbox" name="section-edit" id="section-edit" value="true" class="checkbox" <?php if (cm_get_access('section-edit',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="">Edit</label>
    </p>
  </div>
  <div>
    <h4>Other</h4>
    <p>
      <input type="checkbox" name="server-info" id="server-info" value="true" class="checkbox" <?php if (cm_get_access('server-info',$user_id) == "true") {; echo "checked"; };  ?> />
      <label for="">Server Information</label>
    </p>
    <p>
      <input type="checkbox" name="settings" id="settings" value="true" class="checkbox" <?php if (cm_get_access('settings', $user_id) == "true") {; echo "checked"; }; ?> />
      <label for="">Settings</label>
    </p>
  </div>
  <p>
    <input type="submit" value="Update Settings" name="update" id="update" class="button" />
    <input name="user_id" type="hidden" id="user_id" value="<?php echo $user_id; ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
