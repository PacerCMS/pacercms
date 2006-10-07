<?php include('cm-includes/functions.php'); ?>
<?php
$module = "advertising-browse";
$cmodule = "advertising-edit";
// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

// Read Issue Cookie
if ($_COOKIE["$module-issue"] == "") {;
	setcookie("$module-issue", $next_issue_id); // Current Issue
	$issue = $next_issue_id;
} else {;
	$issue = $_COOKIE["$module-issue"];
};

// Switch issues
if (is_numeric($_GET['issue'])) {;
	$issue = $_GET['issue'];
	if (cm_issue_info("id",$issue) == "") {;
		cm_error("The selected issue could not be loaded.");
	};	
	setcookie("$module-issue", $issue);
	header("Location: $module.php");
	exit;
};

// Database Query
$query_CM_Array = "SELECT cm_advertising.id, cm_advertising.client_id, cm_clients.client_name, cm_advertising.ad_description, cm_advertising.ad_size, cm_advertising.ad_cost, cm_advertising.ad_page, cm_advertising.ad_payment";
$query_CM_Array .= " FROM cm_advertising JOIN cm_clients ON (cm_advertising.client_id = cm_clients.id)";
$query_CM_Array .= " AND issue_id = \"$issue\"";
$query_CM_Array .= " ORDER BY ad_page ASC, ad_submitted ASC;";

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

?>
<?php get_cm_header(); ?>
<?php get_cm_menu(); ?>

<h2>Advertising Manager</h2>
<?php $msg = $_GET['msg'];
if ($msg == "added") {; echo "<p class=\"systemMessage\">Insertion order added.</p>"; };
if ($msg == "updated") {; echo "<p class=\"systemMessage\">Insertion order updated.</p>"; };
if ($msg == "deleted") {; echo "<p class=\"systemMessage\">Insertion order deleted.</p>"; };
if ($msg == "client-added") {; echo "<p class=\"systemMessage\">Client account opened.</p>"; };
if ($msg == "client-updated") {; echo "<p class=\"systemMessage\">Client account updated.</p>"; };
?>
<form action="<?php echo "$module.php"; ?>" method="get">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Select Issue</legend>
  <div class="actionMenu">
    <ul>
      <li>
        <label>Select Issue:</label>
        <select name="issue" id="issue">
          <?php cm_issue_list($module, $issue); ?>
        </select>
        <input type="submit" name="submit-issue" id="submit-issue" value="Open" class="button" />
      </li>
      <li><a href="<?php echo "$module.php?issue=$current_issue_id";?>" <?php if ($issue == $current_issue_id) {;	echo " class=\"selected\""; }; ?>><strong>Current:</strong> <?php echo $current_issue_date; ?></a></li>
      <li><a href="<?php echo "$module.php?issue=$next_issue_id";?>" <?php if ($issue == $next_issue_id) {;	echo " class=\"selected\""; }; ?>><strong>Next:</strong> <?php echo $next_issue_date; ?></a></li>
    </ul>
  </div>
  </fieldset>
</form>
<fieldset>
<legend><?php echo "Run Sheet for: " . cm_issue_info("issue_date", $issue) . " (Volume " . cm_issue_info("issue_volume", $issue) . ", No. " . cm_issue_info("issue_number", $issue) . ")"; ?></legend>
<?php if ($totalRows_CM_Array > 0) {; ?>
<table>
  <tr>
    <th>Page</th>
    <th>Client / Description</th>
	<th>Size</th>
    <th>Cost</th>
    <th>Tools</th>
  </tr>
      <?php

do {;

	$id = $row_CM_Array['id'];
	$client_id = $row_CM_Array['client_id'];
	$client = $row_CM_Array['client_name'];
	$page = $row_CM_Array['ad_page'];
	$desc = $row_CM_Array['ad_description'];
	$size = $row_CM_Array['ad_size'];
	$cost = $row_CM_Array['ad_cost'];
	$paid = $row_CM_Array['ad_payment'];
	if ($paid > '0000-00-00 00:00:00') {; $paid = "[PAID]"; } else { $paid = ""; };
	if ($page == 0) {; $page = "<acronym title=\"Not Yet Placed\">?</acronym>"; };  
	if ($page == 99) {; $page = "<acronym title=\"Special Section\">SS</acronym>"; };  
?>

  <tr>
    <td class="center"><?php echo $page; ?></td>
    <td>"<a href="<?php echo "$cmodule.php?id=$id"; ?>"><?php echo $desc; ?></a>"<br />
	<small><strong>Client:</strong> <a href="<?php echo "advertising-clients.php?id=$client_id"; ?>"><?php echo $client; ?></a>
	<?php echo $paid; ?>
	</small></td>
    <td><?php echo $size; ?></td><td><?php echo "$$cost"; ?></td>
    <td class="actionMenu" nowrap>
      <ul class="center">
        <li><a href="<?php echo "$cmodule.php?id=$id"; ?>">Edit</a></li>
        <li><a href="<?php echo "$cmodule.php?id=$id#delete"; ?>">Delete</a></li>
      </ul>
    </td>
  </tr>
     <? } while ($row_CM_Array = mysql_fetch_assoc($CM_Array)); ?>
  
  <tr>
    <td></td>
    <td class="center"><a href="<?php echo "$cmodule.php?action=new"; ?>"><strong>Place
          Insertion Order</strong></a></td>
    <td style="text-align:right;"><strong>Total:</strong></td>
    <td>$<?php echo number_format(cm_issue_sales($issue),2); ?></td>
	<td></td>
  </tr>
  <tr>
    <td colspan="2" rowspan="2" class="greyOut"></td>
    <td style="text-align:right;"><strong>Received Payments:</strong></td>
    <td>$<?php echo number_format(cm_issue_paid($issue),2); ?></td>
	<td rowspan="2" class="greyOut"></td>
  </tr>
    <tr>
    <td style="text-align:right;"><strong>Balance:</strong></td>
    <td>$<?php echo number_format((cm_issue_sales($issue) - cm_issue_paid($issue)),2); ?></td>
  </tr>
</table>
<?php } else {; ?>
  <p>No advertising has been recorded for this issue. <a href="<?php echo "$cmodule.php?action=new"; ?>">Place Insertion Order</a></p>
<?php };?>
</fieldset>
<form action="advertising-clients.php" method="get">
  <fieldset class="<?php echo "$module-form"; ?>">
  <legend>Browse Advertisers</legend>
  <div class="actionMenu">
    <ul>
      <li>
        <label>Select Advertiser:</label>
        <select name="id" id="id">
          <?php cm_client_list(0); ?>
        </select>
        <input type="submit" name="submit-client" id="submit-client" value="Open" class="button" />
      </li>
      <li><a href="advertising-clients.php?action=new">New Account</a></li>
	  <?php if ($show_advertising_rates == "true") {; ?><li><a href="advertising-rates.php">Rates</a></li><?php }; ?>
    </ul>
  </div>
  </fieldset>
</form>

<?php get_cm_footer(); ?>
