<?php
// Loads everything needed to run PacerCMS
include_once('includes/cm-header.php');

$active_poll = site_info('active_poll');

// Cast Vote
if (is_numeric($_POST['vote-cast'])) {;
	$vote = strip_tags($_POST['vote-cast']);
	$stat = cast_ballot($vote);
	if ($stat == 1) {;
		header("Location: $PHP_SELF?msg=counted");
		exit;
	} else {
		header("Location: $PHP_SELF?msg=failed");
		exit;
	};
};
	
/* Header Configuration */
$topBar = "";
$pageTitle = " &raquo; Pacer Poll Results";
$sectionTitle = "Pacer Poll";

?>
<?php get_header($topBar,$pageTitle,$section); ?>

<div id="content">
  <h2 class="sectionNameplate">Poll Results</h2>
  <?php
$msg = $_GET['msg'];
if ($msg == "counted") {; echo "<p class=\"systemMessage\">Your vote was counted.</p>"; };
if ($msg == "failed") {; echo "<p class=\"systemMessage\">Something was wrong with your ballot.</p>"; };
?>
  <div id="pollResults">

<?php

$question = get_ballot('question',$active_poll);
$r1 = get_ballot('response_1',$active_poll);
$r2 = get_ballot('response_2',$active_poll);
$r3 = get_ballot('response_3',$active_poll);
$r4 = get_ballot('response_4',$active_poll);
$r5 = get_ballot('response_5',$active_poll);
$r6 = get_ballot('response_6',$active_poll);
$r7 = get_ballot('response_7',$active_poll);
$r8 = get_ballot('response_8',$active_poll);
$r9 = get_ballot('response_9',$active_poll);
$r10 = get_ballot('response_10',$active_poll);
$v1 = poll_results(1,$active_poll);
$v2 = poll_results(2,$active_poll);
$v3 = poll_results(3,$active_poll);
$v4 = poll_results(4,$active_poll);
$v5 = poll_results(5,$active_poll);
$v6 = poll_results(6,$active_poll);
$v7 = poll_results(7,$active_poll);
$v8 = poll_results(8,$active_poll);
$v9 = poll_results(9,$active_poll);
$v10 = poll_results(10,$active_poll);
$vTotal = $v1 + $v2 + $v3 + $v4 + $v5 + $v6 + $v7 + $v8 + $v9 + $v10;

?>

<h3><?php echo $question; ?></h3>

<?php if ($vTotal != 0) {; ?>

<ol>
<?php if (!empty($r1)) { echo "<li>$r1 " . number_format($v1/$vTotal*100, 2) . "% ($v1 votes)</li>\n"; }; ?>
<?php if (!empty($r2)) { echo "<li>$r2 " . number_format($v2/$vTotal*100, 2) . "% ($v2 votes)</li>\n"; }; ?>
<?php if (!empty($r3)) { echo "<li>$r3 " . number_format($v3/$vTotal*100, 2) . "% ($v3 votes)</li>\n"; }; ?>
<?php if (!empty($r4)) { echo "<li>$r4 " . number_format($v4/$vTotal*100, 2) . "% ($v4 votes)</li>\n"; }; ?>
<?php if (!empty($r5)) { echo "<li>$r5 " . number_format($v5/$vTotal*100, 2) . "% ($v5 votes)</li>\n"; }; ?>
<?php if (!empty($r6)) { echo "<li>$r6 " . number_format($v6/$vTotal*100, 2) . "% ($v6 votes)</li>\n"; }; ?>
<?php if (!empty($r7)) { echo "<li>$r7 " . number_format($v7/$vTotal*100, 2) . "% ($v7 votes)</li>\n"; }; ?>
<?php if (!empty($r8)) { echo "<li>$r8 " . number_format($v8/$vTotal*100, 2) . "% ($v8 votes)</li>\n"; }; ?>
<?php if (!empty($r9)) { echo "<li>$r9 " . number_format($v9/$vTotal*100, 2) . "% ($v9 votes)</li>\n"; }; ?>
<?php if (!empty($r10)) { echo "<li>$r10 " . number_format($v10/$vTotal*100, 2) . "% ($v10 votes)</li>\n"; }; ?>
</ol>

<p>Total Votes: <?php echo $vTotal; ?></p>

<?php } else { echo "<h2>No votes have been recorded!</h2>"; }; ?>

<p>Our Web poll is not scientific and reflects the opinions of only those Internet users who have chosen to participate. The results cannot be assumed to represent the opinions of Internet users in general, the public as a whole, nor the students or faculty of our university.</p>

  </div>
  
  </div>
  
<?php get_footer(); ?>