<?php
require_once('includes/functions.php');

$id = $_GET['id'];
$next_issue = next_issue("id");

// Query
$query_CM_Array = "SELECT * FROM cm_articles ";
$query_CM_Array .= " WHERE id = $id AND issue_id < $next_issue;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
// Define variables
$id = $row_CM_Array['id'];
$section = $row_CM_Array['section_id'];
$issue = $row_CM_Array['issue_id'];
$title = $row_CM_Array['article_title'];
$subtitle = $row_CM_Array['article_subtitle'];
$summary = $row_CM_Array['article_summary'];
$text = $row_CM_Array['article_text'];
$keywords = $row_CM_Array['article_keywords'];
$author = $row_CM_Array['article_author'];
$author_title = $row_CM_Array['article_author_title'];
$priority = $row_CM_Array['article_priority']; 
$published = $row_CM_Array['article_publish'];
$edited = $row_CM_Array['article_edit'];

/* Header Configuration */
$topBar = "<span class=\"floatLeft\"><strong>Published</strong> $published</span>";
if ($published < $edited) {;
	$topBar .= " <span class=\"floatRight\"><strong>Updated</strong> $edited</span>";
};
$pageTitle = " &raquo; $title";
$sectionTitle = $section;

?>
<?php get_header($topBar,$pageTitle,$section); ?>

<div id="content" class="fullStory">
  <div class="colWrap">
    <div class="fullCol">
      <div class="inlineCol">
        <div class="articleToolbar">
          <ul>
		    <li><strong>Tools:</strong></li>
            <li><a href="javascript:window.print()" class="printArticle">Print</a></li>
            <li><a href="send.php?id=<?php echo $id; ?>" class="emailArticle">E-mail</a></li>
            <li><a href="javascript:w=window;d=document;var u;s='';ds=d.selection;if(ds&&ds!=u){if(ds.createRange()!=u){s=ds.createRange().text;}}else if(d.getSelection!=u){s=d.getSelection()+'';}else if(w.getSelection!=u){s=w.getSelection()+'';} if(s.length<2){h=String(w.location.href);if(h.length==0||h.substring(0,6)=='about:'){s=prompt('Technorati Realtime Search for:',s);}else{s=w.location.href;}}if(s!=null)w.location='http://technorati.com/search/'+escape(s)+'?sub=toolsearch';void(1);" class="discussArticle">Search for Blogs</a></li>
          </ul>
        </div>
        <?php if (image_media_count($id) > 0) {; ?>
        <div class="mediaImage">
          <?php image_media($id); ?>
        </div>
        <?php }; ?>
        <?php if (related_media_count($id) > 0) {; ?>
        <div class="mediaRelated">
          <h4>Related:</h4>
          <ul>
            <?php related_media($id); ?>
          </ul>
        </div>
        <?php }; ?>
        <div class="sectionHeadlines">
          <h4><a href="<?php echo section_info('url',$section); ?>">More <?php echo htmlentities(section_info('name',$section)); ?> Headlines</a></h4>
          <ul>
            <?php section_headlines($section,$issue); ?>
          </ul>
        </div>
      </div>
      <h2><?php echo $title; ?></h2>
      <?php if ($subtitle != "") {; echo "<h3>$subtitle</h3>"; }; ?>
      <p class="byline">
        <?php
    echo "<a href=\"" . site_info('url') . "/search.php?s=" . urlencode($author) . "&amp;s_by=author\">";	
	echo "<strong>" . htmlentities($author) . "</strong></a>";
	if ($author_title != "") {;
		echo ", <em>" . htmlentities($author_title) . "</em>";
	};
	?>
      </p>
      <?php echo autop($text); ?> </div>
  </div>
</div>
<?php get_footer(); ?>
