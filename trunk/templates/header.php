<?php
	header("Content-type: text/html; charset=UTF-8");
	if ($topBar == "") { $topBar = "<em><strong>Serving the University of Tennessee at Martin since 1928</strong></em>"; };
?>
<?php echo "<" . "?xml version=\"1.0\" encoding=\"utf-8\"?" . ">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>
<?php echo site_info('name') . strip_tags($pageTitle); ?>
</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="keywords" content="UTM, The Pacer, Student Newspaper, Campus Newspaper, University of Tennessee at Martin" />
<meta name="description" content="The Pacer is an editorially independent, student-run newspaper published weekly and distributed free to the student body of the University of Tennessee at Martin." />
<meta name="geo.position" content="36.15;-88.45" />
<meta name="geo.region" content="US-TN" />
<meta name="geo.placename" content="Martin, Tennessee" />
<meta name="dmoz.id" content="Top/News/Colleges_and_Universities/Newspapers/United_States/Tennessee/" />

<link href="<?php echo site_info('url'); ?>/favicon.ico" rel="shortcut icon" type="image/icon" title="Shortcut Icon" />

<link href="<?php echo site_info('url'); ?>/templates/style-screen.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo site_info('url'); ?>/templates/style-print.css" rel="stylesheet" type="text/css" media="print" />

<link href="<?php echo site_info('url'); ?>/rss.xml" rel="alternate" type="application/rss+xml" title="The Pacer - Headlines (RSS)" />
<link href="<?php echo site_info('url'); ?>/viewpoints/rss.xml" rel="alternate" type="application/rss+xml" title="The Pacer - Viewpoints (RSS)" />
<link href="<?php echo site_info('url'); ?>/news/rss.xml" rel="alternate" type="application/rss+xml" title="The Pacer - Campus News (RSS)" />
<link href="<?php echo site_info('url'); ?>/entertainment/rss.xml" rel="alternate" type="application/rss+xml" title="The Pacer - Arts &amp; Entertainment (RSS)" />
<link href="<?php echo site_info('url'); ?>/sports/rss.xml" rel="alternate" type="application/rss+xml" title="The Pacer - Sports (RSS)" />

<script src="<?php echo site_info('url'); ?>/includes/functions.js" type="text/javascript"></script>

</head>
<body id="the-body">
<div id="header">
  <div id="nameplate">
    <h1><?php echo site_info('name'); ?></h1>
  </div>
  <?php if (is_numeric($sectionTitle)) {; ?>
  <h2 id="sectionNameplate"><a href="<?php echo section_info('url', $sectionTitle); ?>"><?php echo htmlentities(section_info('name', $sectionTitle)); ?></a></h2>
  <?php } else {;?>
	<h2 id="sectionNameplate"><?php echo htmlentities($sectionTitle); ?></h2>
  <?php }; ?>
</div>
<div id="pageTop">
  <div id="siteUpdate">
    <p><strong>Last Edition: </strong><br />
      <?php echo current_issue('date'); ?></p>
  </div>
  <div id="lastUpdate">
    <p><?php echo $topBar; ?></p>
  </div>
  <div id="searchBox">
    <form action="search.php" method="get">
      <p>
        <label for="s">Search</label>
        <input type="text" name="s" id="s" class="textField" />
        <input type="submit" name="b" id="b" value="Search" class="button" />
		<input type="hidden" name="index" id="index" value="article" />
		<input type="hidden" name="sort_by" id="sort_by" value="article_publish" />
		<input type="hidden" name="sort_dir" id="sort_dir" value="DESC" />
		<input type="hidden" name="boolean" id="boolean" value="false" />
      </p>
    </form>
  </div>
</div>
<div id="mainWrap">
  <?php get_sidebar(); ?>