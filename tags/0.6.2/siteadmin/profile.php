<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Declare the current module
$module = "profile";
$mode = "edit"; // Locked.
$id = $_SESSION['user_data']['id']; // Locked.

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// If posted, call edit function
if ($_GET['action'] == "edit")
{ 
		$user['login'] = $_POST['login'];
		$user['password'] = $_POST['password'];
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
		
		$password_new = $_POST['password_new'];
		$password_confirm = $_POST['password_confirm'];

		if ($password_new == "$password_confirm" && !empty($password_new)) {
			$user['password'] = md5($password_new);
		}
		if ($password_new != "$password_confirm") {
			cm_error("Passwords did not match.");
			exit;
		}
		
		$stat = cm_edit_profile($user);
		
		if ($stat) {
			header("Location: $module.php?msg=updated");
			exit;
		} else {
			cm_error("Error in 'cm_edit_profile' function.");
			exit;
		}
}

$query = "SELECT * FROM cm_users WHERE id = $id; ";

$result = cm_run_query($query);

if ($result->RecordCount() != 1)
{
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


get_cm_header();

?>

<h2>User Profile</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "updated") {
    echo "<p class=\"infoMessage\">Your user profile has been updated.</p>";
} else {
    echo "<p class=\"alertMessage\">You cannot change your name or job title. Please contact an administrator to correct any errors.</p>";
}
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
  <p>
    <label for="first_name">First Name</label>
    <br />
    <big><?php echo $first_name; ?>&nbsp;</big>
  </p>
  <p>
    <label for="middle_name">Middle Name</label>
    <br />
    <big><?php echo $middle_name; ?>&nbsp;</big>
  </p>
  <p>
    <label for="last_name">Last Name</label>
    <br />
    <big><?php echo $last_name; ?></big>&nbsp;
  </p>
  <p>
    <label for="job_title">Job Title</label>
    <br />
    <big><?php echo $job_title; ?></big>&nbsp;
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
    <input type="hidden" name="login" id="login" value="<?php echo $login; ?>" />
    <input type="hidden" name="password" id="password" value="<?php echo $password; ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php get_cm_footer(); ?>
