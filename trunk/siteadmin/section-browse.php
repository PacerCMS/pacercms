<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "section-browse";
$cmodule = "section-edit";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);


get_cm_header();

?>

<h2>Section Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "updated") { echo "<p class=\"infoMessage\">Section updated.</p>"; }
if ($msg == "added") { echo "<p class=\"infoMessage\">Section added.</p>"; }
if ($msg == "deleted") { echo "<p class=\"infoMessage\">Section deleted.</p>"; }
?>
<form>
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Browse Sections</legend>
    <div class="actionMenu"><ul>
    <li><strong>Section Options:</strong></li>
    <li><a href="<?php echo "$cmodule.php?action=new"; ?>">Add New Section</a></li> 
    </ul></div>

  <table class="<?php echo "$module-table"; ?>">
    <tr>
      <th><acronym title="Assigned Priority">AP</acronym></th>
      <th>Section</th>
      <th>Editor</th>
      <th>Tools</th>
    </tr>
    <?php

// Database Query
$query = "SELECT * FROM cm_sections ORDER BY section_priority;";

// Run Query
$result = cm_run_query($query);
$records = $result->GetArray();

foreach ($records as $record)
{
    $id = $record['id'];
    $section_name = $record['section_name'];
    $section_url = $record['section_url'];
    $section_editor = $record['section_editor'];
    $section_editor_title = $record['section_editor_title'];
    $section_priority = $record['section_priority'];
?>
    <tr>
      <td><?php echo $section_priority; ?></td>
      <td><a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo $section_name; ?></a></td>
      <td><?php echo $section_editor; ?><br>
        <?php echo $section_editor_title; ?></td>
      <td class="actionMenu"><ul class="center">
          <li><a href="<?php echo $section_url; ?>">Preview</a></li>
          <li><a href="section-edit.php?id=<?php echo $id; ?>">Edit</a></li>
          <li class="command-delete"><a href="section-edit.php?id=<?php echo $id; ?>#delete">Delete</a></li>
        </ul>
      </td>
    </tr>
<?php } ?>
  </table>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
