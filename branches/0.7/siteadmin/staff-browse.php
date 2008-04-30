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

<fieldset>
<legend>Browse Staff</legend>

<div class="actionMenu"><ul>
<li><strong><?php echo gettext("Staff Options:"); ?></strong></li>
<li><a href="staff-edit.php?action=add"><?php echo gettext("Add a User"); ?></a></li> 
</ul></div>



<table class="<?php echo "$module-table"; ?>">
<thead>
  <tr>
    <th><?php echo gettext("Name"); ?></th>
    <th><?php echo gettext("Job Title"); ?></th>
    <th><?php echo gettext("E-mail"); ?></th>
    <th><?php echo gettext("Tools"); ?></th>
  </tr>
</thead>
<tbody>
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

    if ($rowclass == 'even') { $rowclass = 'odd'; } else { $rowclass = 'even'; }

?>
  <tr class="<?php echo $rowclass; ?>">
    <td><?php echo "$first_name $last_name"; ?></td>
    <td><?php echo $job_title; ?></td>
    <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
    <td nowrap="nowrap" class="actionMenu" nowrap>
      <ul class="center">
        <li><a href="staff-edit.php?id=<?php echo $id; ?>" title="Edit User"><?php echo gettext("Edit"); ?></a></li>
        <li><a href="staff-access.php?id=<?php echo $id; ?>" title="Access"><?php echo gettext("Access"); ?></a></li>
        <?php if ($result_row_count > 1) { // Don't make it easy to delete last user ?>
        <li><a href="staff-edit.php?id=<?php echo $id; ?>#delete" title="Delete User"><?php echo gettext("Delete"); ?></a></li>
        <?php } ?>
      </ul>
    </td>
  </tr>
<?php } ?>
</tbody>
</table>
</fieldset>

<?php get_cm_footer(); ?>
