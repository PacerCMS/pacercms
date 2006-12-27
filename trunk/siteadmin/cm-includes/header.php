<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Content Manager for <?php echo cm_get_settings('site_name'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="cm-includes/style.css" rel="stylesheet" type="text/css" />
<script src="cm-includes/functions.js" type="text/javascript"></script>
<script src="cm-includes/prototype.js" type="text/javascript"></script>
<script src="cm-includes/js_quicktags.js" type="text/javascript"></script>
</head>
<body>
<div id="header">
<h1><a href="index.php" title="Back to Main Page"><?php echo cm_get_settings('site_name'); ?></a></h1>
<?php if ($_SESSION['cm_user_id'] != "") {; ?><p>Welcome, <a href="profile.php" title="Edit Your Profile"><?php echo $_SESSION['cm_user_fullname']; ?></a></p><?php }; ?>
<hr />
</div>
<?php get_cm_menu(); ?>
<div id="content">