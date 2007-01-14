<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "profile";
$mode = "edit"; // Locked.
$id = $_SESSION['cm_user_id']; // Locked.

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// If posted, call edit function
if ($_GET['action'] == "edit") { 
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
		}
		if ($password_new != "$password_confirm") {
			cm_error("Passwords did not match.");
			exit;
		}
		$stat = cm_edit_profile($password,$email,$telephone,$mobile,$address,$city,$state,$zipcode,$im_aol,$im_msn,$im_yahoo,$im_jabber,$profile,$id);
		if ($stat == 1) {
			header("Location: $module.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_profile' function.");
			exit;
		}
}

// Query
$query = "SELECT * FROM cm_users WHERE cm_users.id = $id;";

// Run Query
$result  = mysql_query($query, $CM_MYSQL) or die(mysql_error());
$result_array  = mysql_fetch_assoc($result);
$result_row_count = mysql_num_rows($result);

if ($result_row_count != 1) {
	cm_error("User does not exist.");
}

$id = $result_array['id'];
$login = $result_array['user_login'];
$password = $result_array['user_password'];
$first_name = $result_array['user_first_name'];
$middle_name = $result_array['user_middle_name'];
$last_name = $result_array['user_last_name'];
$job_title = $result_array['user_job_title'];
$email = $result_array['user_email'];
$telephone = $result_array['user_telephone'];
$mobile = $result_array['user_mobile'];
$address = $result_array['user_address'];
$city = $result_array['user_city'];
$state = $result_array['user_state'];
$zipcode = $result_array['user_zipcode'];
$im_aol = $result_array['user_im_aol'];
$im_msn = $result_array['user_im_msn'];
$im_yahoo = $result_array['user_im_yahoo'];
$im_jabber = $result_array['user_im_jabber'];
$profile = $result_array['user_profile'];


?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>User Profile</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "updated") { echo "<p class=\"systemMessage\">Your user profile has been updated.</p>"; }
?>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Basic Profile</legend>
  <div class="sidebar">
    <p>Passwords <strong>must match.</strong></p>
    <p>It is recommended that your<br />
	  password be longer than 6<br />
	  characters, and a mix of<br />
      letters and numbers.</p>
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
    <p><em>Leave blank if you do not wish<br />
 to change your password.</em></p>
  </div>
  <p><strong>Note:</strong> You cannot change your name or job title. Please
    contact an administrator to correct any errors.</p>
  <p>
    <label for="first_name">First Name</label>
    <br />
    <big><?php echo $first_name; ?></big> </p>
  <p>
    <label for="middle_name">Middle Name</label>
    <br />
    <big><?php echo $middle_name; ?></big> </p>
  <p>
    <label for="last_name">Last Name</label>
    <br />
    <big><?php echo $last_name; ?></big> </p>
  <p>
    <label for="job_title">Job Title</label>
    <br />
    <big><?php echo $job_title; ?></big> </p>
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
    <input type="submit" value="Update Profile" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="login" id="login" value="<?php echo $login; ?>" />
    <input type="hidden" name="password" id="password" value="<?php echo $password; ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
