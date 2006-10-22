<?php 
	// This shouldn't be up here, but for whatever reason it won't load the values.
	$show_issue_browse = cm_get_access('issue-browse', $_SESSION['cm_user_id']);
	$show_profile = cm_get_access('profile', $_SESSION['cm_user_id']);
	$show_section_browse = cm_get_access('section-browse', $_SESSION['cm_user_id']);
	$show_settings = cm_get_access('settings', $_SESSION['cm_user_id']);
	$show_staff_browse = cm_get_access('staff-browse', $_SESSION['cm_user_id']);
	$show_article_browse = cm_get_access('article-browse', $_SESSION['cm_user_id']);
	$show_submitted_browse = cm_get_access('submitted-browse', $_SESSION['cm_user_id']);
	$show_page_browse = cm_get_access('page-browse', $_SESSION['cm_user_id']);
	$show_poll_browse = cm_get_access('poll-browse', $_SESSION['cm_user_id']);
	$show_advertising_browse = cm_get_access('advertising-browse', $_SESSION['cm_user_id']);
?>
<div id="mainmenu">
  <?php if ($_SESSION['cm_user_id'] != "") {; // Hide menu if not logged in?>
<?php if ($show_article_browse == "true" || $show_issue_browse == "true" || $show_submitted_browse == "true" || $show_poll_browse == "true" || $show_advertising_browse == "true") {; ?>
  <h3>Manage</h3>
  <ul>
    <?php if ($show_article_browse == "true") {; echo "<li><a href=\"article-browse.php\">Articles</a></li>"; }; ?>
    <?php if ($show_issue_browse == "true") {; echo "<li><a href=\"issue-browse.php\">Issues</a></li>"; }; ?>
    <?php if ($show_submitted_browse == "true") {; echo "<li><a href=\"submitted-browse.php\">Submitted</a></li>"; }; ?>
    <?php if ($show_poll_browse == "true") {; echo "<li><a href=\"poll-browse.php\">Polls</a></li>"; }; ?>
    <?php if ($show_advertising_browse == "true") {; echo "<li><a href=\"advertising-browse.php\">Advertising</a></li>"; }; ?>
    <?php if ($show_page_browse == "true") {; echo "<li><a href=\"page-browse.php\">Pages</a></li>"; }; ?>
  </ul>
  <?php }; ?>
<?php if ($show_section_browse == "true" || $show_staff_browse == "true" || $show_settings == "true") {; ?>
  <h3>Configuration</h3>
  <ul>
    <?php if ($show_section_browse == "true") {; echo "<li><a href=\"section-browse.php\">Sections</a></li>"; }; ?>
    <?php if ($show_staff_browse == "true") {; echo "<li><a href=\"staff-browse.php\">Users</a></li>"; }; ?>
    <?php if ($show_settings == "true") {; echo "<li><a href=\"settings.php\">Settings</a></li>"; }; ?>
  </ul>
<?php }; ?>  
  
  <h3>Profile</h3>
  <ul>
    <?php if ($show_profile == "true") {; echo "<li><a href=\"profile.php\">Edit My Profile</a></li>"; }; ?>
	<li><a href="logout.php" onClick="return confirmLink(this, 'logout of PacerCMS')">Logout</a></li>
  </ul>
  <?php }; // End hide if not logged in ?>
</div>
