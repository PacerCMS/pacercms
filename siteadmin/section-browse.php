<?php include('cm-includes/functions.php'); ?>
<?php
$module = "section-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Section Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Section updated.</p>"; };
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
$query_CM_Array = "SELECT * FROM cm_sections ORDER BY section_priority;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

do {;
$id = $row_CM_Array['id'];
$section_name = $row_CM_Array['section_name'];
$section_url = $row_CM_Array['section_url'];
$section_editor = $row_CM_Array['section_editor'];
$section_editor_title = $row_CM_Array['section_editor_title'];
$section_priority = $row_CM_Array['section_priority'];
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
    <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
  </table>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
