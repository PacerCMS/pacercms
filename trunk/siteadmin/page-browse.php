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
if ($msg == "added") { echo "<p class=\"infoMessage\">" . gettext("Page added.") . "</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">" . gettext("Page updated.") . "</p>"; }
if ($msg == "deleted") { echo "<p class=\"alertMessage\">" . gettext("Page deleted.") . "</p>"; }
?>

<?php
if ($result->RecordCount() > 0)
{
?>

<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-table"; ?>">
  <legend><?php echo gettext("Page Browser"); ?></legend>
  <div class="actionMenu"><ul>
  <li><strong><?php echo gettext("Actions"); ?></strong></li>
  <li><a href="<?php echo "$cmodule.php?action=add"; ?>"><?php echo gettext("Add New Page"); ?></a></li> 
  </ul></div>
  <table>
  <thead>
    <tr>
      <th><?php echo gettext("Title"); ?></th>
      <th><?php echo gettext("Edited"); ?></th>
      <th><?php echo gettext("Tools"); ?></th>
    </tr>
  </thead>
  <tbody>
<?php

foreach ($records as $record)
{
	$id = $record['id'];
	$title = $record['page_title'];
	$edited = date('m/d/Y h:i a', strtotime($record['page_edited']));
	
    if ($rowclass == 'even') { $rowclass = 'odd'; } else { $rowclass = 'even'; }

?>
    <tr class="<?php echo $rowclass; ?>">
      <td><a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo $title; ?></a></p>
	  
	  </td>
      <td><?php echo $edited; ?></td>
      <td class="actionMenu" nowrap><ul class="center">
          <li><a href="<?php echo "$cmodule.php?id=$id#preview"; ?>"><?php echo gettext("Preview"); ?></a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo gettext("Edit"); ?></a></li>
          <li><a href="<?php echo "$cmodule.php?id=$id#delete"; ?>"><?php echo gettext("Delete"); ?></a></li>
        </ul>
      </td>
    </tr>
<?php }  ?>
</tbody>
  </table> 
  </fieldset>
</form>

<?php } else { ?>
	<p><?php echo gettext("You are not currently using pages."); ?> <a href="<?php echo "$cmodule.php?action=add"; ?>"><?php echo gettext("Add a Page"); ?></a></p>
<?php } ?>

<?php get_cm_footer(); ?>
