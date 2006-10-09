<?php include('cm-includes/config.php'); ?>
<?php
$module = "advertising-rates";
$pmodule = "advertising-browse";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

$mode = "edit";

// If action is edit, call edit function
if ($_GET['action'] == "edit") {; 
	if ($_POST['id'] != "") {;
		// Get posted data
		$id = $_POST['id'];
		$local_rate = $_POST['local_rate'];
		$national_rate = $_POST['national_rate'];	
		$stat = cm_edit_ad_rates($local_rate,$national_rate,$id);
		if ($stat == 1) {
			header("Location: $module.php?msg=updated");
			exit;
		} else {;
			cm_error("Error in 'cm_edit_ad_rates' function.");
			exit;
		};		
	} else {;
		cm_error("Did not have an order to load.");
		exit;
	};
};


// Query
$query_CM_Array = "SELECT * FROM cm_settings LIMIT 0,1;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

if ($totalRows_CM_Array != 1) {;
	cm_error("Setting profile does not exist.");
	exit;
};

$id = $row_CM_Array['id'];
$local_rate = $row_CM_Array['local_rate'];
$national_rate = $row_CM_Array['national_rate'];


?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"; ?>" name="edit">Advertising Manager</a></h2>
<?php $msg = $_GET['msg'];
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Rate tables updated.</p>"; };
?>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Change Advertising Rates</legend>
  <p>
    <label for="local_rate">Local Rate</label>
    <br />
    <input type="text" name="local_rate" id="local_rate" value="<?php echo $local_rate; ?>" />
  </p>
  <p>
    <label for="national_rate">National Rate</label>
    <br />
    <input type="text" name="national_rate" id="national_rate" value="<?php echo $national_rate; ?>" />
  </p>
 
  <p>

    <input type="submit" value="Change Rates" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <input type="button" value="Cancel" name="cancel_modify" id="cancel_modify" class="button" onClick="javascript:history.back();" />
  </p>
  </fieldset>
</form>
<h2><a name="rates"></a>Rate Schedule</h2>
<fieldset class="<?php echo "$module-table" ?>">
<legend>Local: $
<?php cm_get_price(1,1,'L'); ?>
/CI; National: $
<?php cm_get_price(1,1,'N'); ?>
/CI</legend>
<table>
  <tr>
    <th colspan="2">1 Column</th>
    <th colspan="2">2 Column</th>
    <th colspan="2">3 Column</th>
  </tr>
  <tr>
    <td><strong>Local</strong></td>
    <td class="greyOut"><strong>National</strong></td>
    <td><strong>Local</strong></td>
    <td class="greyOut"><strong>National</strong></td>
    <td><strong>Local</strong></td>
    <td class="greyOut"><strong>National</strong></td>
  </tr>
  <?php for($i = 1; $i < 22; $i++) {;  ?>
  <tr>
    <td>1x<?php echo $i; ?>" -
      <?php cm_get_price(1,$i,'L'); ?>
    </td>
    <td class="greyOut">1x<?php echo $i; ?>" -
      <?php cm_get_price(1,$i,'N'); ?>
    </td>
    <td>2x<?php echo $i; ?>" -
      <?php cm_get_price(2,$i,'L'); ?>
    </td>
    <td class="greyOut">2x<?php echo $i; ?>" -
      <?php cm_get_price(2,$i,'N'); ?>
    </td>
    <td>3x<?php echo $i; ?>" -
      <?php cm_get_price(3,$i,'L'); ?>
    </td>
    <td class="greyOut">3x<?php echo $i; ?>" -
      <?php cm_get_price(3,$i,'N'); ?>
    </td>
  </tr>
  <?php }; ?>
</table>
<table>
  <tr>
    <th colspan="2">4 Column</th>
    <th colspan="2">5 Column</th>
    <th colspan="2">6 Column</th>
  </tr>
  <tr>
    <td><strong>Local</strong></td>
    <td class="greyOut"><strong>National</strong></td>
    <td><strong>Local</strong></td>
    <td class="greyOut"><strong>National</strong></td>
    <td><strong>Local</strong></td>
    <td class="greyOut"><strong>National</strong></td>
  </tr>
  <?php for($i = 1; $i < 22; $i++) {;  ?>
  <tr>
    <td>4x<?php echo $i; ?>" -
      <?php cm_get_price(4,$i,'L'); ?>
    </td>
    <td class="greyOut">4x<?php echo $i; ?>" -
      <?php cm_get_price(4,$i,'N'); ?>
    </td>
    <td>5x<?php echo $i; ?>" -
      <?php cm_get_price(5,$i,'L'); ?>
    </td>
    <td class="greyOut">5x<?php echo $i; ?>" -
      <?php cm_get_price(5,$i,'N'); ?>
    </td>
    <td>6x<?php echo $i; ?>" -
      <?php cm_get_price(6,$i,'L'); ?>
    </td>
    <td class="greyOut">6x<?php echo $i; ?>" -
      <?php cm_get_price(6,$i,'N'); ?>
    </td>
  </tr>
  <?php }; ?>
</table>
</fieldset>
<?php get_cm_footer(); ?>
