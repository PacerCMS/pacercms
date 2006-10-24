<?php
include_once('includes/config.php');

// If posted
if (!empty($_POST['text'])) {
	if (empty($_POST['name']) || empty($_POST['email'])) {
		header("Location: $PHP_SELF?msg=missing");
		exit;
	}
	$text = $_POST['text'];
	$name = $_POST['name'];
	$email = $_POST['email'];	


// Build the e-mail message
	$recipient = site_info('email');
	$header = "From: $email";
	$subject = stripslashes("[[" . site_info('name') . "]] Web site feedback from $name");
	$message = stripslashes("\n$text\n");
	
	// Send the e-mail notification
	$sendit = mail($recipient, $subject, $message, $header);
	if ($sendit == 1) {
		header("Location: $PHP_SELF?" . $_SERVER['QUERY_STRING'] . "&msg=submitted");
		exit;
	} else {
		header("Location: $PHP_SELF?" . $_SERVER['QUERY_STRING'] . "&msg=failed");
		exit;
	};
};


/* Header Configuration */
$pageTitle = " &raquo; Feedback";
$sectionTitle = "Feedback";
?>
<?php get_header($topBar,$pageTitle,$sectionTitle); ?>
<div id="content">
  <h2 class="sectionNameplate">Newspaper &amp; Web Site Feedback</h2>
<?php
$msg = $_GET['msg'];
if ($msg == "submitted") {; echo "<p class=\"systemMessage\">Your submission has been sent to our editors.</p>"; };
if ($msg == "missing") {; echo "<p class=\"systemMessage\">You must complete the required fields.</p>"; };
if ($msg == "failed") {; echo "<p class=\"systemMessage\">Submission failed for some reason. Please try again.</p>"; };
if ($msg == "disabled") {; echo "<p class=\"systemMessage\">Content submissions are disabled at this time.</p>"; };
?>
    <form action="<?php $PHP_SELF; ?>" method="post" name="sendit">
	<div class="colWrap">
    <div class="leftCol">
    <h3>Mailing Address</h3>
      <p><strong><?php echo site_info('name'); ?><br />
		<?php echo site_info('address'); ?><br />
		<?php echo site_info('city') . ", " . site_info('state') . " " . site_info('zipcode'); ?></strong></p>
      <h3>Contacting Us</h3>
      <p><strong>Telephone: <?php echo site_info('telephone'); ?><br />Fax: <?php echo site_info('fax'); ?></strong></p>
      <p><strong>Primary E-mail: <a href="mailto:<?php echo site_info('email'); ?>"><?php echo site_info('email'); ?></a></strong></p>
      <hr />
      <h3>Before you complete this form ...</h3>
      <p>We receive hundreds of e-mails every day. Unfortunately, this means we cannot guarantee individual response. Take a look at the links below to make sure your question is not answered elsewhere on the Web site.</p>
      <ul>
      <li>Send a <a href="<?php echo site_info('url') . "/submit.php?mode=letter"; ?>">''Letter to the Editor''</a>.</li>
      <li>Send a <a href="<?php echo site_info('url') . "/submit.php?mode=article"; ?>">story or press release</a>.</li>
      <li>Send a <a href="<?php echo site_info('url') . "/submit.php?mode=bboard"; ?>">''Bulletin Board'' item</a>.</li>
      </ul>
      </div>
      <div class="rightCol">
      <h3>Step 1: Contact Information</h3>
      <p>
        <label for="name">Your Name</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <input type="text" id="name" name="name" style="width: 250px;" />
      </p>
      <p>
        <label for="email">E-mail</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <input type="text" id="email" name="email" style="width: 250px;" />
      </p>
      <h3>Step 2: Sound Off</h3>
      <p>
        <label for="text">Your Feedback</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <textarea name="text" id="text" rows="20" style="width: 300px;"><?php echo strip_tags($_POST['text']); ?></textarea>
      </p>
<p><span style="font-weight:bold;color:red;">*</span> - <em>Denotes required information</em>.</p>
      <p>
        <input type="submit" id="submit" value="Submit Feedback" /> <input type="reset" id="reset" value="Reset" />
      </p>

  </div>
</div>
    </form>
</div>
<?php get_footer(); ?>
