<?php include('cm-includes/functions.php'); ?>
<?php

// Declare the current module
$module = "submitted-edit";
$pmodule = "submitted-browse";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);

$id = $_GET["id"];

// Query
$query_CM_Array = "SELECT * FROM cm_submitted ";
$query_CM_Array .= " WHERE id = $id";
// Security Measure

// Run Query
$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
$row_CM_Array  = mysql_fetch_assoc($CM_Array);
$totalRows_CM_Array = mysql_num_rows($CM_Array);

// If Array comes back empty, produce error
if ($totalRows_CM_Array != 1) {;
	cm_error("Submitted article does not exist.");
	exit;
};
	
// Define variables
$id = $row_CM_Array['id'];
$issue = $row_CM_Array['issue_id'];
$title = $row_CM_Array['submitted_title'];
$text = $row_CM_Array['submitted_text'];
$keyword = $row_CM_Array['submitted_keyword'];
$author = $row_CM_Array['submitted_author'];
$email = $row_CM_Array['submitted_author_email'];
$major = $row_CM_Array['submitted_author_major'];
$city = $row_CM_Array['submitted_author_city'];
$telephone = $row_CM_Array['submitted_author_telephone'];
$sent = $row_CM_Array['submitted_sent'];
$words = $row_CM_Array['submitted_words'];
?>
<?php get_cm_header(); ?>

<h2><a href="<?php echo $pmodule; ?>.php?">Submitted Article Manager</a></h2>
<fieldset class="<?php echo "$module-preview" ?>">
<legend>Story Preview</legend>
<h3><?php echo $title; ?></h3>
<p>By <strong><?php echo $author; ?></strong></p>
<?php echo autop($text); ?>
<h4>Contact Information</h4>

<p>
<?php echo $author; ?><br />
<?php echo $city; ?><br />
<?php echo $major; ?></p>
<p><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br />
<?php echo $telephone; ?>
</p>

<p><strong>Word Count:</strong> <?php echo $words; ?><br>
<strong>Keyword:</strong> <?php echo $keyword; ?></p>
</fieldset>
<?php get_cm_footer(); ?>
