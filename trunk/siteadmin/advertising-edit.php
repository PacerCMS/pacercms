<?php include('cm-includes/config.php'); ?>
<?php
$module = "advertising-edit";
$pmodule = "advertising-browse";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Change mode based on query string
if ($_GET["action"] != "") {;
	$mode = $_GET["action"];
} else {;
	$mode = "edit";
};

// Make sure we have operating parameters set.
if ($_COOKIE["$pmodule-issue"] == "") {; // Send back to parent
	header("Location: $pmodule.php");
	exit;
};

// These will be changed later if needed, set defaults.
$issue = $_COOKIE["$pmodule-issue"];
$id = $_GET["id"];


// If action is delete, call delete function
if ($_GET['action'] == "delete" && $_POST['delete-id'] != "") {;
	$id = $_POST['delete-id'];
	$stat = cm_delete_advertising($id);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=deleted");
		exit;
	} else {;
		cm_error("Error in 'cm_delete_advertising' function.");
		exit;
	};
};

// If action is edit, call edit function
if ($_GET['action'] == "edit") {; 
	if ($_POST['id'] != "") {;
		// Get posted data
		$id = $_POST['id'];
		$issue = $_POST['issue'];
		$client = $_POST['client'];
		$size = $_POST['size'];
		$desc = $_POST['desc'];
		$cost = $_POST['cost'];
		$notes = $_POST['notes'];
		$sold_by = $_POST['sold_by'];
		$page = $_POST['page'];
		$ledgered = $_POST['ledgered'];
		$quickbooks = $_POST['quickbooks'];
		$billed = $_POST['billed'];
		$payment = $_POST['payment'];	
		$stat = cm_edit_advertising($issue,$client,$size,$desc,$cost,$notes,$sold_by,$page,$ledgered,$quickbooks,$billed,$payment,$id);
		if ($stat == 1) {
			header("Location: $pmodule.php?msg=updated");
			exit;
		} else {;
			cm_error("Error in 'cm_edit_advertising' function.");
			exit;
		};		
	} else {;
		cm_error("Did not have an order to load.");
		exit;
	};
};

// If action is new, call add function
if ($_GET['action'] == "new" && $_POST['client'] != "") {; 
	// Get posted data
	$issue = $_POST['issue'];
	$client = $_POST['client'];
	$size = $_POST['size'];
	$desc = $_POST['desc'];
	$cost = $_POST['cost'];
	$notes = $_POST['notes'];
	$sold_by = $_POST['sold_by'];
	$page = $_POST['page'];
	$ledgered = $_POST['ledgered'];
	$quickbooks = $_POST['quickbooks'];
	$billed = $_POST['billed'];
	$payment = $_POST['payment'];		
	$stat = cm_add_advertising($issue,$client,$size,$desc,$cost,$notes,$sold_by,$page,$ledgered,$quickbooks,$billed,$payment);
	if ($stat == 1) {
		header("Location: $pmodule.php?msg=added");
		exit;
	} else {;
		cm_error("Error in 'cm_add_advertising' function.");
		exit;
	};
};

// Only call database if in edit mode.
if ($mode == "edit") {;
	
	// Query
	$query_CM_Array = "SELECT * FROM cm_advertising WHERE cm_advertising.id = $id;";
	
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);

	if ($totalRows_CM_Array != 1) {;
		cm_error("Order does not exist.");
		exit;
	};
	
	$id = $row_CM_Array['id'];
	$issue = $row_CM_Array['issue_id'];
	$client = $row_CM_Array['client_id'];
	$size = $row_CM_Array['ad_size'];
	$desc = $row_CM_Array['ad_description'];
	$cost = $row_CM_Array['ad_cost'];
	$notes = $row_CM_Array['ad_notes'];
	$sold_by = $row_CM_Array['ad_sold_by'];
	$page = $row_CM_Array['ad_page'];
	$submitted = $row_CM_Array['ad_submitted'];
	$ledgered = $row_CM_Array['ad_ledgered'];
	$quickbooks = $row_CM_Array['ad_quickbooks'];
	$billed = $row_CM_Array['ad_billed'];
	$payment = $row_CM_Array['ad_payment'];

}; // End database call if in edit mode.

// Only call database if in add mode.
if ($mode == "new") {;
	$ledgered = "0000-00-00 00:00:00";
	$quickbooks = "0000-00-00 00:00:00";
	$billed = "0000-00-00 00:00:00";
	$payment = "0000-00-00 00:00:00";
};

?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2><a href="<?php echo "$pmodule.php"; ?>" name="edit">Advertising Manager</a></h2>
<form action="<?php echo "$module.php?action=$mode"; ?>" method="post">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Insertion Order</legend>
  <div class="sidebar">
    <p>
      <label for="issue_id">Run Issue</label>
      <br />
      <select name="issue" id="issue">
        <?php cm_issue_list($module, $issue); ?>
      </select>
    </p>
    <p>
      <label for="page">Placed on Page</label>
      <br />
      <select name="page" id="page">
        <option value="0" <?php if (!(strcmp(0, $page))) {echo "SELECTED";} ?>>Not
        Yet Placed</option>
        <option value="99" <?php if (!(strcmp(99, $page))) {echo "SELECTED";} ?>>Special
        Section</option>
        <option value="2" <?php if (!(strcmp(2, $page))) {echo "SELECTED";} ?>>Page
        2</option>
        <option value="3" <?php if (!(strcmp(3, $page))) {echo "SELECTED";} ?>>Page
        3</option>
        <option value="4" <?php if (!(strcmp(4, $page))) {echo "SELECTED";} ?>>Page
        4</option>
        <option value="5" <?php if (!(strcmp(5, $page))) {echo "SELECTED";} ?>>Page
        5</option>
        <option value="6" <?php if (!(strcmp(6, $page))) {echo "SELECTED";} ?>>Page
        6</option>
        <option value="7" <?php if (!(strcmp(7, $page))) {echo "SELECTED";} ?>>Page
        7</option>
        <option value="8" <?php if (!(strcmp(8, $page))) {echo "SELECTED";} ?>>Page
        8</option>
        <option value="9" <?php if (!(strcmp(9, $page))) {echo "SELECTED";} ?>>Page
        9</option>
        <option value="10" <?php if (!(strcmp(10, $page))) {echo "SELECTED";} ?>>Page
        10</option>
        <option value="11" <?php if (!(strcmp(11, $page))) {echo "SELECTED";} ?>>Page
        11</option>
        <option value="12" <?php if (!(strcmp(12, $page))) {echo "SELECTED";} ?>>Page
        12</option>
        <option value="13" <?php if (!(strcmp(13, $page))) {echo "SELECTED";} ?>>Page
        13</option>
        <option value="14" <?php if (!(strcmp(14, $page))) {echo "SELECTED";} ?>>Page
        14</option>
        <option value="15" <?php if (!(strcmp(15, $page))) {echo "SELECTED";} ?>>Page
        15</option>
        <option value="16" <?php if (!(strcmp(16, $page))) {echo "SELECTED";} ?>>Page
        16</option>
        <option value="17" <?php if (!(strcmp(17, $page))) {echo "SELECTED";} ?>>Page
        17</option>
        <option value="18" <?php if (!(strcmp(18, $page))) {echo "SELECTED";} ?>>Page
        18</option>
        <option value="19" <?php if (!(strcmp(19, $page))) {echo "SELECTED";} ?>>Page
        19</option>
        <option value="20" <?php if (!(strcmp(20, $page))) {echo "SELECTED";} ?>>Page
        20</option>
        <option value="21" <?php if (!(strcmp(21, $page))) {echo "SELECTED";} ?>>Page
        21</option>
        <option value="22" <?php if (!(strcmp(22, $page))) {echo "SELECTED";} ?>>Page
        22</option>
        <option value="23" <?php if (!(strcmp(23, $page))) {echo "SELECTED";} ?>>Page
        23</option>
        <option value="24" <?php if (!(strcmp(24, $page))) {echo "SELECTED";} ?>>Page
        24</option>
      </select>
    </p>
    <p> <strong>Task List</strong> <br />
      <input type="checkbox" name="ledgered" id="ledgered" value="<?php if ($ledgered != "0000-00-00 00:00:00") {; echo $ledgered; } else {; echo date('Y-m-d h:i:s',time()); }; ?>" <?php if ($ledgered != "0000-00-00 00:00:00") {; echo "checked"; }; ?> class="checkbox" />
      <label for="ledgered">Entered into the Ledger</label>
      <br/>
      <input type="checkbox" name="quickbooks" id="quickbooks" value="<?php if ($quickbooks != "0000-00-00 00:00:00") {; echo $quickbooks; } else {; echo date('Y-m-d h:i:s',time()); }; ?>" <?php if ($quickbooks != "0000-00-00 00:00:00") {; echo "checked"; }; ?> class="checkbox" />
      <label for="lquickbooks">Entered into QuickBooks&trade;</label>
      <br/>
      <input type="checkbox" name="billed" id="billed" value="<?php if ($ledgered != "0000-00-00 00:00:00") {; echo $billed; } else {; echo date('Y-m-d h:i:s',time()); }; ?>" <?php if ($billed != "0000-00-00 00:00:00") {; echo "checked"; }; ?> class="checkbox" />
      <label for="billed">Invoice Mailed / Delivered</label>
      <br/>
      <input type="checkbox" name="payment" id="payment" value="<?php if ($payment != "0000-00-00 00:00:00") {; echo $ledgered; } else {; echo date('Y-m-d h:i:s',time()); }; ?>" <?php if ($payment != "0000-00-00 00:00:00") {; echo "checked"; }; ?> class="checkbox" />
      <label for="payment">Payment Received (In Full)</label>
      <br/>
    </p>
    <p>
      <label for="sold_by">Sales Representative</label>
      <br />
      <select name="sold_by" id="sold_by">
        <option value="0">Not Assigned</option>
        <?php cm_user_list($sold_by); ?>
      </select>
    </p>
  </div>
  <p>
    <label for="client">Client</label>
    <br />
    <select name="client" id="client">
      <?php cm_client_list($client); ?>
    </select>
  </p>
  <p>
    <label for="desc">Title / Description</label>
    <br />
    <input type="text" name="desc" id="desc" value="<?php echo $desc; ?>" />
  </p>
  <p>
    <label for="size"><a href="#rates">Ad Size</a></label>
    <br />
    <input type="text" name="size" id="size" value="<?php echo $size; ?>" />
  </p>
  <p>
    <label for="cost"><a href="#rates">Total Cost</a></label>
    <br />
    <input type="type" name="cost" id="cost" value="<?php echo $cost; ?>" />
  </p>
  <p>
    <label for="notes">Notes</label>
    <br />
    <textarea name="notes" id="notes"><?php echo $notes; ?></textarea>
  </p>
  <p>
    <?php
if ($mode == "new") {;
?>
    <input type="submit" value="Place Order" name="submit" id="submit" class="button" />
    <?php
};
if ($mode == "edit") {;
?>
    <input type="submit" value="Update Order" name="update" id="update" class="button" />
    <input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
    <?php }; ?>
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
<div class="actionMenu">
  <ul>
    <li><strong>Common Sizes (<code>L</code>):</strong></li>
    <li><a href="#edit" onClick="setTextField('size','','2 col. x 4 in.');setTextField('cost','','<?php cm_get_price(2,4,'L'); ?>')">1/16
        page [2x4]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','3 col. x 5 in.');setTextField('cost','','<?php cm_get_price(3,5,'L'); ?>')">1/8
        page [3x5]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','3 col. x 10 in.');setTextField('cost','','<?php cm_get_price(3,10,'L'); ?>')">1/4
        page [3x10]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','6 col. x 10 in.');setTextField('cost','','<?php cm_get_price(6,10,'L'); ?>')">1/2
        page [6x10]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','6 col. x 21 in.');setTextField('cost','','<?php cm_get_price(6,21,'L'); ?>')">Full
        page [6x21]</a></li>
  </ul>
</div>
<div class="actionMenu">
  <ul>
    <li><strong>Common Sizes (<code>N</code>):</strong></li>
    <li><a href="#edit" onClick="setTextField('size','','2 col. x 4 in. [N]');setTextField('cost','','<?php cm_get_price(2,4,'N'); ?>')">1/16
        page [2x4]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','3 col. x 5 in. [N]');setTextField('cost','','<?php cm_get_price(3,5,'N'); ?>')">1/8
        page [3x5]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','3 col. x 10 in. [N]');setTextField('cost','','<?php cm_get_price(3,10,'N'); ?>')">1/4
        page [3x10]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','6 col. x 10 in. [N]');setTextField('cost','','<?php cm_get_price(6,10,'N'); ?>')">1/2
        page [6x10]</a></li>
    <li><a href="#edit" onClick="setTextField('size','','6 col. x 21 in. [N]');setTextField('cost','','<?php cm_get_price(6,21,'N'); ?>')">Full
        page [6x21]</a></li>
  </ul>
</div>
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
    <td><a href="#edit" onClick="setTextField('size','','1 col. x <?php echo $i; ?> in.');setTextField('cost','','<?php cm_get_price(1,$i,'L'); ?>')">1x<?php echo $i; ?>"</a> -
      <?php cm_get_price(1,$i,'L'); ?>
    </td>
    <td class="greyOut"><a href="#edit" onClick="setTextField('size','','1 col. x <?php echo $i; ?> in. [N]');setTextField('cost','','<?php cm_get_price(1,$i,'N'); ?>')">1x<?php echo $i; ?>"</a> -
      <?php cm_get_price(1,$i,'N'); ?>
    </td>
    <td><a href="#edit" onClick="setTextField('size','','2 col. x <?php echo $i; ?> in.');setTextField('cost','','<?php cm_get_price(2,$i,'L'); ?>')">2x<?php echo $i; ?>"</a> -
      <?php cm_get_price(2,$i,'L'); ?>
    </td>
    <td class="greyOut"><a href="#edit" onClick="setTextField('size','','2 col. x <?php echo $i; ?> in. [N]');setTextField('cost','','<?php cm_get_price(2,$i,'N'); ?>')">2x<?php echo $i; ?>"</a> -
      <?php cm_get_price(2,$i,'N'); ?>
    </td>
    <td><a href="#edit" onClick="setTextField('size','','3 col. x <?php echo $i; ?> in.');setTextField('cost','','<?php cm_get_price(3,$i,'L'); ?>')">3x<?php echo $i; ?>"</a> -
      <?php cm_get_price(3,$i,'L'); ?>
    </td>
    <td class="greyOut"><a href="#edit" onClick="setTextField('size','','3 col. x <?php echo $i; ?> in. [N]');setTextField('cost','','<?php cm_get_price(3,$i,'N'); ?>')">3x<?php echo $i; ?>"</a> -
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
    <td><a href="#edit" onClick="setTextField('size','','4 col. x <?php echo $i; ?> in.');setTextField('cost','','<?php cm_get_price(4,$i,'L'); ?>')">4x<?php echo $i; ?>"</a> -
      <?php cm_get_price(4,$i,'L'); ?>
    </td>
    <td class="greyOut"><a href="#edit" onClick="setTextField('size','','4 col. x <?php echo $i; ?> in. [N]');setTextField('cost','','<?php cm_get_price(4,$i,'N'); ?>')">4x<?php echo $i; ?>"</a> -
      <?php cm_get_price(4,$i,'N'); ?>
    </td>
    <td><a href="#edit" onClick="setTextField('size','','5 col. x <?php echo $i; ?> in.');setTextField('cost','','<?php cm_get_price(5,$i,'L'); ?>')">5x<?php echo $i; ?>"</a> -
      <?php cm_get_price(5,$i,'L'); ?>
    </td>
    <td class="greyOut"><a href="#edit" onClick="setTextField('size','','5 col. x <?php echo $i; ?> in. [N]');setTextField('cost','','<?php cm_get_price(5,$i,'N'); ?>')">5x<?php echo $i; ?>"</a> -
      <?php cm_get_price(5,$i,'N'); ?>
    </td>
    <td><a href="#edit" onClick="setTextField('size','','6 col. x <?php echo $i; ?> in.');setTextField('cost','','<?php cm_get_price(6,$i,'L'); ?>')">6x<?php echo $i; ?>"</a> -
      <?php cm_get_price(6,$i,'L'); ?>
    </td>
    <td class="greyOut"><a href="#edit" onClick="setTextField('size','','6 col. x <?php echo $i; ?> in. [N]');setTextField('cost','','<?php cm_get_price(6,$i,'N'); ?>')">6x<?php echo $i; ?>"</a> -
      <?php cm_get_price(6,$i,'N'); ?>
    </td>
  </tr>
  <?php }; ?>
</table>
</fieldset>
<?php if ($mode != "new") {; ?>
<h2>Delete Order <a href="javascript:toggleLayer('deleteRecord');" title="Show Delete Button" name="delete">&raquo;&raquo;</a></h2>
<div id="deleteRecord">
  <form action="<?php echo "$module.php?action=delete"; ?>" method="post">
    <fieldset class="<?php echo "$module-delete" ?>">
    <legend>Confirm Delete</legend>
    <p>Are you sure you want to delete this order?</p>
    <input type="submit" name="submit-delete" id="submit-delete" value="Yes" class="button" />
    <input type="button" name="cancel-delete" id="cancel-delete" value="Cancel" onClick="javascript:toggleLayer('deleteRecord');" class="button" />
    <input type="hidden" name="delete-id" id="delete-id" value="<?php echo $id; ?>" />
    </fieldset>
  </form>
</div>
<?php }; ?>
<?php get_cm_footer(); ?>
