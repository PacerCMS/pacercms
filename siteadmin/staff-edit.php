<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "staff-edit";
$pmodule = "staff-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
// Change mode based on query string
if (!empty($_GET['action']))
{
	$mode = $_GET['action'];
}

// These will be changed later if needed, set defaults.
$id = $_GET["id"];

// If action is delete, call delete function
if ($mode == "delete" && !empty($_POST['delete-id'])) {
	$id = $_POST['delete-id'];
	$stat = cm_delete_user($id);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {
		cm_error("Error in 'cm_delete_user' function.");
		exit;
	}
}

// If action is edit, call edit function
if ($mode == "edit")
{ 
	if (is_numeric($_POST['id']))
	{
		// Get posted data

		$user['login'] = prep_string($_POST['login']);
		$user['password'] = prep_string($_POST['password']);
		$user['first_name'] = prep_string($_POST['first_name']);
		$user['middle_name'] = prep_string($_POST['middle_name']);
		$user['last_name'] = prep_string($_POST['last_name']);
		$user['job_title'] = prep_string($_POST['job_title']);
		$user['email'] = prep_string($_POST['email']);
		$user['telephone'] = prep_string($_POST['telephone']);
		$user['mobile'] = prep_string($_POST['mobile']);
		$user['classification'] = prep_string($_POST['classification']);
		$user['address'] = prep_string($_POST['address']);
		$user['city'] = prep_string($_POST['city']);
		$user['state'] = prep_string($_POST['state']);
		$user['zipcode'] = prep_string($_POST['zipcode']);
		$user['im_aol'] = prep_string($_POST['im_aol']);
		$user['im_msn'] = prep_string($_POST['im_msn']);
		$user['im_yahoo'] = prep_string($_POST['im_yahoo']);
		$user['im_jabber'] = prep_string($_POST['im_jabber']);
		$user['profile'] = prep_string($_POST['profile']);	
		
		$password_new = prep_string($_POST['password_new']);
		$password_confirm = prep_string($_POST['password_confirm']);
		
		if ($password_new == "$password_confirm" && !empty($password_new))
		{
			$user['password'] = md5($password_new);
		}
		if ($password_new != "$password_confirm")
		{
			cm_error("Passwords did not match.");
			exit;
		}
		$id = $_POST['id'];

		$stat = cm_edit_user($user,$id);
		if ($stat == 1)
		{
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_user' function.");
			exit;
		}		
	} elseif (!empty($_POST)) {
		cm_error("Did not have a user to load.");
		exit;
	}
}

// If action is add, call add function
if ($mode == "add" && !empty($_POST['login'])) { 
	// Get posted data
	$user['login'] = prep_string($_POST['login']);
	$user['first_name'] = prep_string($_POST['first_name']);
	$user['middle_name'] = prep_string($_POST['middle_name']);
	$user['last_name'] = prep_string($_POST['last_name']);
	$user['job_title'] = prep_string($_POST['job_title']);
	$user['email'] = prep_string($_POST['email']);
	$user['telephone'] = prep_string($_POST['telephone']);
	$user['mobile'] = prep_string($_POST['mobile']);
	$user['classification'] = prep_string($_POST['classification']);
	$user['address'] = prep_string($_POST['address']);
	$user['city'] = prep_string($_POST['city']);
	$user['state'] = prep_string($_POST['state']);
	$user['zipcode'] = prep_string($_POST['zipcode']);
	$user['im_aol'] = prep_string($_POST['im_aol']);
	$user['im_msn'] = prep_string($_POST['im_msn']);
	$user['im_yahoo'] = prep_string($_POST['im_yahoo']);
	$user['im_jabber'] = prep_string($_POST['im_jabber']);
	$user['profile'] = prep_string($_POST['profile']);	
	$password_new = prep_string($_POST['password_new']);
	$password_confirm = prep_string($_POST['password_confirm']);

	if ($password_new == "$password_confirm" && $password_new != "") {
		$user['password'] = md5($password_new);
	} else {
		cm_error("Passwords did not match, or left blank.");
		exit;
	}	
	$stat = cm_add_user($user);
	if ($stat == 1) {	
		$user_id = $cm_db->Insert_ID();
		header("Location: staff-access.php?id=$user_id&action=add");
		exit;
	} else {
		cm_error("Error in 'cm_add_user' function.");
		exit;
	}
}

// Only call database if in edit mode.
if ($mode == "edit" && is_numeric($id)) {
	
	// Query
	$query = "SELECT * FROM cm_users WHERE id = $id;";
	
	// Run Query
	$result = cm_run_query($query);
	
	if ($result->RecordCount() != 1) {
		cm_error("User does not exist.");
	}
	
	$id = $result->Fields('id');
	$login = $result->Fields('user_login');
	$password = $result->Fields('user_password');
	$first_name = $result->Fields('user_first_name');
	$middle_name = $result->Fields('user_middle_name');
	$last_name = $result->Fields('user_last_name');
	$job_title = $result->Fields('user_job_title');
	$email = $result->Fields('user_email');
	$telephone = $result->Fields('user_telephone');
	$mobile = $result->Fields('user_mobile');
	$address = $result->Fields('user_address');
	$city = $result->Fields('user_city');
	$state = $result->Fields('user_state');
	$zipcode = $result->Fields('user_zipcode');
	$im_aol = $result->Fields('user_im_aol');
	$im_msn = $result->Fields('user_im_msn');
	$im_yahoo = $result->Fields('user_im_yahoo');
	$im_jabber = $result->Fields('user_im_jabber');
	$profile = $result->Fields('user_profile');


}

get_cm_header();

?>


<h2><a href="<?php echo "$pmodule.php"; ?>">Staff Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Basic Profile</legend>
  <div class="sidebar">
    <?php
if ($mode == "add") {
?>
    <p>
      <label for="login">Login</label>
      <br />
      <input type="text" name="login" id="login" />
    </p>
    <?php } ?>
    <p>Passwords <strong>must match</strong>.</p>
    <p>
      <label for="password_new">Password</label>
      <br />
      <input type="password" name="password_new" id="password_new" />
    </p>
    <p>
      <label for="password_confirm">Confirm Password</label>
      <br />
      <input type="password" name="password_confirm" id="password_confirm" />
    </p>
    <?php
if ($mode == "edit") {
?>
    <p><em>Leave blank if you do not wish<br/>
      to change the password.</em></p>
    <?php } ?>
  </div>
  <p>
    <label for="first_name">First Name</label>
    <br />
    <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" class="text" />
  </p>
  <p>
    <label for="middle_name">Middle Name</label>
    <br />
    <input type="text" name="middle_name" id="middle_name" value="<?php echo $middle_name; ?>" class="text" />
  </p>
  <p>
    <label for="last_name">Last Name</label>
    <br />
    <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" class="text" />
  </p>
  <p>
    <label for="job_title">Job Title</label>
    <br />
    <input type="text" name="job_title" id="job_title" value="<?php echo $job_title; ?>" class="text" />
  </p>
  </fieldset>
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Contact Information</legend>
  <div class="sidebar">
    <p>Internet Messengers (Optional)</p>
    <p>
      <label for="im_aol">AOL Instant Messenger</label>
      <br />
      <input type="text" name="im_aol" id="im_aol" value="<?php echo $im_aol; ?>" class="text" />
    </p>
    <p>
      <label for="im_msn">Microsoft Messenger</label>
      <br />
      <input type="text" name="im_msn" id="im_msn" value="<?php echo $im_msn; ?>" class="text" />
    </p>
    <p>
      <label for="im_yahoo">Yahoo! Messenger</label>
      <br />
      <input type="text" name="im_yahoo" id="im_yahoo" value="<?php echo $im_yahoo; ?>" class="text" />
    </p>
    <p>
      <label for="im_jabber">Jabber Services</label>
      <br />
      <input type="text" name="im_jabber" id="im_jabber" value="<?php echo $im_jabber; ?>" class="text" />
    </p>
  </div>
  <p>
    <label for="email">Email</label>
    <br />
    <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="text" />
  </p>
  <p>
    <label for="telephone">Telephone (Local)</label>
    <br />
    <input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" class="text" />
  </p>
  <p>
    <label for="mobile">Mobile</label>
    <br />
    <input type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>" class="text" />
  </p>
  <p>
    <label for="address">Address</label>
    <br />
    <input type="text" name="address" id="address" value="<?php echo $address; ?>" class="text" />
  </p>
  <p>
    <label for="City">City</label>
    <br />
    <input type="text" name="city" id="city" value="<?php echo $city; ?>" class="text" />
  </p>
  <p>
    <label for="state">State</label>
    <br />
    <input type="text" name="state" id="state" value="<?php echo $state; ?>" class="text" />
  </p>
  <p>
    <label for="zipcode">Zip Code</label>
    <br />
    <input type="text" name="zipcode" id="zipcode" value="<?php echo $zipcode; ?>" class="text" />
  </p>
  </fieldset>
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Additional Information</legend>
  <p>
    <label for="profile">Profile</label>
    <br />
    <textarea name="profile" id="profile" rows="10"><?php echo $profile; ?></textarea>
  </p>
  <p>

<?php if ($mode == "add") { ?>

  <p>
    <input type="submit" value="Add User" name="submit" id="submit" class="button" />
    <input type="button" value="Cancel" name="cancel_add" id="cancel_add" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
<form>

<?php } else { ?>
<p>
    <input type="submit" value="Update Profile" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="login" id="login" value="<?php echo $login; ?>" />
    <input type="hidden" name="password" id="password" value="<?php echo $password; ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<h2>Delete User <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p>Are you sure you want to delete this user?</p>
    <input type="submit" name="submit_delete" id="submit_delete" value="Yes" class="button" />
    <input type="button" name="cancel_delete" id="cancel_delete" value="Cancel" onClick="javascript:toggleLayer('deleteUser');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php } ?>

<?php get_cm_footer(); ?>
