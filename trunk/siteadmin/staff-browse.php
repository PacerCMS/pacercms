<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "staff-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);


get_cm_header();

?>


<h2><?php echo gettext("Staff Manager"); ?></h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"infoMessage\">" . gettext("User added.") . "</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">" . gettext("User updated.") . "</p>"; }
if ($msg == "deleted") { echo "<p class=\"alertMessage\">" . gettext("User deleted.") . "</p>"; }
if ($msg == "access-updated") { echo "<p class=\"infoMessage\">" . gettext("User access updated.") . "</p>"; }
?>
<table class="<?php echo "$module-table"; ?>">
  <tr>
    <th><?php echo gettext("Name"); ?></th>
    <th><?php echo gettext("Job Title"); ?></th>
    <th><?php echo gettext("E-mail"); ?></th>
    <th><?php echo gettext("Tools"); ?></th>
  </tr>
<?php

$query = "SELECT * FROM cm_users ORDER BY user_last_name ASC, user_first_name ASC;";

$result = cm_run_query($query);
$records = $result->GetArray();

foreach ($records as $record)
{
    $id = $record['id'];
    $first_name = $record['user_first_name'];
    $last_name = $record['user_last_name'];
    $job_title = $record['user_job_title'];
    $email = $record['user_email'];
?>
  <tr>
    <td><?php echo "$first_name $last_name"; ?></td>
    <td><?php echo $job_title; ?></td>
    <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
    <td class="actionMenu" nowrap>
      <ul>
        <li><a href="staff-edit.php?id=<?php echo $id; ?>" title="Edit User"><?php echo gettext("Edit"); ?></a></li>
        <li><a href="staff-access.php?id=<?php echo $id; ?>" title="Access"><?php echo gettext("Access"); ?></a></li>
        <?php if ($result_row_count > 1) { // Don't make it easy to delete last user ?>
        <li><a href="staff-edit.php?id=<?php echo $id; ?>#delete" title="Delete User"><?php echo gettext("Delete"); ?></a></li>
        <?php } ?>
      </ul>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td class="center" colspan="3"><strong><a href="staff-edit.php?action=add"><?php echo gettext("Add a User"); ?></a></strong></td>
    <td></td>
  </tr>
</table>
<?php get_cm_footer(); ?>
