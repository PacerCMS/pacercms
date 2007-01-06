<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$mode = $_GET['action'];
if ($mode == "") {;
	$mode = "login";
}
$dest = $_GET['dest'];
if ($dest == "") {;
	$dest = "index.php";
}

if ($_POST['username'] != "" && $_POST['password'] != "") {
	$dest = $_POST['dest'];
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$status = cm_auth_user($username,$password,$dest);
	if ($status == 1) {;
		header("Location: $dest");
		exit; 
	} else {;
		header("Location: " . $PHP_SELF . "?msg=login-failed");
		exit;
	};
}

if ($_POST['username'] != "" && $_POST['email'] != "") {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$status = cm_reset_pass($username,$email);
	if ($status == 1) {;
		header("Location: " . $PHP_SELF . "?msg=password-reset");
		exit; 
	} else {;
		header("Location: " . $PHP_SELF . "?msg=not-found");
		exit;
	};
}
?>
<html>
<head>
<title>PacerCMS &raquo; <?php echo cm_get_settings('site_name'); ?></title>
<style type="text/css">
<!--
body {
	font-size:10pt;
	font-family: sans-serif;
	text-align:center;
	background-color: #CCFFFF;
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
.systemMessage { text-align:center; font-weight:bold; background-color: #ffc; color: #000; padding: 5px; margin-top: 5px; margin-bottom: 5px;  border: 1px solid #ccc; }
-->
</style>
</head>
<body>
<div class="loginSplash">
<?php if ($mode == "login") {; ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<fieldset>
<legend><img src="cm-images/header.png" alt="PacerCMS" /></legend>
<h2><a href="<?php echo cm_get_settings('site_url'); ?>"><?php echo cm_get_settings('site_name'); ?></a></h2>
<?php
$msg = $_GET['msg'];
if ($msg == "login-failed") {; echo "<p class=\"systemMessage\">Your username or password were incorrect.</p>"; };
if ($msg == "password-reset") {; echo "<p class=\"systemMessage\">You new password has been e-mailed.</p>"; };
if ($msg == "not-found") {; echo "<p class=\"systemMessage\">Could not find matching username and e-mail address.</p>"; };
if ($msg == "logout") {; echo "<p class=\"systemMessage\">Your session has ended.</p>"; };
?>
<p><label for="username">Username</label><input type="text" name="username" id="username" /></p>
<p><label for="password">Password</label><input type="password" name="password" id="password" /></p>
<p>
<input type="hidden" name="dest" id="dest" value="<?php echo $dest; ?>" />
<input type="submit" name="login" id="login" value="Login" /></p>
<p><a href="<?php echo $PHP_SELF; ?>?action=reset">Reset My Password</a> | <a href="mailto:<?php echo cm_get_settings('site_email'); ?>">Request Login</a></p>
</fieldset>
</form>
<?php
	// Show reset password form instead
	} else {;
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<fieldset>
<legend><img src="cm-images/header.png" alt="PacerCMS" /></legend>
<h2><a href="<?php echo cm_get_settings('site_url'); ?>"><?php echo cm_get_settings('site_name'); ?></a></h2>
<p><label for="username">Username</label><input type="text" name="username" id="username" /></p>
<p><label for="password">E-mail</label><input type="text" name="email" id="password" /></p>
<p>
<input type="submit" name="submit" id="submit" value="Reset Password" /></p>
<p><a href="<?php echo $PHP_SELF; ?>?action=login">Back to Login</a> | <a href="mailto:<?php echo cm_get_settings('site_email'); ?>">Request Login</a></p>
</fieldset>
</form>
<?php }; ?>
</div>
</body>
</html>
