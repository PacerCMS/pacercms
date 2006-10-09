<?php include('cm-includes/config.php'); ?>
<?php
// Declare the current module
$module = "staff-edit";
$pmodule = "staff-browse";
$mode = "edit"; // Default

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

$id = $_GET["id"]; // Default

// Change mode based on query string
if ($_GET["action"] != "") {;
	$mode = $_GET["action"];
};

// If action is delete, call delete function
if ($_GET['action'] == "delete" && $_POST['delete-id'] != "") {;
	$id = $_POST['delete-id'];
	$stat = cm_delete_user($id);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {;
		cm_error("Error in 'cm_delete_user' function.");
		exit;
	};
};

// If action is edit, call edit function
if ($_GET['action'] == "edit") {; 
	if ($_POST['id'] != "") {;
		// Get posted data
		$id = $_POST['id'];
		$login = $_POST['login'];
		$password = $_POST['password'];
		$first_name = $_POST['first_name'];
		$middle_name = $_POST['middle_name'];
		$last_name = $_POST['last_name'];
		$job_title = $_POST['job_title'];
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
		$mobile = $_POST['mobile'];		
		$classification = $_POST['classification'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zipcode = $_POST['zipcode'];
		$im_aol = $_POST['im_aol'];
		$im_msn = $_POST['im_msn'];
		$im_yahoo = $_POST['im_yahoo'];
		$im_jabber = $_POST['im_jabber'];
		$profile = $_POST['profile'];	
		$password_new = $_POST['password_new'];
		$password_confirm = $_POST['password_confirm'];
		if ($password_new == "$password_confirm" && $password_new != "") {
			$password = md5($password_new);
		};
		if ($password_new != "$password_confirm") {
			cm_error("Passwords did not match.");
			exit;
		};
		$stat = cm_edit_user($login,$password,$first_name,$middle_name,$last_name,$job_title,$email,$telephone,$mobile,$address,$city,$state,$zipcode,$im_aol,$im_msn,$im_yahoo,$im_jabber,$profile,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {;
			cm_error("Error in 'cm_edit_user' function.");
			exit;
		};		
	} else {;
		cm_error("Did not have a user to load.");
		exit;
	};
};

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['login'] != "") {; 
	// Get posted data
	$login = $_POST['login'];
	$first_name = $_POST['first_name'];
	$middle_name = $_POST['middle_name'];
	$last_name = $_POST['last_name'];
	$job_title = $_POST['job_title'];
	$email = $_POST['email'];
	$telephone = $_POST['telephone'];
	$mobile = $_POST['mobile'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$im_aol = $_POST['im_aol'];
	$im_msn = $_POST['im_msn'];
	$im_yahoo = $_POST['im_yahoo'];
	$im_jabber = $_POST['im_jabber'];
	$profile = $_POST['profile'];	
	$password_new = $_POST['password_new'];
	$password_confirm = $_POST['password_confirm'];
	if ($password_new == "$password_confirm" && $password_new != "") {
		$password = md5($password_new);
	} else {;
		cm_error("Passwords did not match, or left blank.");
		exit;
	};	
	$stat = cm_add_user($login,$password,$first_name,$middle_name,$last_name,$job_title,$email,$telephone,$mobile,$address,$city,$state,$zipcode,$im_aol,$im_msn,$im_yahoo,$im_jabber,$profile);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {;
		cm_error("Error in 'cm_add_user' function.");
		exit;
	};
};

// Only call database if in edit mode.
if ($mode == "edit") {;
	
	// Query
	$query_CM_Array = "SELECT * FROM cm_users WHERE cm_users.id = $id;";
	
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);

	if ($totalRows_CM_Array != 1) {;
		cm_error("User does not exist.");
	};
	
	$id = $row_CM_Array['id'];
	$login = $row_CM_Array['user_login'];
	$password = $row_CM_Array['user_password'];
	$first_name = $row_CM_Array['user_first_name'];
	$middle_name = $row_CM_Array['user_middle_name'];
	$last_name = $row_CM_Array['user_last_name'];
	$job_title = $row_CM_Array['user_job_title'];
	$email = $row_CM_Array['user_email'];
	$telephone = $row_CM_Array['user_telephone'];
	$mobile = $row_CM_Array['user_mobile'];
	$address = $row_CM_Array['user_address'];
	$city = $row_CM_Array['user_city'];
	$state = $row_CM_Array['user_state'];
	$zipcode = $row_CM_Array['user_zipcode'];
	$im_aol = $row_CM_Array['user_im_aol'];
	$im_msn = $row_CM_Array['user_im_msn'];
	$im_yahoo = $row_CM_Array['user_im_yahoo'];
	$im_jabber = $row_CM_Array['user_im_jabber'];
	$profile = $row_CM_Array['user_profile'];


}; // End database call if in edit mode.

?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"; ?>">Staff Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Basic Profile</legend>
  <div class="sidebar">
    <?php
if ($mode == "new") {;
?>
    <p>
      <label for="login">Login</label>
      <br />
      <input type="text" name="login" id="login" />
    </p>
    <?php }; ?>
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
if ($mode == "edit") {;
?>
    <p><em>Leave blank if you do not wish<br/>
      to change the password.</em></p>
    <?php }; ?>
  </div>
  <p>
    <label for="first_name">First Name</label>
    <br />
    <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" />
  </p>
  <p>
    <label for="middle_name">Middle Name</label>
    <br />
    <input type="text" name="middle_name" id="middle_name" value="<?php echo $middle_name; ?>" />
  </p>
  <p>
    <label for="last_name">Last Name</label>
    <br />
    <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" />
  </p>
  <p>
    <label for="job_title">Job Title</label>
    <br />
    <input type="text" name="job_title" id="job_title" value="<?php echo $job_title; ?>" />
  </p>
  </fieldset>
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Contact Information</legend>
  <div class="sidebar">
    <p>Internet Messengers (Optional)</p>
    <p>
      <label for="im_aol">AOL Instant Messenger</label>
      <br />
      <input type="text" name="im_aol" id="im_aol" value="<?php echo $im_aol; ?>" />
    </p>
    <p>
      <label for="im_msn">Microsoft Messenger</label>
      <br />
      <input type="text" name="im_msn" id="im_msn" value="<?php echo $im_msn; ?>" />
    </p>
    <p>
      <label for="im_yahoo">Yahoo! Messenger</label>
      <br />
      <input type="text" name="im_yahoo" id="im_yahoo" value="<?php echo $im_yahoo; ?>" />
    </p>
    <p>
      <label for="im_jabber">Jabber Services</label>
      <br />
      <input type="text" name="im_jabber" id="im_jabber" value="<?php echo $im_jabber; ?>" />
    </p>
  </div>
  <p>
    <label for="email">Email</label>
    <br />
    <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
  </p>
  <p>
    <label for="telephone">Telephone (Local)</label>
    <br />
    <input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" />
  </p>
  <p>
    <label for="mobile">Mobile</label>
    <br />
    <input type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
  </p>
  <p>
    <label for="address">Address</label>
    <br />
    <input type="text" name="address" id="address" value="<?php echo $address; ?>" />
  </p>
  <p>
    <label for="City">City</label>
    <br />
    <input type="text" name="city" id="city" value="<?php echo $city; ?>" />
  </p>
  <p>
    <label for="state">State</label>
    <br />
    <input type="text" name="state" id="state" value="<?php echo $state; ?>" />
  </p>
  <p>
    <label for="zipcode">Zip Code</label>
    <br />
    <input type="text" name="zipcode" id="zipcode" value="<?php echo $zipcode; ?>" />
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
    <?php
if ($mode == "new") {;
?>
    <input type="submit" value="Add User" name="submit" id="submit" class="button" />
    <?php
};
if ($mode == "edit") {;
?>
    <input type="submit" value="Update Profile" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="login" id="login" value="<?php echo $login; ?>" />
    <input type="hidden" name="password" id="password" value="<?php echo $password; ?>" />
    <?php }; ?>
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
<?php get_cm_footer(); ?>
