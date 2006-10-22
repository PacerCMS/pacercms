<?php include('cm-includes/config.php'); ?>
<?php
$module = "settings";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

if ($_POST['id'] != "") {;
	// Get posted data
	$id = $_POST['id'];
	$name = $_POST['name'];
	$description = $_POST['description'];
	$url = $_POST['url'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$telephone = $_POST['telephone'];
	$fax = $_POST['fax'];
	$announce = $_POST['announce'];	
	// Run function
	$stat = cm_edit_settings($name,$description,$url,$email,$address,$city,$state,$zipcode,$telephone,$fax,$announce,$id);
	if ($stat == 1) {
		header("Location: $module.php?msg=updated");
		exit;
	} else {;
		cm_error("Error in 'cm_edit_settings' function.");
		exit;
	};
};


?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Site Settings</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Site settings updated.</p>"; };
?>
<?php

// Database Query
$query_CM_Array = "SELECT * FROM cm_settings;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

do {;
	$id = $row_CM_Array['id'];
	$name = $row_CM_Array['site_name'];
	$description = $row_CM_Array['site_description'];
	$url = $row_CM_Array['site_url'];
	$email = $row_CM_Array['site_email'];
	$address = $row_CM_Array['site_address'];
	$city = $row_CM_Array['site_city'];
	$state = $row_CM_Array['site_state'];
	$zipcode = $row_CM_Array['site_zipcode'];
	$telephone = $row_CM_Array['site_telephone'];
	$fax = $row_CM_Array['site_fax'];
	$announce = $row_CM_Array['site_announcement'];	
} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));

?>
<form action="<?php echo "$module.php"; ?>" method="post">
    <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Other Site Settings</legend>
  <p>
    <label for="name">Publication Name</label>
    <br />
    <input type="text" name="name" id="name" value="<?php echo $name; ?>"/>
  </p>
  <p>
    <label for="name">Tagline</label>
    <br />
    <input type="text" name="description" id="description" value="<?php echo $description; ?>"/>
  </p>  
  <p>
    <label for="url">Web Site URL</label>
    <br />
    <input type="text" name="url" id="url" value="<?php echo $url; ?>"/>
  </p>
  <p>
    <label for="email">Primary Email Address</label>
    <br />
    <input type="text" name="email" id="email" value="<?php echo $email; ?>"/>
  </p>    
  <p>
    <label for="address">Address</label>
    <br />
    <input type="text" name="address" id="address" value="<?php echo $address; ?>"/>
  </p> 
  <p>
    <label for="email">City</label>
    <br />
    <input type="text" name="city" id="city" value="<?php echo $city; ?>"/>
  </p> 
  <p>
    <label for="state">State</label>
    <br />
    <input type="text" name="state" id="state" value="<?php echo $state; ?>"/>
  </p> 
  <p>
    <label for="zipcode">Zip Code</label>
    <br />
    <input type="text" name="zipcode" id="zipcode" value="<?php echo $zipcode; ?>"/>
  </p>
  <p>
    <label for="telephone">Telephone</label>
    <br />
    <input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>"/>
  </p>
  <p>
    <label for="fax">Fax</label>
    <br />
    <input type="text" name="fax" id="fax" value="<?php echo $fax; ?>"/>
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
