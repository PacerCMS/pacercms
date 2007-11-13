<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "settings";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if (is_numeric($_POST['id']))
{
	$settings['name'] = prep_string($_POST['name']);
	$settings['description'] = prep_string($_POST['description']);
	$settings['url'] = prep_string($_POST['url']);
	$settings['email'] = prep_string($_POST['email']);
	$settings['address'] = prep_string($_POST['address']);
	$settings['city'] = prep_string($_POST['city']);
	$settings['state'] = prep_string($_POST['state']);
	$settings['zipcode'] = prep_string($_POST['zipcode']);
	$settings['telephone'] = prep_string($_POST['telephone']);
	$settings['fax'] = prep_string($_POST['fax']);
	$settings['announce'] = prep_string($_POST['announce']);	
	$id = $_POST['id'];

	$stat = cm_edit_settings($settings,$id);

	if ($stat == 1)
	{
		header("Location: $module.php?msg=updated");
		exit;
	} else {
		cm_error("Error in 'cm_edit_settings' function.");
		exit;
	}
}


get_cm_header();

?>

<h2>Site Settings</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "updated") { echo "<p class=\"infoMessage\">Site settings updated.</p>"; }
?>
<?php

$id = cm_get_settings('id');

$query = "SELECT * FROM cm_settings WHERE id = $id; ";

$result = cm_run_query($query);

$id = $result->Fields('id');
$name = $result->Fields('site_name');
$description = $result->Fields('site_description');
$url = $result->Fields('site_url');
$email = $result->Fields('site_email');
$address = $result->Fields('site_address');
$city = $result->Fields('site_city');
$state = $result->Fields('site_state');
$zipcode = $result->Fields('site_zipcode');
$telephone = $result->Fields('site_telephone');
$fax = $result->Fields('site_fax');
$announce = $result->Fields('site_announcement');	

?>
<form action="<?php echo "$module.php"; ?>" method="post">
    <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Other Site Settings</legend>
  <p>
    <label for="name">Publication Name</label>
    <br />
    <input type="text" name="name" id="name" value="<?php echo $name; ?>" class="text" />
  </p>
  <p>
    <label for="name">Tagline</label>
    <br />
    <input type="text" name="description" id="description" value="<?php echo $description; ?>" class="text" />
  </p>  
  <p>
    <label for="url">Web Site URL</label>
    <br />
    <input type="text" name="url" id="url" value="<?php echo $url; ?>" class="text" />
  </p>
  <p>
    <label for="email">Primary Email Address</label>
    <br />
    <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="text" />
  </p>    
  <p>
    <label for="address">Address</label>
    <br />
    <input type="text" name="address" id="address" value="<?php echo $address; ?>" class="text" />
  </p> 
  <p>
    <label for="email">City</label>
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
  <p>
    <label for="telephone">Telephone</label>
    <br />
    <input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" class="text" />
  </p>
  <p>
    <label for="fax">Fax</label>
    <br />
    <input type="text" name="fax" id="fax" value="<?php echo $fax; ?>" class="text" />
  </p>
    <p>
    <label for="announce">Announcement</label>
    <br />
    <textarea name="announce" id="announce" rows="5"><?php echo $announce; ?></textarea>
  </p>
  <p>
    <input type="submit" value="Update Settings" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
  
</form>
<?php get_cm_footer(); ?>
