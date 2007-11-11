<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "page-browse";
$cmodule = "page-edit";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Database Query
$query = "SELECT * FROM cm_pages ORDER BY page_title DESC;";

// Run Query
$result = cm_run_query($query);
$records = $result->GetArray();

get_cm_header();

?>

<h2>Page Manager</h2>
<?php $msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"infoMessage\">Page added.</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">Page updated.</p>"; }
if ($msg == "deleted") { echo "<p class=\"alertMessage\">Page deleted.</p>"; }
?>

<?php
if ($result->RecordCount() > 0)
{
?>

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

foreach ($records as $record)
{
	$id = $record['id'];
	$title = $record['page_title'];
	$edited = $record['page_edited'];
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
<?php }  ?>
  </table> 
  </fieldset>
</form>

<?php } else { ?>
	<p>You are not currently using pages. <a href="<?php echo "$cmodule.php?action=add"; ?>">Add a Page</a></p>
<?php } ?>

<?php get_cm_footer(); ?>
