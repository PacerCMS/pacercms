<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "article-browse";
$cmodule = "article-edit";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if ($_COOKIE["$module-section"] == "") {
	setcookie("$module-section", "1"); // Default Section
	$section = 1;
} else {
	$section = $_COOKIE["$module-section"];
}

if ($_COOKIE["$module-issue"] == "") {
	setcookie("$module-issue", cm_next_issue('id')); // Current Issue
	$issue = cm_next_issue('id');
} else {
	$issue = $_COOKIE["$module-issue"];
}

// Switch sections
if ($_GET['section'] != "") {
	$section = $_GET['section'];
	if (!is_numeric(cm_section_info("id",$section)))
	{
		cm_error(gettext("The selected section could not be loaded."));
		exit;
	}
	setcookie("$module-section", $section);
	header("Location: $module.php");
	exit;
}

// Switch issues
if (is_numeric($_GET['issue'])) {
	$issue = $_GET['issue'];
	if (!is_numeric(cm_issue_info("id",$issue)))
	{
		cm_error(gettext("The selected issue could not be loaded."));
		exit;
	}	
	setcookie("$module-issue", $issue);
	header("Location: $module.php");
	exit;
}

// Show Summaries
if ($_GET['summary'] == "show") {
	setcookie("$module-summary", true);
	header("Location: $module.php");
	exit;
}

// Hide Summaries
if ($_GET['summary'] == "hide") {
	setcookie("$module-summary", false);
	header("Location: $module.php");
	exit;
}

if (cm_auth_restrict('restrict_issue') == "next") {
	$issue = cm_next_issue('id');
	setcookie("$module-issue", $issue);
}
if (cm_auth_restrict('restrict_issue') == "current") {
	$issue = cm_current_issue('id');
	setcookie("$module-issue", $issue);
}
if (is_numeric(cm_auth_restrict('restrict_section'))) {
	$section = cm_auth_restrict('restrict_section');
	setcookie("$module-section", $section);
}

// Reset publication dates to match issue's
if ($_GET['reset'] == 'publish') {
	$publish_date = cm_issue_info('issue_date', $issue);
	$query = "UPDATE cm_articles";
	$query .= " SET article_publish = '$publish_date 00:00:00'";
	$query .= " WHERE issue_id = \"$issue\" AND section_id = \"$section\";";
	$stat = cm_run_query($query);
	if ($stat) {
		header("Location: $module.php?msg=pub-reset");
		exit;
	} else {
		header("Location: $module.php?msg=pub-error");
		exit;	
	}
}


// Database Query
$query = "SELECT * FROM cm_articles WHERE issue_id = \"$issue\" AND section_id = \"$section\" ORDER BY article_priority;";

// Run Query
$result = cm_run_query($query);
$records = $result->GetArray();

get_cm_header();

?>

<h2><?php echo gettext("Article Manager"); ?></h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"infoMessage\">" . gettext("Article added.") . "</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">" . gettext("Article updated.") . "</p>"; }
if ($msg == "deleted") { echo "<p class=\"alertMessage\">" . gettext("Article deleted.") . "</p>"; }
if ($msg == "media-added") { echo "<p class=\"infoMessage\">" . gettext("Media added.") . "</p>"; }
if ($msg == "media-updated") { echo "<p class=\"infoMessage\">" . gettext("Media updated.") . "</p>"; }
if ($msg == "media-deleted") { echo "<p class=\"alertMessage\">" . gettext("Media deleted.") . "</p>"; }
if ($msg == "pub-reset") { echo "<p class=\"alertMessage\">" . gettext("Publication dates set to match issue.") . "</p>"; }
if ($msg == "pub-error") { echo "<p class=\"alertMessage\">" . gettext("Error setting publication dates.") . "</p>"; }
?>
<?php
// Begin Restrict Issue
if (cm_auth_restrict('restrict_issue') != "next" && cm_auth_restrict('restrict_issue') != "current")
{
?>
<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo gettext("Select Issue"); ?></legend>
  <div class="actionMenu">
    <ul>
      <li>
        <label><?php echo gettext("Select Issue:"); ?></label>
        <select name="issue" id="issue">
          <?php cm_issue_list($module, $issue); ?>
        </select>
        <input type="submit" name="submit" id="submit" value="Open" class="button" />
      </li>
      <li><a href="<?php echo "$module.php?issue=" . cm_current_issue('id');?>" <?php if ($issue == cm_current_issue('id')) {	echo " class=\"selected\""; } ?>><strong><?php echo gettext("Current:"); ?></strong> <?php echo cm_current_issue('date'); ?></a></li>
      <li><a href="<?php echo "$module.php?issue=" . cm_next_issue('id');?>" <?php if ($issue == cm_next_issue('id')) {	echo " class=\"selected\""; } ?>><strong><?php echo gettext("Next:"); ?></strong> <?php echo cm_next_issue('date'); ?></a></li>
    </ul>
  </div>
  </fieldset>
</form>
<?php } // End Restrict Issue ?>
<form action="article-edit.php" method="get" name="QuickEdit" id="QuickEdit">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo gettext("Viewing: ") . cm_issue_info("issue_date", $issue) . gettext(" (Volume ") . cm_issue_info("issue_volume", $issue) . gettext(", No. ") . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
  <?php if (cm_auth_restrict('restrict_section') == "false") { // Begin Restrict Section ?>
  <div class="actionMenu">
    <ul>
      <li><strong><?php echo gettext("Section:"); ?></strong></li>
      <?php cm_section_list($module, $section); ?>
    </ul>
  </div>
  <?php } else {  ?>
  <p><strong><?php echo gettext("Viewing:"); ?></strong> <?php echo cm_section_info('section_name', $section); ?></p>
  <?php } // End Restrict Section  ?>
  <?php if (!empty($records)) { ?>

  <table class="<?php echo $module; ?>-table">
    <tr>
      <th><acronym title="<?php echo gettext("Assigned Priority"); ?>"><?php echo gettext("AP"); ?></acronym></th>
      <th><?php echo gettext("Headline"); ?></th>
      <?php if (cm_auth_restrict('article-edit') == "true") { ?>
      <th><?php echo gettext("Tools"); ?></th>
      <?php } ?>
    </tr>
    <?php

foreach ($records as $article)
{
	$id = $article['id'];
	$title = $article['article_title'];
	$summary = $article['article_summary'];
	$published = $article['article_publish'];
	$keywords = $article['article_keywords'];
	$author = $article['article_author'];
	$priority = $article['article_priority'];
	$issue_id = $article['issue_id'];
	$issue_date = cm_issue_info("issue_date",$issue_id) . " 00:00:00";
	if ($published > $issue_date) { $is_breaking = "true"; } else { unset($is_breaking); }
	if ($is_breaking == "true") { $flag = "<small style=\"color:red;font-weight:bold;\">[ " . gettext("Breaking News") . " ]</small> "; } else { unset($flag); }
?>
    <tr>
      <td class="center"><?php echo $priority; ?></td>
      <td><p><?php echo $flag; ?><a href="article-edit.php?id=<?php echo $id; ?>#preview"><strong><?php echo $title; ?></strong></a><small> - <?php echo $author; ?></small></p>
        <?php if ($_COOKIE["$module-summary"] == true) { ?>
        <?php echo autop($summary); ?>
        <?php if (cm_auth_restrict('article-media') == "true") { cm_list_media($id,true); } ?>
        <p><strong><?php echo gettext("Keywords:"); ?></strong> <em><?php echo $keywords; ?></em></p>
        <?php	} // End Summary Row ?>
      </td>
      <?php if (cm_auth_restrict('article-edit') == "true") { ?>
      <td nowrap class="actionMenu">
        <ul class="center">
          <li class="command-preview"><a href="article-edit.php?id=<?php echo $id; ?>#preview"><?php echo gettext("Preview"); ?></a></li>
          <li class="command-edit"><a href="article-edit.php?id=<?php echo $id; ?>"><?php echo gettext("Edit"); ?></a></li>
          <li class="command-delete"><a href="article-edit.php?id=<?php echo $id; ?>#delete"><?php echo gettext("Delete"); ?></a></li>
        </ul>
        <?php } ?>
      </td>
    </tr>
<?php } ?>
    <?php if (cm_auth_restrict('article-edit') == "true") { ?>
    <tr>
      <td>&nbsp;</td>
      <td class="center"><strong><a href="article-edit.php?action=new"><?php echo gettext("Add an Article"); ?></a></strong></td>
      <td nowrap class="center">
        <?php if (cm_auth_restrict('restrict_section') == "false" && cm_auth_restrict('restrict_issue') == "false") { // Hide Quick Edit ?>
        <input type="text" name="id" id="id" size="4" maxlength="11" />
        <input type="submit" name="submit-edit" id="submit-edit" value="Quick Edit" />
        <?php } // End Hide Quick Edit?>
      </td>
    </tr>
    <?php } ?>
  </table>
  <div class="actionMenu">
    <ul>
      <li><strong><?php echo gettext("Summaries:"); ?></strong></li>
      <?php if ($_COOKIE["$module-summary"] == false) { ?>
      <li><a href="<?php echo $module; ?>.php?summary=show"><?php echo gettext("Show Summaries"); ?></a></li>
      <?php } else { ?>
      <li><a href="<?php echo $module; ?>.php?summary=hide"><?php echo gettext("Hide Summaries"); ?></a></li>
      <?php } ?>
	  <li><strong><?php echo gettext("Reset Dates:"); ?></strong></li>
      <li><a href="<?php echo $module; ?>.php?reset=publish"><?php echo gettext("Publication Dates"); ?></a></li>
    </ul>
  </div>
  <?php } else { ?>
  <p><?php echo gettext("This selected section is empty."); ?>
    <?php if (cm_auth_restrict('article-edit') == "true") { ?>
    <a href="article-edit.php?action=new"><?php echo gettext("Add an Article"); ?></a>.
    <?php } ?>
  </p>
  <?php } ?>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
