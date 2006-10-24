<?php

include_once('includes/config.php');

$current_volume = current_issue('volume'); 

// Switch issues
if (is_numeric($_GET['volume'])) {;
	$volume = $_GET['volume'];	
	setcookie("archive-volume", $volume);
	header("Location: " . site_info('url') . "/archives/");
	exit;
};

// Read Issue Cookie
if ($_COOKIE["archive-volume"] == "") {;
	setcookie("archive-volume", $current_volume); // Current Issue
	$volume = $current_volume;
} else {;
	$volume = $_COOKIE["archive-volume"];
};

$issue_date = $_GET['issue'];

// Create list of volumes
$query_CM_Array = "SELECT DISTINCT(issue_volume), COUNT(id) AS issue_count";	
$query_CM_Array .= " FROM cm_issues GROUP BY issue_volume";
$query_CM_Array .= " ORDER BY issue_volume DESC;";
$CM_ListVolumes  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
$row_ListVolumes  = mysql_fetch_assoc($CM_ListVolumes);
$totalRows_ListVolumes = mysql_num_rows($CM_ListVolumes);

// Create list of issues
$query_CM_Array = "SELECT *, DATE_FORMAT(issue_date, '%b. %e, %Y') AS nice_date";
$query_CM_Array .= " FROM cm_issues";
$query_CM_Array .= " WHERE issue_volume = '$volume'";
$query_CM_Array .= " ORDER BY issue_date ASC;";
$CM_ListIssues = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_ListIssues = mysql_fetch_assoc($CM_ListIssues);
$totalRows_ListIssues = mysql_num_rows($CM_ListIssues);

// Checks if in "view issue" mode
if ($issue_date != "") {

	$query_CM_Array = "SELECT id, issue_volume";
	$query_CM_Array .= " FROM cm_issues";
	$query_CM_Array .= " WHERE issue_date = '$issue_date'";
	$CM_LookupIssue = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_LookupIssue = mysql_fetch_assoc($CM_LookupIssue);

	$issue = $row_LookupIssue['id'];

};

// Makes sure something loaded.
if (is_numeric($issue)) {
	
	$query_CM_Array = "SELECT *, cm_articles.id AS article_id, DATE_FORMAT(issue_date, '%M %e, %Y') AS nice_date";
	$query_CM_Array .= " FROM cm_articles INNER JOIN (cm_sections, cm_issues) ON (cm_sections.id = cm_articles.section_id AND cm_issues.id = cm_articles.issue_id)";
	$query_CM_Array .= " WHERE issue_id = '$issue'";
	$query_CM_Array .= " ORDER BY section_priority ASC, article_priority ASC;";
	$CM_ListArticles  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_ListArticles  = mysql_fetch_assoc($CM_ListArticles);
	$totalRows_ListArticles = mysql_num_rows($CM_ListArticles);

};

$topBar = "";
$sectionTitle = "Archives";
$pageTitle = " &raquo; Archives";

?>
<?php get_header($topBar,$pageTitle,$sectionTitle); ?>

<div id="content">
<div class="colWrap">
<div class="bigCol">
<?php
if ($totalRows_ListArticles > 0) {;
?>
<h3><?php echo $row_ListArticles['nice_date']; ?></h3>

  <table>
    <tr>
      <th>Headline</th>
      <th>Author</th>
      <th>Section</th>
      <th>Words</th>
    </tr>
    <?php
	do {
		// Define variables
		$id = $row_ListArticles['article_id'];
		$section = $row_ListArticles['section_name'];
		$issue = $row_ListArticles['issue_id'];
		$title = $row_ListArticles['article_title'];
		$subtitle = $row_ListArticles['article_subtitle'];
		$summary = $row_ListArticles['article_summary'];
		$text = $row_ListArticles['article_text'];
		$keywords = $row_ListArticles['article_keywords'];
		$author = $row_ListArticles['article_author'];
		$author_title = $row_ListArticles['article_author_title'];
		$priority = $row_ListArticles['article_priority']; 
		$published = $row_ListArticles['article_publish'];
		$edited = $row_ListArticles['article_edit'];
		$word_count = $row_ListArticles['article_word_count'];
		
		echo "<tr>";
		echo "<td><a href=\"" . site_info('url') . "/article.php?id=$id\">$title</a></td>";
		echo "<td>$author</td>";
		echo "<td>" . htmlentities($section) . "</td>";
		echo "<td>$word_count</td>";
		echo "</tr>\n";
		
	} while ($row_ListArticles = mysql_fetch_assoc($CM_ListArticles));
?>
  </table>

<?php } else {; // End hide listArticles ?>
<h3>Welcome to <em><?php echo site_info('name'); ?></em> Archives</h3>
<p>Select an issue to the right to begin browsing through the most-recent volume of the newspaper. If you are looking for an article from a previous year, use the "Volumes" list below it to see any article published since Fall 2002. If you need any help, just <a href"mailto:<?php echo site_info('email'); ?>">drop us an email</a>.</p>
<?php }; ?>
</div>
<div class="smallCol">
<?php
if ($totalRows_ListIssues > 0) {;
?>

<h3>Volume <?php echo $volume; ?></h3>
  <table>
    <tr>
      <th>Issue</th>
	  <th>Date</th>
    </tr>
    <?php
	do {
		// Define variables
		$id = $row_ListIssues['id'];
		$number = $row_ListIssues['issue_number'];
		$nice_date = $row_ListIssues['nice_date'];
		$date = $row_ListIssues['issue_date'];
		$circulation = $row_ListIssues['issue_circulation'];
		
		echo "<tr>";
		echo "<td>$number</td>";
		echo "<td><a href=\"" . site_info('url') . "/archives.php?issue=$date\">$nice_date</a></td>";
		echo "</tr>\n";
		
	} while ($row_ListIssues = mysql_fetch_assoc($CM_ListIssues));
?>
  </table>

<?php }; // End hide listIssues ?>

<h3>Volumes in our Internet Archives</h3>
<ul>
<?php
do {;
	$volume = $row_ListVolumes['issue_volume'];
	$count = $row_ListVolumes['issue_count'];
	
	echo "<li><a href=\"" . site_info('url') . "/archives.php?volume=$volume\">Vol. $volume</a> ($count issues)</li>";

} while ($row_ListVolumes = mysql_fetch_assoc($CM_ListVolumes));
?>
</ul>
</div>
</div>
</div>
<?php get_footer(); ?>
