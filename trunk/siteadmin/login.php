<?php include('cm-includes/config.php'); ?>
<?php

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
		cm_error("Your login was denied. Check your username and password before trying again.");
		exit;
	};
}
?>
<html>
<head>
<title>Content Manager &raquo; <?php echo cm_get_settings('site_name'); ?></title>
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
-->
</style>
</head>
<body>
<div class="loginSplash">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<fieldset>
<legend><img src="cm-images/header.png" alt="ContentManager" /></legend>
<h2><a href="<?php echo cm_get_settings('site_url'); ?>"><?php echo cm_get_settings('site_name'); ?></a></h2>
<p><label for="username">Username</label><input type="text" name="username" id="username" /></p>
<p><label for="password">Password</label><input type="password" name="password" id="password" /></p>
<p>
<input type="hidden" name="dest" id="dest" value="<?php echo $dest; ?>" />
<input type="submit" name="login" id="login" value="Login" /></p>
<p><a href="mailto:<?php echo cm_get_settings('site_email'); ?>">Forgot your username and/or password?</a></p>
</fieldset>
</form>
</div>
</body>
</html>
