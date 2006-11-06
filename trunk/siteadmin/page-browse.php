<?php include('cm-includes/config.php'); ?>
<?php
$module = "page-browse";
$cmodule = "page-edit";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Database Query
$query_CM_Array = "SELECT * FROM cm_pages ORDER BY page_title DESC;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Page Manager</h2>
<?php $msg = $_GET['msg'];
if ($msg == "added") {; echo "<p class=\"systemMessage\">Page added.</p>"; };
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Page updated.</p>"; };
if ($msg == "deleted") {; echo "<p class=\"systemMessage\">Page deleted.</p>"; };
?>

<?php if ($totalRows_CM_Array > 0) {; // If there are any pages ?>

<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-table"; ?>">
  <legend>Page Browser</legend>
  <div class="actionMenu"><ul>
  <li><strong>Actions</strong></li>
  <li><a href="<?php echo "$cmodule.php?action=add"; ?>">Add New Page</a></li> 
  </ul></div>
  <table>
    <tr>
      <th>Title</th>
      <th>Edited</th>
      <th>Tools</th>
    </tr>
    <?php

do {;
	$id = $row_CM_Array['id'];
	$title = $row_CM_Array['page_title'];
	$edited = $row_CM_Array['page_edited'];
?>
    <tr>
      <td><a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo $title; ?></a></p>
	  
	  </td>
      <td><?php echo $edited; ?></td>
      <td class="actionMenu" nowrap><ul class="center">
          <li><a href="<?php echo "$cmodule.php?id=$id#preview"; ?>">Preview</a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id"; ?>">Edit</a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id#delete"; ?>">Delete</a></li>
        </ul>
      </td>
    </tr>
    <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
  </table> 
  </fieldset>
</form>

<?php } else {; ?>
	<p>You are not currently using pages. Why not <a href="<?php echo "$cmodule.php?action=add"; ?>">add one</a> now?</p>
<?php }; ?>

<?php get_cm_footer(); ?>
