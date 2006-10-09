<?php include('cm-includes/config.php'); ?>
<?php
$module = "article-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if ($_COOKIE["$module-section"] == "") {;
	setcookie("$module-section", "1"); // Default Section
	$section = 1;
} else {;
	$section = $_COOKIE["$module-section"];
};

if ($_COOKIE["$module-issue"] == "") {;
	setcookie("$module-issue", $next_issue_id); // Current Issue
	$issue = $next_issue_id;
} else {;
	$issue = $_COOKIE["$module-issue"];
};

// Switch sections
if ($_GET['section'] != "") {;
	$section = $_GET['section'];
	if (cm_section_info("id",$section) == "") {;
		cm_error("The selected section could not be loaded.");
	};
	setcookie("$module-section", $section);
	header("Location: $module.php");
	exit;
};

// Switch issues
if (is_numeric($_GET['issue'])) {;
	$issue = $_GET['issue'];
	if (cm_issue_info("id",$issue) == "") {;
		cm_error("The selected issue could not be loaded.");
	};	
	setcookie("$module-issue", $issue);
	header("Location: $module.php");
	exit;
};

// Show Summaries
if ($_GET['summary'] == "show") {;
	setcookie("$module-summary", true);
	header("Location: $module.php");
	exit;
};

// Hide Summaries
if ($_GET['summary'] == "hide") {;
	setcookie("$module-summary", false);
	header("Location: $module.php");
	exit;
};

if ($restrict_issue == "next") {
	$issue = $next_issue_id;
	setcookie("$module-issue", $issue);
};
if ($restrict_issue == "current") {;
	$issue = $current_issue_id;
	setcookie("$module-issue", $issue);
};
if ($restrict_section != "false") {;
	$section = $restrict_section;
	setcookie("$module-section", $section);
};

// Reset publication dates to match issue's
if ($_GET['reset'] == 'publish') {
	$publish_date = cm_issue_info('issue_date', $issue);
	$query = "UPDATE cm_articles";
	$query .= " SET article_publish = '$publish_date 00:00:00'";
	$query .= " WHERE issue_id = \"$issue\" AND section_id = \"$section\";";
	$stat = cm_run_query($query);
	if ($stat == 1) {
		header("Location: $module.php?msg=pub-reset");
		exit;
	} else {
		header("Location: $module.php?msg=pub-error");
		exit;	
	};
};


// Database Query
$query_CM_Array = "SELECT * FROM cm_articles WHERE issue_id = \"$issue\" AND section_id = \"$section\" ORDER BY article_priority;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

?>
<?php get_cm_header(); ?>

<h2>Article Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") {; echo "<p class=\"systemMessage\">Article added.</p>"; };
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Article updated.</p>"; };
if ($msg == "deleted") {; echo "<p class=\"systemMessage\">Article deleted.</p>"; };
if ($msg == "media-added") {; echo "<p class=\"systemMessage\">Media added.</p>"; };
if ($msg == "media-updated") {; echo "<p class=\"systemMessage\">Media updated.</p>"; };
if ($msg == "media-deleted") {; echo "<p class=\"systemMessage\">Media deleted.</p>"; };
if ($msg == "pub-reset") {; echo "<p class=\"systemMessage\">Publication dates set to match issue.</p>"; };
if ($msg == "pub-error") {; echo "<p class=\"systemMessage\">Error setting publication dates.</p>"; };
?>
<?php if ($restrict_issue != "next" && $restrict_issue != "current") {; // Begin Restrict Issue ?>
<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Select Issue</legend>
  <div class="actionMenu">
    <ul>
      <li>
        <label>Select Issue:</label>
        <select name="issue" id="issue">
          <?php cm_issue_list($module, $issue); ?>
        </select>
        <input type="submit" name="submit" id="submit" value="Open" class="button" />
      </li>
      <li><a href="<?php echo "$module.php?issue=$current_issue_id";?>" <?php if ($issue == $current_issue_id) {;	echo " class=\"selected\""; }; ?>><strong>Current:</strong> <?php echo $current_issue_date; ?></a></li>
      <li><a href="<?php echo "$module.php?issue=$next_issue_id";?>" <?php if ($issue == $next_issue_id) {;	echo " class=\"selected\""; }; ?>><strong>Next:</strong> <?php echo $next_issue_date; ?></a></li>
    </ul>
  </div>
  </fieldset>
</form>
<?php }; // End Restrict Issue ?>
<form action="article-edit.php" method="get" name="QuickEdit" id="QuickEdit">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend><?php echo "Viewing: " . cm_issue_info("issue_date", $issue) . " (Volume " . cm_issue_info("issue_volume", $issue) . ", No. " . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
  <?php if ($restrict_section == "false") {; // Begin Restrict Section ?>
  <div class="actionMenu">
    <ul>
      <li><strong>Section:</strong></li>
      <?php cm_section_list($module, $section); ?>
    </ul>
  </div>
  <?php } else {;  ?>
  <p><strong>Viewing:</strong> <?php echo cm_section_info('section_name', $section); ?></p>
  <?php }; // End Restrict Section  ?>
  <?php if ($totalRows_CM_Array > 0) {; ?>
  <table class="<?php echo $module; ?>-table">
    <tr>
      <th><acronym title="Assigned Priority">AP</acronym></th>
      <th>Headline</th>
      <?php if ($show_article_edit == "true") {; ?>
      <th>Tools</th>
      <?php }; ?>
    </tr>
    <?php

do {;
	$id = $row_CM_Array['id'];
	$title = $row_CM_Array['article_title'];
	$summary = $row_CM_Array['article_summary'];
	$published = $row_CM_Array['article_publish'];
	$keywords = $row_CM_Array['article_keywords'];
	$author = $row_CM_Array['article_author'];
	$priority = $row_CM_Array['article_priority'];
	$issue_id = $row_CM_Array['issue_id'];
	$issue_date = cm_issue_info("issue_date",$issue_id) . " 00:00:00";
	if ($published > $issue_date) { $is_breaking = "true"; } else { unset($is_breaking); };
	if ($is_breaking == "true") { $flag = "<small style=\"color:red;font-weight:bold;\">[ BREAKING NEWS ]</small> "; } else { unset($flag); };
?>
    <tr>
      <td class="center"><?php echo $priority; ?></td>
      <td><p><?php echo $flag; ?><a href="article-edit.php?id=<?php echo $id; ?>#preview"><strong><?php echo $title; ?></strong></a><small> - <?php echo $author; ?></small></p>
        <?php if ($_COOKIE["$module-summary"] == true) {; ?>
        <?php echo autop($summary); ?>
        <?php if ($show_article_media == "true") {; cm_list_media($id,true); }; ?>
        <p><strong>Keywords:</strong> <em><?php echo $keywords; ?></em></p>
        <?php	}; // End Summary Row ?>
      </td>
      <?php if ($show_article_edit == "true") {; ?>
      <td nowrap class="actionMenu">
        <ul class="center">
          <li class="command-preview"><a href="article-edit.php?id=<?php echo $id; ?>#preview">Preview</a></li>
          <li class="command-edit"><a href="article-edit.php?id=<?php echo $id; ?>">Edit</a></li>
          <li class="command-delete"><a href="article-edit.php?id=<?php echo $id; ?>#delete">Delete</a></li>
        </ul>
        <?php }; ?>
      </td>
    </tr>
    <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
    <?php if ($show_article_edit == "true") {; ?>
    <tr>
      <td>&nbsp;</td>
      <td class="center"><strong><a href="article-edit.php?action=new">Add an
            Article</a></strong></td>
      <td nowrap class="center">
        <?php if ($restrict_section == "false" && $restrict_issue == "false") {; // Hide Quick Edit ?>
        <input type="text" name="id" id="id" size="4" maxlength="11" />
        <input type="submit" name="submit-edit" id="submit-edit" value="Quick Edit" />
        <?php }; // End Hide Quick Edit?>
      </td>
    </tr>
    <?php }; ?>
  </table>
  <div class="actionMenu">
    <ul>
      <li><strong>Summaries:</strong></li>
      <?php if ($_COOKIE["$module-summary"] == false) {; ?>
      <li><a href="<?php echo $module; ?>.php?summary=show">Show Summaries</a></li>
      <?php } else {; ?>
      <li><a href="<?php echo $module; ?>.php?summary=hide">Hide Summaries</a></li>
      <?php }; ?>
	  <li><strong>Reset Dates:</strong></li>
      <li><a href="<?php echo $module; ?>.php?reset=publish">Publication Dates</a></li>
    </ul>
  </div>
  <?php } else {; ?>
  <p>This selected section is empty.
    <?php if ($section != "") {; ?>
    <a href="article-edit.php?action=new">Add an article</a>.
    <?php }; ?>
  </p>
  <?php }; ?>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
