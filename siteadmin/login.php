<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

// Load settings into session variable
cm_settings_data();

$mode = $_REQUEST['action'];
if ($mode == "") {
	$mode = "login";
}

if ($mode == "logout") {
    cm_logout();
    exit;
}

$dest = $_REQUEST['dest'];
if ($dest == "") {
	$dest = "index.php";
}

if ($_POST['username'] != "" && $_POST['password'] != "") {
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$status = cm_auth_user($username,$password);
	if ($status == true) {
		header("Location: $dest");
		exit; 
	} else {
		header("Location: " . $PHP_SELF . "?msg=login-failed");
		exit;
	}
}

if ($_POST['username'] != "" && $_POST['email'] != "") {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$status = cm_reset_pass($username,$email);
	if ($status == true) {
		header("Location: " . $PHP_SELF . "?msg=password-reset");
		exit; 
	} else {
		header("Location: " . $PHP_SELF . "?msg=not-found");
		exit;
	}
}
?>
<html>
<head>
<title>PacerCMS &raquo; <?php echo $_SESSION['setting_data']['site_name']; ?></title>
<style type="text/css">
<!--
body {
	font-size:10pt;
	font-family: sans-serif;
	text-align:center;
	background-color: rgb(248, 250, 252);
}
fieldset {
	padding:10px;
	border:solid 1px #ccc;
	background-color: #FFFFFF;
}
legend {
	padding:10px;
	border:solid 1px #ccc;
	font-weight:bold;
	background-color: #FFFFFF;
}
p {text-align:right;}
h2 {text-align:center;}
label {margin-right:5px; font-weight:bold;}
input {font-size: 15pt;width: 300px;}
.loginSplash {margin: auto; height: 300px; width: 400px;}
.alertMessage {	background: #fff6bf url(cm-images/exclamation.png) center no-repeat;	background-position: 15px 50%; /* x-pos y-pos */	text-align: left;	padding: 5px 20px 5px 45px;	border-top: 2px solid #ffd324;	border-bottom: 2px solid #ffd324;}
.infoMessage {	background: rgb(248, 250, 252) url(cm-images/information.png) center no-repeat;	background-position: 15px 50%; /* x-pos y-pos */	text-align: left;	padding: 5px 20px 5px 45px;	border-top: 2px solid rgb(181, 212, 254);	border-bottom: 2px solid rgb(181, 212, 254);}
-->
</style>
</head>
<body>
<div class="loginSplash">
<?php if ($mode == "login") { ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>
    <legend><img src="cm-images/header.png" alt="PacerCMS" /></legend>
    <h2><a href="<?php echo cm_get_settings('site_url'); ?>"><?php echo cm_get_settings('site_name'); ?></a></h2>
    <?php
    $msg = $_GET['msg'];
    if ($msg == "login-failed") { echo "<p class=\"alertMessage\">" . gettext("Your username or password were incorrect.") . "</p>"; }
    if ($msg == "password-reset") { echo "<p class=\"infoMessage\">" . gettext("You new password has been e-mailed.") . "</p>"; }
    if ($msg == "not-found") { echo "<p class=\"alertMessage\">" . gettext("Could not find matching username and e-mail address.") . "</p>"; }
    if ($msg == "logout") { echo "<p class=\"infoMessage\">" . gettext("Your session has ended.") . "</p>"; }
    ?>
    <p><label for="username"><?php echo gettext("Username"); ?></label><input type="text" name="username" id="username" /></p>
    <p><label for="password"><?php echo gettext("Password"); ?></label><input type="password" name="password" id="password" /></p>
    <p>
    <input type="hidden" name="dest" id="dest" value="<?php echo $dest; ?>" />
    <input type="submit" name="login" id="login" value="<?php echo gettext("Login"); ?>" /></p>
    <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=reset"><?php echo gettext("Reset My Password"); ?></a> | <a href="mailto:<?php echo cm_get_settings('site_email'); ?>"><?php echo gettext("Request Login"); ?></a></p>
    </fieldset>
    </form>
<?php
	// Show reset password form instead
	} else {
?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>
    <legend><img src="cm-images/header.png" alt="PacerCMS" /></legend>
    <h2><a href="<?php echo cm_get_settings('site_url'); ?>"><?php echo cm_get_settings('site_name'); ?></a></h2>
    <p><label for="username"><?php echo gettext("Username"); ?></label><input type="text" name="username" id="username" /></p>
    <p><label for="password"><?php echo gettext("E-mail"); ?></label><input type="text" name="email" id="password" /></p>
    <p>
    <input type="submit" name="submit" id="submit" value="<?php echo gettext("Reset Password"); ?>" /></p>
    <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=login"><?php echo gettext("Back to Login"); ?></a> | <a href="mailto:<?php echo cm_get_settings('site_email'); ?>"><?php echo gettext("Request Login"); ?></a></p>
    </fieldset>
    </form>
<?php } ?>
</div>
</body>
</html>
