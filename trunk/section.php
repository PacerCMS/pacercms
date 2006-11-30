<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

if ($_GET['id'] == 1) {;
	// Go back to homepage.
	$home = site_info('url');
	header("Location: $home");
	exit;
} else {;
	$section = $_GET['id'];
};

$issue = current_issue('id');

// Database Connection
$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
mysql_select_db(DB_DATABASE, $CM_MYSQL);	
// Database Query
$query_CM_Array = "SELECT * FROM cm_articles";	
$query_CM_Array .= " WHERE section_id = '$section' AND issue_id = '$issue'";
$query_CM_Array .= " ORDER BY article_priority ASC";
$query_CM_Array .= " LIMIT 0,1;";

// Run Query
$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_topStory = mysql_fetch_assoc($CM_Array);
$totalRows_topStory = mysql_num_rows($CM_Array);

// Database Query
$query_CM_Array = "SELECT * FROM cm_articles";	
$query_CM_Array .= " WHERE section_id = '$section' AND issue_id = '$issue'";
$query_CM_Array .= " ORDER BY article_priority ASC";
$query_CM_Array .= " LIMIT 1,99;";

// Run Query
$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_topMore = mysql_fetch_assoc($CM_Array);
$totalRows_topMore = mysql_num_rows($CM_Array);

/* Header Configuration */
$pageTitle = " - " . section_info('name', $section);
?>
<?php get_header($topBar,$pageTitle,$section); ?>

<div id="content">
<h2 class="sectionNameplate"><?php echo section_info('name', $section); ?></h2>
<?php if ($totalRows_topStory > 0) {; // Hide if topMore empty?>
  <div class="topStory">
    <div class="bigCol">
      <?php

	$id = $row_topStory['id'];
	$title = $row_topStory['article_title'];
	$summary = $row_topStory['article_summary'];
		$link = site_info('url') . "/article.php?id=$id";
	$author = $row_topStory['article_author'];
	$author_title = $row_topStory['article_author_title'];
	
	echo "<h2><a href=\"$link\" title=\"$title\">$title</a></h2>\n";
	echo "<p class=\"summary\">$summary</p>\n";
	echo "<p class=\"moreLink\"><a href=\"$link\" title=\"$title\"><strong>Read More</strong></a></p>\n";
	?>
    </div>
    <div class="smallCol">
      <div class="sectionSidebar">
	  <p><strong><a href="mailto:<?php echo section_info('editor_email', $section); ?>"><?php echo section_info('editor', $section); ?></a></strong><br />
	  <em><?php echo section_info('editor_title',$section); ?></em></p>
  	  <div class="divider"><hr /></div>
	  <?php echo autop(section_info('sidebar',$section)); ?>
	  <div class="divider"><hr /></div>
	  <p><a href="<?php echo "feed.php?id=$section"; ?>" class="rssFeed"><?php echo section_info('name',$section); ?></a></p>
	  </div>
    </div>
    <?php }; // End hide if topStory empty ?>
	<?php if ($totalRows_topMore > 0) {; // Hide if topMore empty ?>
    <div class="colWrap">
      <div class="fullCol">
        <hr />
        <?php
    do {
		$id = $row_topMore['id'];
		$title = $row_topMore['article_title'];
		$summary = $row_topMore['article_summary'];
		$link = site_info('url') . "/article.php?id=$id";
	    echo "<div class=\"otherStory\">\n";
		echo "<h3><a href=\"$link\" title=\"$title\">$title</a></h3>\n";
		echo "<p class=\"summary\">$summary</p>\n";
		echo "<p class=\"moreLink\"><a href=\"$link\" title=\"$title\">Read More</a></p>\n";
	    echo "</div>\n";
		
} while ($row_topMore = mysql_fetch_assoc($CM_Array));
?>
      </div>
    </div>
    <?php }; // End hide if topMore empty ?>
  </div>
  <?php get_summaries(); ?>
</div>
<?php get_footer(); ?>
