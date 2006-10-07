<?php include('cm-includes/functions.php'); ?>
<?php
$module = "advertising-clients";
$pmodule = "advertising-browse";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// These will be changed later if needed, set defaults.
$issue = $_COOKIE["$pmodule-issue"];
$id = $_GET["id"];

// Change mode based on query string
if ($_GET["action"] != "") {;
	$mode = $_GET["action"];
} else {;
	$mode = "edit";
};

// If action is edit, call edit function
if ($_GET['action'] == "edit") {; 
	if ($_POST['id'] != "") {;
		// Get posted data
		$id = $_POST['id'];
		$name = $_POST['name'];
		$type = $_POST['type'];
		$contact = $_POST['contact'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zipcode = $_POST['zipcode'];
		$telephone = $_POST['telephone'];
		$fax = $_POST['fax'];
		$email = $_POST['email'];
		$active = $_POST['active'];
		$notes = $_POST['notes'];
		$stat = cm_edit_client($name,$type,$contact,$address,$city,$state,$zipcode,$telephone,$fax,$email,$active,$notes,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=client-updated");
			exit;
		} else {;
			cm_error("Error in 'cm_edit_client' function.");
			exit;
		};		
	} else {;
		cm_error("Did not have a client to load.");
		exit;
	};
};

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['name'] != "") {; 
	// Get posted data
	$name = $_POST['name'];
	$type = $_POST['type'];
	$contact = $_POST['contact'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$telephone = $_POST['telephone'];
	$fax = $_POST['fax'];
	$email = $_POST['email'];
	$active = $_POST['active'];
	$notes = $_POST['notes'];
	$stat = cm_add_client($name,$type,$contact,$address,$city,$state,$zipcode,$telephone,$fax,$email,$active,$notes);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=client-added");
		exit;
	} else {;
		cm_error("Error in 'cm_add_client' function.");
		exit;
	};
};

// Only call database if in edit mode.
if ($mode == "edit") {;
	$query_CM_Array = "SELECT * FROM cm_clients WHERE cm_clients.id = $id;";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	if ($totalRows_CM_Array != 1) {;
		cm_error("Client does not exist.");
		exit;
	};
	$id = $row_CM_Array['id'];
	$name = $row_CM_Array['client_name'];
	$type = $row_CM_Array['client_type'];
	$contact = $row_CM_Array['client_contact'];
	$address = $row_CM_Array['client_address'];
	$city = $row_CM_Array['client_city'];
	$state = $row_CM_Array['client_state'];
	$zipcode = $row_CM_Array['client_zipcode'];
	$telephone = $row_CM_Array['client_telephone'];
	$fax = $row_CM_Array['client_fax'];
	$email = $row_CM_Array['client_email'];
	$active = $row_CM_Array['client_active'];
	$notes = $row_CM_Array['client_notes'];
}; // End database call if in edit mode.


?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"; ?>">Advertising Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Client Editor</legend>
  <div class="sidebar">
    <p>
      <label for="type">Type / Ad Schedule</label>
      <br />
      <select name="type" id="type">
        <option value="Local" <?php if (!(strcmp("Local", $type))) {echo "SELECTED";} ?>>Local</option>
        <option value="On Campus" <?php if (!(strcmp("On Campus", $type))) {echo "SELECTED";} ?>>On
        Campus</option>
        <option value="National" <?php if (!(strcmp("National", $type))) {echo "SELECTED";} ?>>National</option>
        <option value="Other" <?php if (!(strcmp("Other", $type))) {echo "SELECTED";} ?>>Other</option>
      </select>
    </p>
    <p>
      <label for="active">Active?</label>
      <br />
      <select name="active" id="active">
        <option value="Y" <?php if (!(strcmp('Y', $active))) {echo "SELECTED";} ?>>Yes</option>
        <option value="N" <?php if (!(strcmp('N', $active))) {echo "SELECTED";} ?>>No</option>
      </select>
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
  </div>
  <p>
    <label for="name">Client / Business Name</label>
    <br />
    <input type="text" name="name" id="name" value="<?php echo $name;?>" />
  </p>
  <p>
    <label for="contact">Contact Person</label>
    <br />
    <input type="text" name="contact" id="contact" value="<?php echo $contact; ?>" />
  </p>
  <p>
    <label for="email">Email</label>
    <br />
    <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
  </p>
  <p>
    <label for="telephone">Telephone</label>
    <br />
    <input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" />
  </p>
  <p>
    <label for="fax">Fax</label>
    <br />
    <input type="text" name="fax" id="fax" value="<?php echo $fax; ?>" />
  </p>
  <p>
    <label for="notes">Notes</label>
    <br />
    <textarea name="notes" id="notes" rows="10"><?php echo $notes; ?></textarea>
  </p>
  <p>
    <?php
if ($mode == "new") {;
?>
    <input type="submit" value="Open Account" name="submit" id="submit" class="button" />
    <?php
};
if ($mode == "edit") {;
?>
    <input type="submit" value="Update Client" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php }; ?>
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<?php if ($mode != "new") {; ?>
<h2><a name="rates"></a>Client History</h2>
<fieldset class="<?php echo "$module-table" ?>">
<legend>Recent Insertion Orders</legend>
<table>
  <tr>
    <th>Date</th>
    <th>Ad Size</th>
    <th>Title / Desc.</th>
    <th>Cost</th>
    <th>Tools</th>
  </tr>
  <?php
    $id = $_GET['id'];
	$query_CM_Array = "SELECT *, cm_advertising.id AS ad_id FROM cm_advertising, cm_issues WHERE cm_advertising.client_id = $id AND cm_advertising.issue_id = cm_issues.id ORDER BY cm_issues.issue_date DESC;";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);

	if ($totalRows_CM_Array > 0) {;
		do {;
			$ad_id = $row_CM_Array['ad_id'];
			$date = $row_CM_Array['issue_date'];
			$issue_id = $row_CM_Array['issue_id'];
			$size = $row_CM_Array['ad_size'];
			$desc = $row_CM_Array['ad_description'];
			$cost = $row_CM_Array['ad_cost'];
			
?>
  <tr>
    <td><a href="<?php echo "$pmodule.php?issue=$issue_id"; ?>"><?php echo $date; ?></a></td>
    <td><?php echo $size; ?></td>
    <td><a href="<?php echo "advertising-edit.php?id=$ad_id"; ?>"><?php echo $desc; ?></a></td>
    <td><?php echo $cost; ?></td>
    <td class="actionMenu">
      <ul class="center">
        <li><a href="<?php echo "advertising-edit.php?id=$ad_id"; ?>">Edit</a></li>
        <li><a href="<?php echo "advertising-edit.php?id=$ad_id#delete"; ?>">Delete</a></li>
      </ul>
    </td>
  </tr>
  <?php } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
  <tr>
    <td colspan="2" rowspan="3" class="greyOut"></td>
    <td style="text-align:right;"><strong>Total</strong></td>
    <td>$<?php echo number_format(cm_client_sales($id),2); ?></td>
    <td rowspan="3" class="greyOut"></td>
  </tr>
  <tr>
    <td style="text-align:right;"><strong>Payments Received</strong></td>
    <td>$<?php echo number_format(cm_client_paid($id),2); ?></td>
  </tr>
  <tr>
    <td style="text-align:right;"><strong>Account Balance</strong></td>
    <td>$<?php echo number_format((cm_client_sales($id) - cm_client_paid($id)),2); ?></td>
  </tr>
  <?php } else {; echo "<td colspan=\"5\">No records found.</td>"; }; ?>
</table>
</fieldset>
<?php }; ?>
<?php get_cm_footer(); ?>
