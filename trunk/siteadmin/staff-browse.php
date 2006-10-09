<?php include('cm-includes/config.php'); ?>
<?php
$module = "staff-browse";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Staff Manager</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "added") {; echo "<p class=\"systemMessage\">User added.</p>"; };
if ($msg == "updated") {; echo "<p class=\"systemMessage\">User updated.</p>"; };
if ($msg == "deleted") {; echo "<p class=\"systemMessage\">User deleted.</p>"; };
if ($msg == "access-updated") {; echo "<p class=\"systemMessage\">User access updated.</p>"; };
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
$query_CM_Array = "SELECT * FROM cm_users ORDER BY user_last_name ASC, user_first_name ASC;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

do {;
$id = $row_CM_Array['id'];
$first_name = $row_CM_Array['user_first_name'];
$last_name = $row_CM_Array['user_last_name'];
$job_title = $row_CM_Array['user_job_title'];
$email = $row_CM_Array['user_email'];
?>
  <tr>
    <td><?php echo "$first_name $last_name"; ?></td>
    <td><?php echo $job_title; ?></td>
    <td><a href="mailto:<?php echo $email; ?>" title="Email User"><?php echo $email; ?></a></td>
    <td class="actionMenu" nowrap>
      <ul>
        <li><a href="staff-edit.php?id=<?php echo $id; ?>" title="Edit User">Edit</a></li>
        <li><a href="staff-access.php?id=<?php echo $id; ?>" title="Access">Access</a></li>
        <li><a href="staff-edit.php?id=<?php echo $id; ?>#delete" title="Delete User">Delete</a></li>
      </ul>
    </td>
  </tr>
  <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
  <tr>
    <td class="center" colspan="3"><strong><a href="staff-edit.php?action=new">Add a User</a></strong></td>
    <td></td>
  </tr>
</table>
<?php get_cm_footer(); ?>
