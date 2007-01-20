<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "section-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Section Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "updated") { echo "<p class=\"infoMessage\">Section updated.</p>"; }
?>
<form>
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Browse Sections</legend>
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
$result  = mysql_query($query, $CM_MYSQL) or die(mysql_error());
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);

do {
$id = $result_array['id'];
$section_name = $result_array['section_name'];
$section_url = $result_array['section_url'];
$section_editor = $result_array['section_editor'];
$section_editor_title = $result_array['section_editor_title'];
$section_priority = $result_array['section_priority'];
?>
    <tr>
      <td><?php echo $section_priority; ?></td>
      <td><a href="section-edit.php?id=<?php echo $id; ?>"><?php echo $section_name; ?></a></td>
      <td><?php echo $section_editor; ?><br>
        <?php echo $section_editor_title; ?></td>
      <td class="actionMenu"><ul class="center">
          <li><a href="<?php echo $section_url; ?>">Preview</a></li>
          <li><a href="section-edit.php?id=<?php echo $id; ?>">Edit</a></li>
        </ul>
      </td>
    </tr>
    <? } while ($result_array = mysql_fetch_assoc($result)); ?>
  </table>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
