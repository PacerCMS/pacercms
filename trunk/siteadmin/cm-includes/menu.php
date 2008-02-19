<?php 
	$show_issue_browse = cm_auth_restrict('issue-browse');
	$show_profile = cm_auth_restrict('profile');
	$show_section_browse = cm_auth_restrict('section-browse');
	$show_settings = cm_auth_restrict('settings');
	$show_staff_browse = cm_auth_restrict('staff-browse');
	$show_article_browse = cm_auth_restrict('article-browse');
	$show_submitted_browse = cm_auth_restrict('submitted-browse');
	$show_page_browse = cm_auth_restrict('page-browse');
	$show_poll_browse = cm_auth_restrict('poll-browse');
?>
<div id="mainmenu">

<?php if ($show_article_browse == "true" || $show_issue_browse == "true" || $show_submitted_browse == "true" || $show_poll_browse == "true" || $show_page_browse == "true") { ?>
  <h3>Manage</h3>
  <ul>
    <?php if ($show_article_browse == "true") { echo "<li><a href=\"article-browse.php\">Articles</a></li>"; } ?>
    <?php if ($show_issue_browse == "true") { echo "<li><a href=\"issue-browse.php\">Issues</a></li>"; } ?>
    <?php if ($show_submitted_browse == "true") { echo "<li><a href=\"submitted-browse.php\">Submitted</a></li>"; } ?>
    <?php if ($show_poll_browse == "true") { echo "<li><a href=\"poll-browse.php\">Polls</a></li>"; } ?>
    <?php if ($show_page_browse == "true") { echo "<li><a href=\"page-browse.php\">Pages</a></li>"; } ?>
  </ul>
  <?php }; ?>
<?php if ($show_section_browse == "true" || $show_staff_browse == "true" || $show_settings == "true") { ?>
  <h3>Configuration</h3>
  <ul>
    <?php if ($show_section_browse == "true") { echo "<li><a href=\"section-browse.php\">Sections</a></li>"; } ?>
    <?php if ($show_staff_browse == "true") { echo "<li><a href=\"staff-browse.php\">Users</a></li>"; } ?>
    <?php if ($show_settings == "true") { echo "<li><a href=\"settings.php\">Settings</a></li>"; } ?>
  </ul>
<?php }; ?>  

<?php if ($show_profile == "true") { ?>
  <h3>Profile</h3>
  <ul>
    <?php if ($show_profile == "true") {; echo "<li><a href=\"profile.php\">Edit My Profile</a></li>"; } ?>
	<li><a href="login.php?action=logout" onClick="return confirmLink(this, 'logout of PacerCMS')">Logout</a></li>
  </ul>
<?php } ?>

</div>
