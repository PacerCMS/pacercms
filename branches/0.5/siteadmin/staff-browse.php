<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "staff-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);


get_cm_header();

?>


<h2>Staff Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") { echo "<p class=\"infoMessage\">User added.</p>"; }
if ($msg == "updated") { echo "<p class=\"infoMessage\">User updated.</p>"; }
if ($msg == "deleted") { echo "<p class=\"alertMessage\">User deleted.</p>"; }
if ($msg == "access-updated") { echo "<p class=\"infoMessage\">User access updated.</p>"; }
?>
<table class="<?php echo "$module-table"; ?>">
  <tr>
    <th>Name</th>
    <th>Job Title</th>
    <th>Email</th>
    <th>Tools</th>
  </tr>
  <?php

// Database Query
$query = "SELECT * FROM cm_users ORDER BY user_last_name ASC, user_first_name ASC;";

// Run Query
$result = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);

do {
$id = $result_array['id'];
$first_name = $result_array['user_first_name'];
$last_name = $result_array['user_last_name'];
$job_title = $result_array['user_job_title'];
$email = $result_array['user_email'];
?>
  <tr>
    <td><?php echo "$first_name $last_name"; ?></td>
    <td><?php echo $job_title; ?></td>
    <td><a href="mailto:<?php echo $email; ?>" title="Email User"><?php echo $email; ?></a></td>
    <td class="actionMenu" nowrap>
      <ul>
        <li><a href="staff-edit.php?id=<?php echo $id; ?>" title="Edit User">Edit</a></li>
        <li><a href="staff-access.php?id=<?php echo $id; ?>" title="Access">Access</a></li>
        <?php if ($result_row_count > 1) { // Don't make it easy to delete last user ?>
        <li><a href="staff-edit.php?id=<?php echo $id; ?>#delete" title="Delete User">Delete</a></li>
        <?php } ?>
      </ul>
    </td>
  </tr>
  <? } while ($result_array = mysql_fetch_assoc($result)); ?>
  <tr>
    <td class="center" colspan="3"><strong><a href="staff-edit.php?action=add">Add a User</a></strong></td>
    <td></td>
  </tr>
</table>
<?php get_cm_footer(); ?>
