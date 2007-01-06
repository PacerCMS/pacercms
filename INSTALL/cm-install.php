<?php
define('CM_INSTALLING', true);
if (!file_exists('../includes/config.php')) 
    die("There doesn't seem to be a <code>config.php</code> file. I need this before we can get started. Need more help? <a href='http://wordpress.org/docs/faq/#cm-config'>We got it</a>. You can <a href='cm-config.php'>create a <code>config.php</code> file through a web interface</a>, but this doesn't work for all server setups. The safest way is to manually create the file.");

require_once('../includes/config.php');
require_once('../includes/classes.php');
require_once('../includes/functions.php');

$schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
$site_url = str_replace('INSTALL', '', $schema . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) );

if (isset($_GET['step']))
	$step = $_GET['step'];
else
	$step = 0;
header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>PacerCMS &rsaquo; Installation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
<body>
<h1 id="logo">PacerCMS Installer</h1>
<?php
// Let's check to make sure WP isn't already installed.
$installed = $db->Execute("SELECT * FROM cm_users;");
if ($installed) die('<h2>Already Installed</h2><p>You appear to have already installed PacerCMS. To reinstall please clear your old database tables first.</p></body></html>');

switch($step) {

	case 0:
?>
<p>Welcome to the PacerCMS installer. This utility will create the database structure needed to begin managing your online publication.  There are only three steps to get the system up and running, so let's get started.</p>
	<h2 class="step"><a href="cm-install.php?step=1">First Step &raquo;</a></h2>
<?php
	break;

	case 1:

?>
<h2>First Step</h2>
<p>Before we begin we need a little bit of information. Don't worry, you can always change these later.</p>

<form id="setup" method="post" action="cm-install.php?step=2">
<table width="100%">
<tr>
<th width="33%">Publication Name</th>
<td><input name="site_name" type="text" id="site_name" size="25" /></td>
</tr>
<tr>
<th>E-mail Address</th>
<td><input name="site_email" type="text" id="site_email" size="25" /></td>
</tr>
</table>
<p><em>Make sure your e-mail address is correct, because that is where we are sending your login information.</em></p>
<h2 class="step">
<input type="submit" name="Submit" value="Continue to Next Step" />
</h2>
</form>

<?php
	break;
	case 2:

// Fill in the data we gathered
$site_name = stripslashes($_POST['site_name']);
$site_email = stripslashes($_POST['site_email']);
// check e-mail address
if (empty($site_email)) {
	die ('<strong>ERROR</strong>: Please type your e-mail address');
}
	
?>
<h1>Second Step</h1>
<p>Now we're going to build the database tables ...</p>
<ul>

<?php

echo "<ul>";

// Access table (structure)
echo "  <li><strong>Creating cm_access</strong> ...";
$sql = file_get_contents('sql/cm_access-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Articles table (structure)
echo "  <li><strong>Creating cm_articles</strong> ...";$sql = file_get_contents('sql/cm_articles-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Issues table (structure)
echo "  <li><strong>Creating cm_issues</strong> ...";$sql = file_get_contents('sql/cm_issues-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Media table (structure)
echo "  <li><strong>Creating cm_media</strong> ...";$sql = file_get_contents('sql/cm_media-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Pages table (structure)
echo "  <li><strong>Creating cm_pages</strong> ...";$sql = file_get_contents('sql/cm_pages-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Poll Ballot table (structure)
echo "  <li><strong>Creating cm_poll_ballot</strong> ...";$sql = file_get_contents('sql/cm_poll_ballot-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Poll Questions table (structure)
echo "  <li><strong>Creating cm_poll_questions</strong> ...";$sql = file_get_contents('sql/cm_poll_questions-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Sections table (structure)
echo "  <li><strong>Creating cm_sections</strong> ...";$sql = file_get_contents('sql/cm_sections-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Settings table (structure)
echo "  <li><strong>Creating cm_settings</strong> ...";$sql = file_get_contents('sql/cm_settings-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Submitted table (structure)
echo "  <li><strong>Creating cm_submitted</strong> ...";$sql = file_get_contents('sql/cm_submitted-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Users table (structure)
echo "  <li><strong>Creating cm_users</strong> ...";$sql = file_get_contents('sql/cm_users-structure.sql');
$request = $db->Execute($sql);
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

echo "</ul>";

?>

<p>... And load the default data.</p>

<?php

// Set up admin user
$user_name = "System Admininistrator";
$user_login = "admin";
$random_password = substr(md5(uniqid(microtime())), 0, 6);

echo "<ul>\n";

// Access table (data)
echo "  <li><strong>Adding default data for cm_access</strong> ...";
$sql = file('sql/cm_access-data.sql');
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Articles table (data)
echo "  <li><strong>Adding default data for cm_articles</strong> ...";$sql = file('sql/cm_articles-data.sql');
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Issues table (data)
echo "  <li><strong>Adding default data for cm_issues</strong> ...";$sql = file('sql/cm_issues-data.sql');
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Media table (data)
echo "  <li><strong>Adding default data for cm_media</strong> ...";$sql = file('sql/cm_media-data.sql');
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Pages table (data)
echo "  <li><strong>Adding default data for cm_pages</strong> ...";$sql = file('sql/cm_pages-data.sql');
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Poll Questions table (data)
echo "  <li><strong>Adding default data for cm_poll_questions</strong> ...";$sql = file('sql/cm_poll_questions-data.sql');
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Sections table (data)
echo "  <li><strong>Adding default data for cm_sections</strong> ...";$sql_tpl = file('sql/cm_sections-data.sql');
$search = array( '{site_url}' );
$replace = array( $site_url );
$sql = str_replace($search, $replace, $sql_tpl);
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Settings table (data)
echo "  <li><strong>Adding default data for cm_settings</strong> ...";$sql_tpl = file('sql/cm_settings-data.sql');
$search = array( '{site_name}', '{site_url}', '{site_email}' );
$replace = array( $site_name, $site_url, $site_email );
$sql = str_replace($search, $replace, $sql_tpl);
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

// Users table (data)
echo "  <li><strong>Adding default data for cm_users</strong> ...";
$sql_tpl = file('sql/cm_users-data.sql');
$search = array( '{random_password}', '{site_email}' );
$replace = array( md5($random_password), $site_email );
$sql = str_replace($search, $replace, $sql_tpl);
foreach($sql as $line)
{
    $request = $db->Execute($line);
}
if ($db->ErrorMsg())
{
    echo $db->ErrorMsg();
    exit;
} else {
    echo "  Done!</li>\n";
};

echo "</ul>\n";

?>

<p>Your database is now ready to go!</p>

<?php

$message_headers = 'From: ' . $site_name . ' <pacercms@' . $_SERVER['SERVER_NAME'] . '>';
$message = sprintf("Your new PacerCMS installation has been successfully set up at:

%1\$s

You can log in to PacerCMS with the following information:

Username: admin
Password: %2\$s

Make sure you pick a new password that is easy to remember but hard to guess.

--The PacerCMS Team
http://pacercms.sourceforge.net/
", $site_url, $random_password);

mail($site_email, 'Welcome to PacerCMS', $message, $message_headers);

?>

<p><em>Finished!</em></p>

<p><?php printf('Now you can <a href="%1$s">log in</a> with the <strong>username</strong> "<code>admin</code>" and <strong>password</strong> "<code>%2$s</code>".', '../siteadmin/', $random_password); ?></p>
<p><strong><em>Note that password</em></strong> carefully! It is a <em>random</em> password that was generated just for you. If you lose it, you will have to delete the tables from the database yourself, and re-install PacerCMS. So to review:</p>
<dl>
<dt>Username</dt>
<dd><code>admin</code></dd>
<dt>Password</dt>
<dd><code><?php echo $random_password; ?></code></dd>
	<dt>Login Address</dt>
<dd><a href="../siteadmin/">Site Administration</a></dd>
</dl>
<p>Installation complete!</p>
<?php
	break;
}
?>
</body>
</html>
