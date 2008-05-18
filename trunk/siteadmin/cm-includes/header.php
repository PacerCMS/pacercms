<?php header("Content-type: text/html; charset=UTF-8"); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PacerCMS for <?php echo cm_get_settings('site_name'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="cm-includes/style.css" rel="stylesheet" type="text/css" />
<script src="cm-includes/functions.js" type="text/javascript"> </script>
<script src="cm-includes/prototype.js" type="text/javascript"> </script>
<script src="cm-includes/js_quicktags.js" type="text/javascript"> </script>
<?php if ($_REQUEST['msg'] == 'preview-issue' && is_numeric($_REQUEST['id']) ) { ?>
<script type="text/javascript">
    window.open('<?php echo cm_get_settings('site_url'); ?>?preview=<?php echo $_REQUEST['id']; ?>');
</script>
<?php } ?>
</head>
<body>
<div id="header">
<h1><a href="index.php"><?php echo cm_get_settings('site_name'); ?></a></h1>
<?php if (!empty($_SESSION['user_data'])) {; ?><p><?php echo gettext("Welcome"); ?>, <a href="profile.php"><?php echo $_SESSION['user_data']['user_first_name'] . ' ' . $_SESSION['user_data']['user_last_name']; ?></a></p><?php }; ?>
<hr />
</div>
<?php get_cm_menu(); ?>
<div id="content">
<?php
/* Warn about configuration errors */
if (strtotime(cm_next_issue('date')) <= strtotime(cm_current_issue('date'))) {
    print '<p class="alertMessage">Warning: Your "Current Issue" date is set as the same or a later date than your "Next Issue"</p>';
}
?>