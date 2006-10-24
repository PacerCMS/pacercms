<?php
require_once('includes/config.php');

// Switch sections
if (is_numeric($_GET['id'])) {
	$section = $_GET['id'];
} else {
	$section = 1; // Default
};

// Other Settings
$issue = current_issue('id');
$feed_tagline = site_info('description');
$feed_image = site_info('url') . "/templates/images/feed_logo.png";

// Database Connection
$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
mysql_select_db(DB_DATABASE, $CM_MYSQL);	
// Database Query
$query_CM_Array = "SELECT *, DATE_FORMAT(article_publish, '%a, %d %b %Y %H:%i:%S CST') AS nice_date FROM cm_articles";	
$query_CM_Array .= " WHERE section_id = '$section' AND issue_id = '$issue'";
$query_CM_Array .= " ORDER BY article_priority ASC;";

// Run Query
$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

?>
<?php
header("Content-type: text/xml; charset=UTF-8");
echo "<?xml version=\"1.0\" ?><rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\" xmlns:admin=\"http://webns.net/mvcb/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\">";
?>
<channel>
	<title><?php echo site_info('name'); ?><?php if ($section != 1) {echo " - " . htmlentities(section_info('name',$section)); }; ?></title>
	<description><?php echo $feed_tagline; ?></description> 
	<language>en-us</language> 
	<link><?php echo site_info('url'); ?></link> 
	<image>
		<url><?php echo $feed_image; ?></url>
		<title><?php echo site_info('name'); ?></title>
		<link><?php echo site_info('url'); ?></link>
		<height>88</height>
		<width>31</width> 
		<description><?php echo $feed_tagline; ?></description>
	</image>

<?php
do {
	$id = $row_CM_Array['id'];
	$section_id = $row_CM_Array['section_id'];
	$title = $row_CM_Array['article_title'];
	$section = section_info('name', $section_id);
	$link = site_info('url') . "/article.php?id=$id";
	$summary = $row_CM_Array['article_summary'];
	$published = $row_CM_Array['nice_date'];
?>

	<item>
		<title><![CDATA[<?php echo $title; ?>]]></title>
		<category><![CDATA[<?php echo $section; ?>]]></category>
		<link><?php echo $link; ?></link>
		<description><![CDATA[<?php echo $summary; ?>]]></description>
		<pubDate><?php echo $published; ?></pubDate>
	</item>

<?php
} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
?>
</channel>
</rss>
