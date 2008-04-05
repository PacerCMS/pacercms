<?php
define('CM_INSTALLING', true);

if (!file_exists('../includes/config-sample.php'))
    die('Sorry, I need a <tt>config-sample.php</tt> file to work from. Please re-upload this file from your PacerCMS installation.');

$configFile = file('../includes/config-sample.php');

if (!is_writable('../includes/')) die("Sorry, I can't write to the <tt>./includes</tt> directory. You'll have to either change the permissions (<tt>chmod 777 includes/</tt>) on the directory or create your <tt>config.php</tt> manually.");


if (isset($_GET['step']))
	$step = $_GET['step'];
else
	$step = 0;
header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PacerCMS &rsaquo; Setup Configuration File</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body {
	font-family: "Lucida Grande", Helvetica, Verdana, sans-serif;
	font-size: 10pt;
	line-height: 16pt;
	text-align: left;
	margin: 0;
	margin-left: 10%;
	margin-right: 10%;
	padding: 0;
	color: rgb(0, 0, 0);
}

a, a:link, a:visited {
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

img {
	border: 0;
}

h1 {
	font-size: 15pt;
	font-weight: bold;
}

#footer {text-align:right;font-size:80%;line-height:2em;border-top:solid 1px #eee;}

</style>
</head>
<body>
<h1><img src="../siteadmin/cm-images/header.png" alt="PacerCMS"></h1>
<?php
// Check if cm-config.php has been created
if (file_exists('../includes/config.php'))
	die("<p>The file 'config.php' already exists. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href='cm-install.php'>installing now</a>.</p></body></html>");

switch($step) {
	case 0:
?> 

<p>Welcome to PacerCMS. Before getting started, we need some information on the database. You will need to know the following items before proceeding.</p> 
<ol> 
  <li>Database name</li> 
  <li>Database username</li> 
  <li>Database password</li>
  <li>Database host</li>
  <li>Template folder name</li> 
</ol> 
<p><strong>If for any reason this automatic file creation doesn't work, don't worry. All this does is fill in the database information to a configuration file. You may also simply open <code>config-sample.php</code> in a text editor, fill in your information, and save it as <code>config.php</code>. </strong></p>
<p>In all likelihood, these items were supplied to you by your ISP. If you do not have this information, then you will need to contact them before you can continue. If you&#8217;re all ready, <a href="cm-config.php?step=1">let&#8217;s go</a>! </p>
<?php
	break;

	case 1:
	?> 
</p> 
<form method="post" action="cm-config.php?step=2">
  <p>Below you should enter your database connection details. If you're not sure about these, contact your host. </p>
  <table> 
    <tr> 
      <th scope="row">Database Platform</th> 
      <td>
         <select name="dbplatform" type="text">
           <option value="mysql">MySQL</option>
         </select>
      </td>
      <td>The type of database platform (Note: Only tested with MySQL). </td> 
    </tr> 
    <tr> 
      <th scope="row">Database Name</th> 
      <td><input name="dbname" type="text" size="25" value="pacercms" /></td>
      <td>The name of the database you want to run PacerCMS in. </td> 
    </tr> 
    <tr> 
      <th scope="row">User Name</th> 
      <td><input name="uname" type="text" size="25" value="username" /></td>
      <td>Your database username</td> 
    </tr> 
    <tr> 
      <th scope="row">Password</th> 
      <td><input name="pwd" type="text" size="25" value="password" /></td>
      <td>...and password.</td> 
    </tr> 
    <tr> 
      <th scope="row">Database Host</th> 
      <td><input name="dbhost" type="text" size="25" value="localhost" /></td>
      <td>99% chance you won't need to change this value.</td> 
    </tr>
    <tr> 
      <th scope="row">Template Folder</th> 
      <td><input name="tplfld" type="text" size="25" value="default" /></td>
      <td>Type the name of your template folder. Use "<tt>default</tt>" for basic template.</td> 
    </tr>
    <tr> 
      <th scope="row">Language</th> 
      <td>
        <select name="locale" type="text">
          <option value="en_US.UTF8">English (United States)</option>
          <option value="fr_FR.UTF8">French (France)</option>
        </select>
      </td>
      <td>Choose the default language for your site.</td> 
    </tr>
  </table>
  <h2 class="step">
  <input name="submit" type="submit" value="Submit" />
  </h2>
</form> 
<?php
	break;

	case 2:
	$dbplatform = trim($_POST['dbplatform']);
	$dbname     = trim($_POST['dbname']);
    $uname      = trim($_POST['uname']);
    $passwrd    = trim($_POST['pwd']);
    $dbhost     = trim($_POST['dbhost']);
    $tplfld     = trim($_POST['tplfld']);
    $locale     = trim($_POST['locale']);
    
    define('DB_PLATFORM', $dbplatform);
    define('DB_DATABASE', $dbname);
    define('DB_USERNAME', $uname);
    define('DB_PASSWORD', $passwrd);
    define('DB_HOSTNAME', $dbhost);
    define('TEMPLATES_FOLDER', $tplfld);
    define('LOCALE', $locale);
    
    // Database Connection
    $dbTest = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die("Your connection to the server was refused.");
    mysql_select_db(DB_DATABASE, $dbTest) or die("You connected to the server, but your connection to the database was refused.");
    
    // We'll fail here if the values are no good.
	$handle = fopen('../includes/config.php', 'w');

    echo "<blockquote><code>";

    foreach ($configFile as $line_num => $line)
    {
        
        switch (substr($line,0,19))
        {
            case 'define("DB_PLATFORM':
                $line = str_replace("mysql", $dbplatform, $line);
                fwrite($handle, $line);
                echo $line . "<br/>";
                break;
            case 'define("DB_HOSTNAME':
                $line = str_replace("localhost", $dbhost, $line);
                fwrite($handle, $line);
                echo $line . "<br/>";
                break;
            case 'define("DB_DATABASE':
                $line = str_replace("pacercms", $dbname, $line);
                fwrite($handle, $line);
                echo $line . "<br/>";
                break;
            case 'define("DB_USERNAME':
                $line = str_replace("username", $uname, $line);
                fwrite($handle, $line);
                echo $line . "<br>";
                break;
            case 'define("DB_PASSWORD':
                $line = str_replace("password", $passwrd, $line);
                fwrite($handle, $line);
                echo $line . "<br/>";
                break;
            case 'define("TEMPLATES_F':
                $line = str_replace("default", $tplfld, $line);
                fwrite($handle, $line);
                echo $line . "<br/>";
                break;
            case 'define("LOCALE", "e':
                $line = str_replace("en_US.UTF8", $locale, $line);
                fwrite($handle, $line);
                echo $line . "<br/>";
                break;
            default:
                fwrite($handle, $line);
                echo $line . "<br/>";
        }
    }
    fclose($handle);
	chmod('../includes/config.php', 0666);

    echo "</code></blockquote>";

?>
 
<p>PacerCMS can now communicate with your database. If you are ready, time now to <a href="cm-install.php">run the install!</a></p> 
<?php
	break;
}
?>

<p id="footer">Install scripts modeled after <a href="http://wordpress.org">WordPress</a>'s simple but effective methods.</p>


</body>
</html>
