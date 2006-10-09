<?php
include_once('includes/config.php');

$section = 1;
$issue = current_issue('id');

// Database Connection
$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
mysql_select_db(DB_DATABASE, $CM_MYSQL);	
// Database Query
$query_CM_Array = "SELECT * FROM cm_articles";	
$query_CM_Array .= " WHERE section_id = '1' AND issue_id = '$issue'";
$query_CM_Array .= " ORDER BY article_priority ASC";
$query_CM_Array .= " LIMIT 0,1;";

// Run Query
$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_topStory = mysql_fetch_assoc($CM_Array);
$totalRows_topStory = mysql_num_rows($CM_Array);

// Database Query
$query_CM_Array = "SELECT * FROM cm_articles";	
$query_CM_Array .= " WHERE section_id = '1' AND issue_id = '$issue'";
$query_CM_Array .= " ORDER BY article_priority ASC";
$query_CM_Array .= " LIMIT 1,99;";

// Run Query
$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_topMore = mysql_fetch_assoc($CM_Array);
$totalRows_topMore = mysql_num_rows($CM_Array);

/* Header Configuration */
$pageTitle = " - UTM's Student Newspaper";
?>
<?php get_header($topBar,$pageTitle,$sectionTitle); ?>

<div id="content">
<?php 
	$id = $row_topStory['id'];
	$title = $row_topStory['article_title'];
	$summary = $row_topStory['article_summary'];
	$author = $row_topStory['article_author'];
	$author_title = $row_topStory['article_author_title'];
	$link = site_info('url') . "/$id.htm";
	$issue_id = $row_topStory['issue_id'];
	$published = $row_topStory['article_publish'];
	$issue_date = issue_info("date",$issue_id) . " 00:00:00";
	if ($published > $issue_date) { $is_breaking = "true"; } else { unset($is_breaking); };
?>

<?php if (image_media_count($id) > 0) {; ?>

  <div class="topStory">
    <div class="biggerCol">
	<?php	

	// Breaking news?
	if ($is_breaking == "true") {;
		echo "<h5 class=\"breakingNews\">Breaking News</h5>";
	// Nope.
	} else {;
    	echo "<h5>&mdash; TOP STORY &mdash;</h5>";
	};	

	echo "<h2><a href=\"$link\" title=\"$title\">$title</a></h2>\n";
    echo "<p class=\"byline\">";
    echo "<a href=\"search.php?s=" . urlencode($author) . "&amp;s_by=author\">";
	echo "<strong>" . htmlentities($author) . "</strong></a>";
	if ($author_title != "") {;
		echo ", <em>" . htmlentities($author_title) . "</em>";
	};
	echo "<p class=\"summary\">$summary</p>\n";
	echo "<p class=\"moreLink\"><a href=\"$link\" title=\"$title\"><strong>Read More</strong></a></p>\n";
	?>
    </div>
    <div class="smallerCol">
      <div class="mediaImage">
        <?php image_media($id); ?>
      </div>
    </div>

	<?php  } else {; ?>

  <div class="topStory">
    <div class="fullCol">
	<?php
	// Breaking news?
	if ($is_breaking == "true") {;
		echo "<h5 class=\"breakingNews\">Breaking News</h5>";
	// Nope.
	} else {;
    	echo "<h5>&mdash; TOP STORY &mdash;</h5>";
	};	

	echo "<h2><a href=\"$link\" title=\"$title\">$title</a></h2>\n";
	echo "<p class=\"summary\">$summary</p>\n";
	echo "<p class=\"moreLink\"><a href=\"$link\" title=\"$title\"><strong>Read More</strong></a></p>\n";
	?>
    </div>

	<?php }; ?>
	
    <hr />
    <div class="colWrap">
      <div class="bigCol">
        <?php
    do {
		$id = $row_topMore['id'];
		$title = $row_topMore['article_title'];
		$summary = $row_topMore['article_summary'];
		$link = site_info('url') . "/$id.htm";
	    echo "<div class=\"otherStory\">\n";
		echo "<h3><a href=\"$link\" title=\"$title\">$title</a></h3>\n";
		echo "<p class=\"summary\">$summary</p>\n";
		echo "<p class=\"moreLink\"><a href=\"$link\" title=\"$title\">Read More</a></p>\n";
	    echo "</div>\n";
		
} while ($row_topMore = mysql_fetch_assoc($CM_Array));
?>
      </div>
      <div class="smallCol">
        <div class="homeSidebar">
          <?php

$active_poll = site_info('active_poll');

if ($active_poll > 0) {;

	$question = get_ballot('question',$active_poll);
	$r1 = get_ballot('response_1',$active_poll);
	$r2 = get_ballot('response_2',$active_poll);
	$r3 = get_ballot('response_3',$active_poll);
	$r4 = get_ballot('response_4',$active_poll);
	$r5 = get_ballot('response_5',$active_poll);
	$r6 = get_ballot('response_6',$active_poll);
	$r7 = get_ballot('response_7',$active_poll);
	$r8 = get_ballot('response_8',$active_poll);
	$r9 = get_ballot('response_9',$active_poll);
	$r10 = get_ballot('response_10',$active_poll);

?>
          <form action="poll.php" method="post" class="sitePoll">
            <h4>&mdash;&nbsp;Pacer&nbsp;Poll&nbsp;&mdash;</h4>
            <p class="question"><strong> <?php echo $question; ?> </strong></p>
            <ul>
              <?php if ($r1 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="1" id="option-1" />
                <label for="option-1"> <?php echo htmlentities($r1); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r2 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="2" id="option-2" />
                <label for="option-2"> <?php echo htmlentities($r2); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r3 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="3" id="option-3" />
                <label for="option-3"> <?php echo htmlentities($r3); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r4 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="4" id="option-4" />
                <label for="option-4"> <?php echo htmlentities($r4); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r5 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="5" id="option-5" />
                <label for="option-5"> <?php echo htmlentities($r5); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r6 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="6" id="option-6" />
                <label for="option-6"> <?php echo htmlentities($r6); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r7 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="7" id="option-7" />
                <label for="option-7"> <?php echo htmlentities($r7); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r8 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="8" id="option-8" />
                <label for="option-8"> <?php echo htmlentities($r8); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r9 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="9" id="option-9" />
                <label for="option-9"> <?php echo htmlentities($r9); ?> </label>
              </li>
              <?php }; ?>
              <?php if ($r10 != "") {; ?>
              <li>
                <input name="vote-cast" type="radio" value="10" id="option-10" />
                <label for="option-10"> <?php echo htmlentities($r10); ?> </label>
              </li>
              <?php }; ?>
            </ul>
            <p class="submit">
              <input type="submit" id="submit" value="Cast Vote" class="button" />
              <input type="hidden" name="poll-id" value="{{poll_id}}" />
            </p>
          </form>
          <div class="divider">
            <hr />
          </div>
		  <?php }; // End show poll ?>
          <?php echo autop(section_info('sidebar',$section)); ?>
          <div class="divider">
            <hr />
          </div>
          <p><a href="<?php echo "feed.php?id=$section"; ?>" class="rssFeed">Top
              Stories</a> <small>(What's this?)</small></p>
        </div>
      </div>
    </div>
  </div>
  <?php get_summaries(); ?>
</div>
<?php get_footer(); ?>
