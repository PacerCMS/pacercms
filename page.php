<?php
include_once('includes/functions.php');

$id = $_GET['id'];

// Query
$query_CM_Array = "SELECT * FROM cm_pages ";
$query_CM_Array .= " WHERE id = '$id';";

// Run Query
$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

// Define variables
$id = $row_CM_Array['id'];
$title = $row_CM_Array['page_title'];
$shortTitle = $row_CM_Array['page_short_title'];
$text = $row_CM_Array['page_text'];
$sideText = $row_CM_Array['page_side_text'];

// Header Configuration
$topBar = "";
$pageTitle = " &raquo; $title";
$section = $shortTitle;

get_header($topBar,$pageTitle,$section);

?>
<div id="content">

<?php if (empty($sideText)) {; ?>


  <h2 class="sectionNameplate">
    <?php echo $title; ?>
  </h2>
  <?php echo autop($text); ?>


<?php } else {; ?>

<div class="colWrap">
<div class="bigCol">
  <h2 class="sectionNameplate">
    <?php echo $title; ?>
  </h2>
  <?php echo autop($text); ?>
</div>
<div class="smallCol">
  <?php echo autop($sideText); ?>
</div>

</div>



<?php }; ?>

</div>

<?php get_footer(); ?>
