<?php
require_once('includes/config.php');

$string = strip_tags($_GET['s']);
$index = strip_tags($_GET['index']);
$sort_by = strip_tags($_GET['sort_by']);
$sort_dir = strip_tags($_GET['sort_dir']);
$boolean = strip_tags($_GET['boolean']);

if ($_GET['s_by'] == "author") {
	$index = "author";
	$sort_by = "article_publish";
	$sort_dir = "DESC";
	$boolean = "true";
};

if ($_GET['s_by'] == "keyword") {
	$index = "keyword";
	$sort_by = "article_publish";
	$sort_dir = "DESC";
	$boolean = "true";
};

// Query
$query_CM_Array = create_search_query($string,$index,$sort_by,$sort_dir,$boolean);

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

$pageTitle = " - Searching for [" . htmlentities($string) . "]";
$sectionTitle = "Search";

?>
<?php get_header($topBar,$pageTitle,$sectionTitle); ?>

<div id="content">
  <div class="colWrap">
    <form action="<?php echo $PHP_SELF; ?>" method="get">
      <div class="biggerCol">
        <h3>Search <em>
          <?php echo site_info('name'); ?>
          </em> Archives</h3>
        <p>
          <label for="s">Search for:</label>
          <input type="text" name="s" value="<?php echo stripslashes(htmlentities($string)); ?>"/>
        </p>
        <p>
          <input type="submit" name="search" value="Search" />
        </p>
      </div>
      <div class="smallerCol">
        <p>
          <label for="index">Search within:</label>
          <select name="index" id="index">
            <option value="article" <?php if (!(strcmp("article", "$index"))) {echo "SELECTED";} ?>>Article &amp; Headlines</option>
            <option value="author" <?php if (!(strcmp("author", "$index"))) {echo "SELECTED";} ?>>Authors</option>
            <option value="keyword" <?php if (!(strcmp("keyword", "$index"))) {echo "SELECTED";} ?>>Keywords</option>
          </select>
        </p>
        <p>
          <label for="sort_by">Sort by:</label>
          <select name="sort_by" id="sort_by">
            <option value="article_publish" <?php if (!(strcmp("article_publish", "$sort_by"))) {echo "SELECTED";} ?>>Publish
            Date</option>
            <option value="article_edit" <?php if (!(strcmp("article_edit", "$sort_by"))) {echo "SELECTED";} ?>>Edited
            Date</option>
            <option value="article_title" <?php if (!(strcmp("article_title", "$sort_by"))) {echo "SELECTED";} ?>>Headline</option>
            <option value="article_subtitle" <?php if (!(strcmp("article_subtitle", "$sort_by"))) {echo "SELECTED";} ?>>Sub-Headline</option>
            <option value="article_author" <?php if (!(strcmp("article_author", "$sort_by"))) {echo "SELECTED";} ?>>Author
            Name</option>
          </select>
          <select name="sort_dir" id="sort_dir">
            <option value="DESC" <?php if (!(strcmp("DESC", "$sort_dir"))) {echo "SELECTED";} ?>>Descending</option>
            <option value="ASC" <?php if (!(strcmp("ASC", "$sort_dir"))) {echo "SELECTED";} ?>>Ascending</option>
          </select>
        </p>
        <p>
          <label for="boolean">Search Mode:</label>
          <select name="boolean" id="boolean">
            <option value="false" <?php if (!(strcmp("false", "$boolean"))) {echo "SELECTED";} ?>>Normal</option>
            <option value="true" <?php if (!(strcmp("true", "$boolean"))) {echo "SELECTED";} ?>>Advanced
            (Boolean)</option>
          </select>
        </p>
        <p>
          <input type="submit" name="search" value="Search" />
        </p>
      </div>
    </form>
  </div>
  <div class="colWrap">
    <div class="fullCol">
      <table>
        <tr>
          <th>Headline</th>
          <th>Author</th>
          <th>Published</th>
          <th>Words</th>
        </tr>
        <?php
if ($totalRows_CM_Array > 0) {
	do {
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
		$word_count = $row_CM_Array['article_word_count'];
		
		echo "<tr>";
		echo "<td><a href=\"" . site_info('url') .  "/article.php?id=$id\">$title</a></td>";
		echo "<td>$author</td>";
		echo "<td>$published</td>";
		echo "<td>$word_count</td>";
		echo "</tr>\n";
		
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
	
} else {;
	echo "Nothing found for " . stripslashes(htmlentities($string)) . ".";
};
?>
      </table>
      <p>You search found <?php echo $totalRows_CM_Array; ?> article<?php if ($totalRows_CM_Array != 1) {echo "s";}; ?>.</p>
    </div>
  </div>
</div>
<?php get_footer(); ?>
