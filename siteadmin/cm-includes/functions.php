<?php

// Starting sessions
session_start();

// Database Connection
$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
mysql_select_db(DB_DATABASE, $CM_MYSQL);

// File handling for templates
function get_cm_header() {; include_once('header.php'); };
function get_cm_footer() {; include_once('footer.php'); };
function get_cm_menu() {; include_once('menu.php'); };

/*******************************************
	Function:	cm_error
*******************************************/
function cm_error($msg)
{;
	get_cm_header();
	echo "<h2>Error!</h2>";
	echo autop($msg);
	echo "<p><a href=\"javascript:history.back();\">Go Back</a>";
	get_cm_footer();
	exit;
};

#==========================================#
######## Security-Related Functions ########
#==========================================#


/*******************************************
	Function:	cm_auth_user
*******************************************/
function cm_auth_user($username,$password,$dest)
{;
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_users";
	$query_CM_Array .= " WHERE user_login = '$username' AND user_password = '$password';";	
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	if ($totalRows_CM_Array == 1) {
		$_SESSION['cm_user_id'] = $row_CM_Array['id'];
		$_SESSION['cm_user_fullname'] = $row_CM_Array['user_first_name'] . " " . $row_CM_Array['user_last_name'];
	};
	return $totalRows_CM_Array;
};


/*******************************************
	Function:	cm_reset_pass
*******************************************/
function cm_reset_pass($username,$email)
{;
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query = "SELECT * FROM cm_users";
	$query .= " WHERE user_login = '$username' AND user_email = '$email'";
	$query .= " LIMIT 1;";	
	// Run Query
	$CM_Array  = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);

	$id = $row_CM_Array['id'];
	
	if ($id != "") {
		
		// $username
		$salt = "abchefghjkmnpqrstuvwxyz23456789"; 
  		srand((double)microtime()*1000000); 
      	$i = 0; 
      	while ($i <= 7) { 
            $num = rand() % 33; 
            $tmp = substr($salt, $num, 1); 
            $password = $password . $tmp; 
            $i++; 
      	} 
      	
      	// Change to MD5 Hash of random password
      	$enc_password = md5($password);
      	$query = "UPDATE cm_users SET";
		$query .= " user_password = '$enc_password'";
		$query .= " WHERE id = $id";
		$ChangePassword  = mysql_query($query, $CM_MYSQL) or die(cm_error(mysql_error()));
      	
      	// E-mail new password to user
      	$subject = "PacerCMS - Your new username and password";
		$message = "\n ";
		$message .= "Your username and password:\n";
		$message .= "========================================\n";
		$message .= "Username:\t $username\n";
		$message .= "Password:\t $password\n ";
		$message .= "========================================\n";
		
		// Send the e-mail notification
		$sendit = mail($email, $subject, $message);      	

	};
	return $totalRows_CM_Array;
};

/*******************************************
	Function:	cm_auth_module
*******************************************/
function cm_auth_module($module)
{;
	if (!isset($_SESSION['cm_user_id'])) {
		$dest = $_SERVER['REQUEST_URI'];
		header("Location: login.php?dest=$dest");
		exit;
	};
	$user_id = $_SESSION['cm_user_id'];
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT * FROM cm_access";
	$query_CM_Array .= " WHERE user_id = '$user_id'";
	$query_CM_Array .= " AND access_type = 'module'";
	$query_CM_Array .= " AND access_option = '$module'";
	$query_CM_Array .= " AND access_value = 'true'";
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);	
	if ($totalRows_CM_Array > 0) {
		return true;
	} else {;
		cm_error("You do not have permission to access this page.");
		exit;
	};
};

/*******************************************
	Function:	cm_logout
*******************************************/
function cm_logout()
{;
	$cookiesSet = array_keys($_COOKIE);
	for ($x=0;$x<count($cookiesSet);$x++) setcookie($cookiesSet[$x],"",time()-1);
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
	   setcookie(session_name(), '', time()-42000, '/');
	};
	session_destroy();
	header("Location: login.php?msg=logout");
};

/*******************************************
	Function:	cm_auth_restrict
*******************************************/
function cm_auth_restrict($sel)
{;
	$user_id = $_SESSION['cm_user_id'];
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT * FROM cm_access";
	$query_CM_Array .= " WHERE user_id = '$user_id'";
	$query_CM_Array .= " AND access_type = 'string'";
	$query_CM_Array .= " AND access_option = '$sel'";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);	
	$myval = $row_CM_Array['access_value'];	
	if ($totalRows_CM_Array > 0) {
		return $myval;
	} else {;
		return false;
	};
};

/*******************************************
	Function:	cm_get_restrict
*******************************************/
function cm_get_restrict($string,$sel)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_access";
	$query_CM_Array .= " WHERE user_id = '$sel'";
	$query_CM_Array .= " AND access_type = 'string'";
	$query_CM_Array .= " AND access_option = '$string'";
	// Run Query
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);	
	$myval = $row_CM_Array['access_value'];	
	if ($totalRows_CM_Array > 0) {
		return $myval;
	} else {;
		return false;
	};	
}

///// Working with the cm_access table /////

/*******************************************
	Function:	cm_clear_access
*******************************************/
function cm_clear_access($sel)
{;
	$query = "DELETE FROM cm_access WHERE user_id = $sel;";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_add_access
*******************************************/
function cm_add_access($sel,$type,$option,$value)
{;
	if ($value == "") { $value = "false"; };	
	$query = "INSERT INTO cm_access (user_id,access_type,access_option,access_value) VALUES ($sel,'$type','$option','$value');";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_get_access
*******************************************/
function cm_get_access($module,$sel)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_access";
	$query_CM_Array .= " WHERE user_id = '$sel'";
	$query_CM_Array .= " AND access_type = 'module'";
	$query_CM_Array .= " AND access_option = '$module'";
	$query_CM_Array .= " AND access_value = 'true';";
	// Run Query
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	if ($totalRows_CM_Array > 0) {; 
		$stat = "true";
	} else {;
		$stat = "false";
	}; 
	return $stat;
}


#==========================================#
######### Building Lists and Menus #########
#==========================================#

/*******************************************
	Function:	cm_section_list
*******************************************/
function cm_section_list($module, $sel=1)
{;
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	// Database Query
	$query_CM_Array = "SELECT * FROM cm_sections ORDER BY section_priority ASC;";	
	// Run Query
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);	
	do {;	
		$id = $row_CM_Array['id'];
		$section_name = $row_CM_Array['section_name'];
		$section_url = $row_CM_Array['section_url'];		
		// If called from the Menu include
		if ($module == "menu") {;
		echo "\t<li><a href=\"$section_url\">" . htmlentities($section_name) . "</a></li>\n";
		};		
		// If called from "article-browse.php" module
		if ($module == "article-browse") {;
			echo "\t<li><a href=\"$module.php?section=$id\"";
			if ($sel == $id) {;
			echo " class=\"selected\"";;	
		};
			echo ">" . htmlentities($section_name) . "</a></li>\n";
		};		
		// If called from "article-edit.php" module
		if ($module == "article-edit" || $module == "staff-access") {;
			echo "\t<option value=\"$id\"";
			if ($sel == $id) {;
				echo " selected";;	
			};
			echo ">" . htmlentities($section_name) . "</option>\n";
		};		
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_issue_list
*******************************************/
function cm_issue_list($module, $sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT * FROM cm_issues ORDER BY issue_date DESC;";	
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$id = $row_CM_Array['id'];
		$date = $row_CM_Array['issue_date'];
		$volume = $row_CM_Array['issue_volume'];
		$number = $row_CM_Array['issue_number'];
		echo "\t<option value=\"$id\"";
		if ($sel == $id) {;
			echo " selected";;	
		};
		echo ">$date (Vol. $volume, No. $number)</option>\n";
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_volume_list
*******************************************/
function cm_volume_list($module, $sel=1)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT DISTINCT(issue_volume) FROM cm_issues ORDER BY issue_volume DESC;";	
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$volume = $row_CM_Array['issue_volume'];
		if ($module == "issue-browse") {;
			echo "\t<option value=\"$volume\"";
			if ($sel == $volume) {;
			echo " selected";;	
		};
		echo ">Volume $volume</option>\n";
		};
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_list_media
*******************************************/
function cm_list_media($sel,$display=false)
{;
	if ($display == true) {
		$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
		mysql_select_db(DB_DATABASE, $CM_MYSQL);	
		$query_CM_Array = "SELECT * FROM cm_media WHERE article_id = '$sel';";	
		$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
		$row_CM_Array = mysql_fetch_assoc($CM_Array);
		$totalRows_CM_Array = mysql_num_rows($CM_Array);
		echo "<p><strong>Linked Media:</strong></p>";
		if ($totalRows_CM_Array > 0) {;
			do {;		
				$id = $row_CM_Array['id'];
				$title = $row_CM_Array['media_title'];
				$src = $row_CM_Array['media_src'];
				$type = $row_CM_Array['media_type'];
				echo "<p>";
				cm_display_media($src,$type,$title);
				echo "</p>\n";
				echo "<p class=\"systemMessage\"><a href=\"article-media.php?id=$id\">Edit '$title' ($type)</a> | <a href=\"article-media.php?id=$id#delete\">Delete</a></p>\n";
			} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
			echo "<p><strong><a href=\"article-media.php?action=new&amp;article_id=$sel\">Add a file</a></strong></p>";
		} else {;
			echo "<p><em>No linked files. <a href=\"article-media.php?action=new&amp;article_id=$sel\">Add a file</a></em></p>";
		};
	} else {;
		$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
		mysql_select_db(DB_DATABASE, $CM_MYSQL);	
		$query_CM_Array = "SELECT * FROM cm_media WHERE article_id = '$sel';";	
		$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
		$row_CM_Array = mysql_fetch_assoc($CM_Array);
		$totalRows_CM_Array = mysql_num_rows($CM_Array);
		if ($totalRows_CM_Array > 0) {;
			do {;		
				$id = $row_CM_Array['id'];
				$title = $row_CM_Array['media_title'];
				echo "<li><a href=\"article-media.php?id=$id\">#$id: $title</a></li>\n";
			} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
		} else {;
			echo "<li><em>No linked files.</em></li>";
		};
	};
};

/*******************************************
	Function:	cm_user_list
*******************************************/
function cm_user_list($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT * FROM cm_users ORDER BY user_last_name ASC, user_first_name ASC;";	
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$id = $row_CM_Array['id'];
		$last_name = $row_CM_Array['user_last_name'];
		$first_name = $row_CM_Array['user_first_name'];
		echo "\t<option value=\"$id\"";
		if ($sel == $id) {;
			echo " selected";;	
		};
		echo ">$last_name, $first_name</option>\n";
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_poll_list
*******************************************/
function cm_poll_list($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT * FROM cm_poll_questions ORDER BY poll_created DESC, id DESC;";	
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$id = $row_CM_Array['id'];
		$question = trim_text($row_CM_Array['poll_question'], 50);
		echo "\t<option value=\"$id\"";
		if ($sel == $id) {;
			echo " selected";;	
		};
		echo ">$question</option>\n";
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_client_list
*******************************************/
function cm_client_list($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);	
	$query_CM_Array = "SELECT * FROM cm_clients ORDER BY client_active ASC, client_name ASC;";	
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));;
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$id = $row_CM_Array['id'];
		$name = htmlentities($row_CM_Array['client_name']);
		$active = $row_CM_Array['client_active'];
		echo "\t<option value=\"$id\"";
		if ($sel == $id) {;
			echo " selected";;	
		};
		if ($active == 'Y') {
			echo ">$name</option>\n";
		} else {;
			echo ">[Closed] $name</option>\n";
		};
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_display_media
*******************************************/
function cm_display_media($src,$type,$title='')
{;
	if ($type == "jpg" || $type == "png" || $type == "gif") {;
		echo "<img src=\"$src\" alt=\"$type\" />";
	};
	if ($type == "pdf" || $type == "doc") {;
		echo "Related Document: <a href=\"$src\">Dowload '$title' ($type)</a>.";
	};
	if ($type == "wav" || $type == "mp3") {;
		echo "Media File: <a href=\"$src\">Play '$title' ($type)</a>.";
	};
	if ($type == "swf") {;
		echo "<object type=\"application/x-shockwave-flash\" data=\"$src\">\n";
		echo "\t<param name=\"movie\" value=\"$src\" />\n";
		echo "</object>\n";
	};
	if ($type == "url") {;
		echo "Related: <a href=\"$src\">Open related link</a>.";
	};
};


#==========================================#
###########  Returns Information ###########
#==========================================#

/*******************************************
	Function:	cm_current_issue
*******************************************/
function cm_current_issue($format)
{;	
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	if ($format != "id") {;
		$format = "issue_" . $format;
	};	
	$query_CM_Array = "SELECT cm_issues.$format AS myvalue";	
	$query_CM_Array .= " FROM cm_issues, cm_settings";
	$query_CM_Array .= " WHERE cm_settings.current_issue = cm_issues.id";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );	
	$value = $row_CM_Array['myvalue'];
	return $value;
};
/*******************************************
	Function:	cm_next_issue	
*******************************************/
function cm_next_issue($format)
{;	
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	if ($format != "id") {;
	$format = "issue_" . $format;
	};	
	$query_CM_Array = "SELECT cm_issues.$format AS myvalue";	
	$query_CM_Array .= " FROM cm_issues, cm_settings";
	$query_CM_Array .= " WHERE cm_settings.next_issue = cm_issues.id";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );	
	$value = $row_CM_Array['myvalue'];
	return $value;
};

/*******************************************
	Function:	cm_issue_info
*******************************************/
function cm_issue_info($format, $sel)
{;	
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT $format AS myvalue";	
	$query_CM_Array .= " FROM cm_issues";
	$query_CM_Array .= " WHERE id = '$sel'";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );
	do {;	
		$value = $row_CM_Array['myvalue'];
		return $value;
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_section_info
*******************************************/
function cm_section_info($format, $sel)
{;	
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT $format AS myvalue";	
	$query_CM_Array .= " FROM cm_sections";
	$query_CM_Array .= " WHERE id = '$sel'";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );
	do {;	
		$value = $row_CM_Array['myvalue'];
		return $value;
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};

/*******************************************
	Function:	cm_get_settings
*******************************************/
function cm_get_settings($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT $sel AS myval FROM cm_settings;";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	do {;
		$myval = $row_CM_Array['myval'];
		return $myval;	
	} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
};


#==========================================#
############# Managing Pages ############
#==========================================#

/*******************************************
	Function:	cm_add_page
*******************************************/
function cm_add_page($title,$short_title,$text,$side_text,$slug,$edited)
{;
	$edited = date("Y-m-d h:i:s",time());
	$query = "INSERT INTO cm_pages (page_title,page_short_title,page_text,page_side_text,page_slug,page_edited)";
	$query .= " VALUES ('$title','$short_title','$text','$side_text','$slug','$edited')";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_page
*******************************************/
function cm_edit_page($title,$short_title,$text,$side_text,$slug,$edited,$id)
{;
	$edited = date("Y-m-d h:i:s",time());
	$query = "UPDATE cm_pages SET";
	$query .= " page_title = '$title',";
	$query .= " page_short_title = '$short_title',";
	$query .= " page_slug = '$slug',";
	$query .= " page_text = '$text',";
	$query .= " page_side_text = '$side_text',";
	$query .= " page_edited = '$edited' ";
	$query .= " WHERE id = $id";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_delete_page
*******************************************/
function cm_delete_page($sel)
{;	
	$query = "DELETE FROM cm_pages WHERE id = '$sel';";
	$stat = cm_run_query($query);
	return $stat;
};


#==========================================#
############# Managing Articles ############
#==========================================#

/*******************************************
	Function:	cm_add_article
*******************************************/
function cm_add_article($title,$subtitle,$author,$author_title,$summary,$text,$keywords,$priority,$section,$issue,$published)
{;
	$edited = date("Y-m-d h:i:s",time());
	$word_count = count_words($text);
	$query = "INSERT INTO cm_articles (article_title,article_subtitle,article_author,article_author_title,article_summary,article_text,article_keywords,article_priority,section_id,issue_id,article_publish,article_edit,article_word_count)";
	$query .= " VALUES ('$title','$subtitle','$author','$author_title','$summary','$text','$keywords','$priority','$section','$issue','$published','$edited',$word_count)";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_article
*******************************************/
function cm_edit_article($title,$subtitle,$author,$author_title,$summary,$text,$keywords,$priority,$section,$issue,$published,$id)
{;
	$word_count = count_words($text);
	$edited = date("Y-m-d h:i:s",time());
	$query = "UPDATE cm_articles SET";
	$query .= " article_title = '$title',";
	$query .= " article_subtitle = '$subtitle',";
	$query .= " article_author = '$author',";
	$query .= " article_author_title = '$author_title',";
	$query .= " article_summary = '$summary',";
	$query .= " article_text = '$text',";
	$query .= " article_keywords = '$keywords',";
	$query .= " article_priority = '$priority',";
	$query .= " section_id = '$section',";
	$query .= " issue_id = '$issue',";
	$query .= " article_publish = '$published',";
	$query .= " article_edit = '$edited',";
	$query .= " article_word_count = '$word_count'";
	$query .= " WHERE id = $id";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_delete_article
*******************************************/
function cm_delete_article($sel)
{;	
	$query = "DELETE FROM cm_articles WHERE id = '$sel';";
	$stat = cm_run_query($query);
	$query = "DELETE FROM cm_media WHERE article_id = '$sel';";
	$stat = cm_run_query($query);
	return $stat;
};

//// Media Module Portion of the System ////

/*******************************************
	Function:	cm_add_media	
*******************************************/
function cm_add_media($article_id,$title,$src,$type,$caption,$credit)
{;
	$query .= "INSERT INTO cm_media (article_id,media_title,media_src,media_type,media_caption,media_credit)";
	$query .= " VALUES ('$article_id','$title','$src','$type','$caption','$credit');";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_media
*******************************************/
function cm_edit_media($article_id,$title,$src,$type,$caption,$credit,$id)
{;
	$query = "UPDATE cm_media SET";
	$query .= " article_id = '$article_id',";
	$query .= " media_title = '$title',";
	$query .= " media_src = '$src',";
	$query .= " media_type = '$type',";
	$query .= " media_caption = '$caption',";
	$query .= " media_credit = '$credit'";
	$query .= " WHERE id = $id";	
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_delete_media
*******************************************/
function cm_delete_media($module, $sel)
{;	
	$query = "DELETE FROM cm_media WHERE id = '$sel';";
	$stat = cm_run_query($query);
	return $stat;
};

#==========================================#
#############  Managing Issues #############
#==========================================#

/*******************************************
	Function:	cm_add_issue
*******************************************/
function cm_add_issue($date,$volume,$number,$circulation,$online_only)
{;
	$query = "INSERT INTO cm_issues (issue_date,issue_volume,issue_number,issue_circulation,online_only)";
	$query .= " VALUES ('$date','$volume','$number','$circulation','$online_only');";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_issue
*******************************************/
function cm_edit_issue($date,$volume,$number,$circulation,$online_only,$id)
{;
	$query = "UPDATE cm_issues SET";
	$query .= " issue_date = '$date',";
	$query .= " issue_volume = '$volume',";
	$query .= " issue_number = '$number',";
	$query .= " issue_circulation = '$circulation',";
	$query .= " online_only = '$online_only'";
	$query .= " WHERE id = $id;";
	$stat = cm_run_query($query);
	return $stat;
};

#==========================================#
############  Managing Sections ############
#==========================================#

/*******************************************
	Function:	cm_edit_section
*******************************************/
function cm_edit_section($name,$editor,$editor_title,$editor_email,$url,$sidebar,$priority,$id)
{;
	$query = "UPDATE cm_sections SET";
	$query .= " section_name = '$name',";
	$query .= " section_editor = '$editor',";
	$query .= " section_editor_title = '$editor_title',";
	$query .= " section_editor_email = '$editor_email',";
	$query .= " section_url = '$url',";
	$query .= " section_sidebar = '$sidebar',";
	$query .= " section_priority = '$priority'";
	$query .= " WHERE id = $id";
	$stat = cm_run_query($query);
	return $stat;
};

#==========================================#
############## Managing Users ##############
#==========================================#

/*******************************************
	Function:	cm_add_user	
*******************************************/
function cm_add_user($user_login,$user_password,$user_first_name,$user_middle_name,$user_last_name,$user_job_title,$user_email,$user_telephone,$user_mobile,$user_address,$user_city,$user_state,$user_zipcode,$user_im_aol,$user_im_msn,$user_im_yahoo,$user_im_jabber,$user_profile)
{;
	$query = "INSERT INTO cm_users (user_login,user_password,user_first_name,user_middle_name,user_last_name,user_job_title,user_email,user_telephone,user_mobile,user_address,user_city,user_state,user_zipcode,user_im_aol,user_im_msn,user_im_yahoo,user_im_jabber,user_profile)";
	$query .= " VALUES ('$user_login','$user_password','$user_first_name','$user_middle_name','$user_last_name','$user_job_title','$user_email','$user_telephone','$user_mobile','$user_address','$user_city','$user_state','$user_zipcode','$user_im_aol','$user_im_msn','$user_im_yahoo','$user_im_jabber','$user_profile');";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_user	
*******************************************/
function cm_edit_user($user_login,$user_password,$user_first_name,$user_middle_name,$user_last_name,$user_job_title,$user_email,$user_telephone,$user_mobile,$user_address,$user_city,$user_state,$user_zipcode,$user_im_aol,$user_im_msn,$user_im_yahoo,$user_im_jabber,$user_profile,$id)
{;
	$query = "UPDATE cm_users SET";
	$query .= " user_login = '$user_login',";
	$query .= " user_password = '$user_password',";
	$query .= " user_first_name = '$user_first_name',";
	$query .= " user_middle_name = '$user_middle_name',";
	$query .= " user_last_name = '$user_last_name',";
	$query .= " user_job_title = '$user_job_title',";
	$query .= " user_email = '$user_email',";
	$query .= " user_telephone = '$user_telephone',";
	$query .= " user_mobile = '$user_mobile',";
	$query .= " user_address = '$user_address',";
	$query .= " user_city = '$user_city',";
	$query .= " user_state = '$user_state',";
	$query .= " user_zipcode = '$user_zipcode',";
	$query .= " user_im_aol = '$user_im_aol',";
	$query .= " user_im_msn = '$user_im_msn',";
	$query .= " user_im_yahoo = '$user_im_yahoo',";
	$query .= " user_im_jabber = '$user_im_jabber',";
	$query .= " user_profile = '$user_profile'";
	$query .= " WHERE id = $id;";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_delete_user
*******************************************/
function cm_delete_user($sel)
{;	
	$stat = cm_clear_access($sel); // Lock out user
	$query = "DELETE FROM cm_users WHERE id = '$sel';";
	$stat = cm_run_query($query);
	return $stat;	
};
#==========================================#
##########  Managing User Profiles #########
#==========================================#
/*******************************************
	Function:	cm_edit_profile	
*******************************************/
function cm_edit_profile($user_password,$user_email,$user_telephone,$user_mobile,$user_address,$user_city,$user_state,$user_zipcode,$user_im_aol,$user_im_msn,$user_im_yahoo,$user_im_jabber,$user_profile,$id)
{;
	$query = "UPDATE cm_users SET";
	$query .= " user_password = '$user_password',";
	$query .= " user_email = '$user_email',";
	$query .= " user_telephone = '$user_telephone',";
	$query .= " user_mobile = '$user_mobile',";
	$query .= " user_address = '$user_address',";
	$query .= " user_city = '$user_city',";
	$query .= " user_state = '$user_state',";
	$query .= " user_zipcode = '$user_zipcode',";
	$query .= " user_im_aol = '$user_im_aol',";
	$query .= " user_im_msn = '$user_im_msn',";
	$query .= " user_im_yahoo = '$user_im_yahoo',";
	$query .= " user_im_jabber = '$user_im_jabber',";
	$query .= " user_profile = '$user_profile'";
	$query .= " WHERE id = $id;";
	$stat = cm_run_query($query);
	return $stat;
};

#==========================================#
########## Managing Poll Questions #########
#==========================================#

/*******************************************
	Function:	cm_add_poll
*******************************************/
function cm_add_poll($question,$r1,$r2,$r3,$r4,$r5,$r6,$r7,$r8,$r9,$r10,$article)
{;
	$created = date("Y-m-d h:i:s",time());
	$query = "INSERT INTO ";
	$query .= " cm_poll_questions (poll_created,poll_question,poll_response_1,poll_response_2,poll_response_3,poll_response_4,poll_response_5,poll_response_6,poll_response_7,poll_response_8,poll_response_9,poll_response_10,article_id)";
	$query .= "  VALUES ('$created','$question','$r1','$r2','$r3','$r4','$r5','$r6','$r7','$r8','$r9','$r10','$article');";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_poll
*******************************************/
function cm_edit_poll($question,$r1,$r2,$r3,$r4,$r5,$r6,$r7,$r8,$r9,$r10,$article,$id)
{;
	$query = "UPDATE cm_poll_questions SET";
	$query .= " poll_question = '$question',";
	$query .= " poll_response_1 = '$r1',";
	$query .= " poll_response_2 = '$r2',";
	$query .= " poll_response_3 = '$r3',";
	$query .= " poll_response_4 = '$r4',";
	$query .= " poll_response_5 = '$r5',";
	$query .= " poll_response_6 = '$r6',";
	$query .= " poll_response_7 = '$r7',";
	$query .= " poll_response_8 = '$r8',";
	$query .= " poll_response_9 = '$r9',";
	$query .= " poll_response_10 = '$r10',";
	$query .= " article_id = '$article'";	
	$query .= " WHERE id = '$id';";
	$stat = cm_run_query($query);
	return $stat;
};


/*******************************************
	Function:	cm_poll_results
*******************************************/
function cm_poll_results($sel)
{
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);

	$query_CM_Array = "SELECT COUNT(id) AS total";
	$query_CM_Array .= " FROM cm_poll_ballot";
	$query_CM_Array .= " WHERE poll_id = '$sel'";
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	
	$total = $row_CM_Array['total'];

	$query_CM_Array = "SELECT ballot_response AS response, COUNT(ballot_response) AS votes, COUNT(id) AS total";
	$query_CM_Array .= " FROM cm_poll_ballot";
	$query_CM_Array .= " WHERE poll_id = '$sel'";
	$query_CM_Array .= " GROUP BY ballot_response;";
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	if ($total > 0) {;
		echo "<ul>";
		do {;
			$response = $row_CM_Array['response'];
			$votes = $row_CM_Array['votes'];
			$percent = (100 * ($votes / $total));
			$percent = number_format($percent, 2, '.', '');
			echo "<li><strong>Option $response:</strong> $votes votes ($percent%)</li>\n";	
		} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
		echo "</ul>";
		echo "<p><strong>Total votes:</strong> $total</p>";
	} else {;
	    echo "<p>No one has voted yet.</p>";
	};
}

/*******************************************
	Function:	cm_poll_cleanup
*******************************************/
function cm_poll_cleanup($sel)
{
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);

	$query_CM_Array = "SELECT ballot_ip_address, COUNT(ballot_ip_address) AS ballots";
	$query_CM_Array .= " FROM cm_poll_ballot";
	$query_CM_Array .= " WHERE poll_id = '$sel'";
	$query_CM_Array .= " GROUP BY ballot_ip_address";
	$query_CM_Array .= " HAVING ballots > 1;";
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(mysql_error());
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array);
	
	if ($totalRows_CM_Array > 0) {;
		do {
			$ipad = $row_CM_Array['ballot_ip_address'];
			cm_poll_delete_ballots($sel,$ipad);
		} while ($row_CM_Array = mysql_fetch_assoc($CM_Array));
		echo "This poll had $totalRows_CM_Array multiple voter(s).<br /> All have been cleared.";
	} else {;
		echo "Poll audit successful.<br /> No multiple-voters detected.";
	};
};

/*******************************************
	Function:	cm_poll_delete_ballots
*******************************************/
function cm_poll_delete_ballots($sel,$ipad)
{
	$query = "DELETE";
	$query .= " FROM cm_poll_ballot";
	$query .= " WHERE ballot_ip_address = '$ipad'";
	$query .= " AND poll_id = '$sel'";
	$stat = cm_run_query($query);
	return $stat;	
};

#==========================================#
###########  Managing Advertising ##########
#==========================================#

/*******************************************
	Function:	cm_add_advertising
*******************************************/
function cm_add_advertising($issue_id,$client_id,$size,$description,$cost,$notes,$sold_by,$page,$ledgered,$quickbooks,$billed,$payment)
{;
	$submitted = date("Y-m-d h:i:s",time());
	$query = "INSERT INTO cm_advertising (issue_id,client_id,ad_size,ad_description,ad_cost,ad_notes,ad_sold_by,ad_page,ad_submitted,ad_ledgered,ad_quickbooks,ad_billed,ad_payment)";
	$query .= " VALUES ('$issue_id','$client_id','$size','$description','$cost','$notes','$sold_by','$page','$submitted','$ledgered','$quickbooks','$billed','$payment');";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_advertising
*******************************************/
function cm_edit_advertising($issue_id,$client_id,$size,$desc,$cost,$notes,$sold_by,$page,$ledgered,$quickbooks,$billed,$payment,$id)
{;
	$query = "UPDATE cm_advertising SET";
	$query .= " issue_id = '$issue_id',";
	$query .= " client_id = '$client_id',";
	$query .= " ad_size = '$size',";
	$query .= " ad_description = '$desc',";
	$query .= " ad_cost = '$cost',";
	$query .= " ad_notes = '$notes',";
	$query .= " ad_sold_by = '$sold_by',";
	$query .= " ad_page = '$page',";
	$query .= " ad_ledgered = '$ledgered',";
	$query .= " ad_quickbooks = '$quickbooks',";
	$query .= " ad_billed = '$billed',";
	$query .= " ad_payment = '$payment'";
	$query .= " WHERE id = $id;";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_delete_advertising
*******************************************/
function cm_delete_advertising($sel)
{;	
	$query = "DELETE FROM cm_advertising WHERE id = '$sel';";
	$stat = cm_run_query($query);
	return $stat;	
};

/*******************************************
	Function:	cm_issue_sales
*******************************************/
function cm_issue_sales($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT SUM(ad_cost) AS myvalue";	
	$query_CM_Array .= " FROM cm_advertising";
	$query_CM_Array .= " WHERE issue_id = '$sel'";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );
	$value = $row_CM_Array['myvalue'];
	if ($value == '') {; $value = "0.00"; };
	return $value;

};

/*******************************************
	Function:	cm_issue_paid
*******************************************/
function cm_issue_paid($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT SUM(ad_cost) AS myvalue";	
	$query_CM_Array .= " FROM cm_advertising";
	$query_CM_Array .= " WHERE issue_id = '$sel'";
	$query_CM_Array .= " AND ad_payment > '0000-00-00 00:00:00';";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );
	$value = $row_CM_Array['myvalue'];
	if ($value == '') {; $value = "0.00"; };
	return $value;
};

/*******************************************
	Function:	cm_client_sales
*******************************************/
function cm_client_sales($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT SUM(ad_cost) AS myvalue";	
	$query_CM_Array .= " FROM cm_advertising";
	$query_CM_Array .= " WHERE client_id = '$sel'";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );	
	$value = $row_CM_Array['myvalue'];
	if ($value == '') {; $value = "0.00"; };
	return $value;
};

/*******************************************
	Function:	cm_client_paid
*******************************************/
function cm_client_paid($sel)
{;
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	$query_CM_Array = "SELECT SUM(ad_cost) AS myvalue";	
	$query_CM_Array .= " FROM cm_advertising";
	$query_CM_Array .= " WHERE client_id = '$sel'";
	$query_CM_Array .= " AND ad_payment > '0000-00-00 00:00:00';";
	$CM_Array  = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	$row_CM_Array  = mysql_fetch_assoc($CM_Array);
	$totalRows_CM_Array = mysql_num_rows($CM_Array );
	$value = $row_CM_Array['myvalue'];
	if ($value == '') {; $value = "0.00"; };
	return $value;
};

/*******************************************
	Function:	cm_get_price
*******************************************/
function cm_get_price($col,$in,$sched)
{;

	$local_rate = cm_get_settings('local_rate');
	$national_rate = cm_get_settings('national_rate');

	if ($sched == "L" || $sched == "l") { $rate = $local_rate;	};
	if ($sched == "N" || $sched == "n") { $rate = $national_rate; };
	$ci = $col * $in;
	$price = $ci * $rate;
	echo number_format($price,2);
	return;
};

#==========================================#
#############  Managing Clients ############
#==========================================#

/*******************************************
	Function:	cm_add_client
*******************************************/
function cm_add_client($name,$type,$contact,$address,$city,$state,$zipcode,$telephone,$fax,$email,$active,$notes)
{;
	$query = " INSERT INTO cm_clients (client_name,client_type,client_contact,client_address,client_city,client_state,client_zipcode,client_telephone,client_fax,client_email,client_active,client_notes)";
	$query .= " VALUES ('$name','$type','$contact','$address','$city','$state','$zipcode','$telephone','$fax','$email','$active','$client_notes')";
	$stat = cm_run_query($query);
	return $stat;
};


/*******************************************
	Function:	cm_edit_client
*******************************************/
function cm_edit_client($name,$type,$contact,$address,$city,$state,$zipcode,$telephone,$fax,$email,$active,$notes,$id)
{;
	$query = "UPDATE cm_clients SET";
	$query .= " client_name = '$name',";
	$query .= " client_type = '$type',";
	$query .= " client_contact = '$contact',";
	$query .= " client_address = '$address',";
	$query .= " client_state = '$state',";
	$query .= " client_zipcode = '$zipcode',";
	$query .= " client_telephone = '$telephone',";
	$query .= " client_fax = '$fax',";
	$query .= " client_email = '$email',";
	$query .= " client_active = '$active',";
	$query .= " client_notes = '$notes'";
	$query .= " WHERE id = $id;";
	$stat = cm_run_query($query);
	return $stat;
};



#==========================================#
##############  Site Settings ##############
#==========================================#

/*******************************************
	Function:	cm_edit_publish_settings
*******************************************/
function cm_edit_publish_settings($current_issue,$next_issue,$id=1)
{;
	$query = "UPDATE cm_settings SET current_issue = $current_issue, next_issue = $next_issue WHERE id = $id";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_poll_settings
*******************************************/
function cm_edit_poll_settings($active_poll,$id=1)
{;
	if ($active_poll == "") {
		$active_poll = 0;
	}
	$query = "UPDATE cm_settings SET active_poll = $active_poll WHERE id = $id";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_ad_rates
*******************************************/
function cm_edit_ad_rates($local_rate,$national_rate,$id=1)
{;
	$query = "UPDATE cm_settings SET local_rate = '$local_rate', national_rate = '$national_rate' WHERE id = $id";
	$stat = cm_run_query($query);
	return $stat;
};

/*******************************************
	Function:	cm_edit_settings
*******************************************/
function cm_edit_settings($name,$description,$url,$email,$address,$city,$state,$zipcode,$telephone,$fax,$announce,$id)
{;
	$query = "UPDATE cm_settings SET";
	$query .= " site_name = '$name',";
	$query .= " site_description = '$description',";
	$query .= " site_url = '$url',";
	$query .= " site_email = '$email',";
	$query .= " site_address = '$address',";
	$query .= " site_city = '$city',";
	$query .= " site_state = '$state',";
	$query .= " site_zipcode = '$zipcode',";
	$query .= " site_telephone = '$telephone',";
	$query .= " site_fax = '$fax',";
	$query .= " site_announcement = '$announce'";
	$query .= " WHERE id = $id";
	$stat = cm_run_query($query);
	return $stat;
};

#==========================================#
###########  Universal Functions ###########
#==========================================#
function cm_run_query($query)
{
	// Database Connection
	$CM_MYSQL = mysql_pconnect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(cm_error(mysql_error()));
	mysql_select_db(DB_DATABASE, $CM_MYSQL);
	// Database Query
	$query_CM_Array = $query;
	// Run Query
	$CM_Array = mysql_query($query_CM_Array, $CM_MYSQL) or die(cm_error(mysql_error()));
	return $CM_Array;
};

/*******************************************
	Function:	autop
*******************************************/
function autop($pee, $br = 1) {
	$pee = $pee . "\n"; // just to make things a little easier, pad the end
	$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
	$pee = preg_replace('!(<(?:table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', "\n$1", $pee); // Space things out a little
	$pee = preg_replace('!(</(?:table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', "$1\n", $pee); // Space things out a little
	$pee = preg_replace("/(\r\n|\r)/", "\n", $pee); // cross-platform newlines
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "\t<p>$1</p>\n", $pee); // make paragraphs, including one at the end
	$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
	$pee = preg_replace('!<p>\s*(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $pee);
	$pee = preg_replace('!(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee);
	if ($br) $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
	$pee = preg_replace('!(</?(?:table|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*<br />!', "$1", $pee);
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|th|pre|td|ul|ol)>)!', '$1', $pee);
	$pee = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $pee);
	return $pee;
};

/*******************************************
	Function:	count_words
*******************************************/
function count_words($string)
{
	$word_count = 0;
	$string = eregi_replace(" +", " ", $string);
	$string = explode(" ", $string);
	
	while (list(, $word) = each ($string)) {
		if (eregi("[0-9A-Za-z?-??-??-?]", $word)) {
			$word_count++;
		}
	}
	return($word_count);
}

/*******************************************
	Function:	trim_text
*******************************************/
function trim_text($text,$length)
{
	if (strlen($text) > $length) {;
		$text = $text." ";
		$text = substr($text,0,$length);
		$text = substr($text,0,strrpos($text,' '));
		$text = $text."...";
	};
	return $text;
}

#==========================================#
############## Site Constants ##############
#==========================================#

$current_issue_id = cm_current_issue('id'); 
$current_issue_date = cm_current_issue('date');
$next_issue_id = cm_next_issue('id'); 
$next_issue_date = cm_next_issue('date');

$restrict_issue = cm_get_restrict('restrict_issue', $_SESSION['cm_user_id']);
$restrict_section = cm_get_restrict('restrict_section', $_SESSION['cm_user_id']);

$show_issue_browse = cm_get_access('issue-browse', $_SESSION['cm_user_id']);
$show_issue_edit = cm_get_access('issue-edit', $_SESSION['cm_user_id']);
$show_page_browse = cm_get_access('page-browse', $_SESSION['cm_user_id']);
$show_page_edit = cm_get_access('page-edit', $_SESSION['cm_user_id']);
$show_profile = cm_get_access('profile', $_SESSION['cm_user_id']);
$show_section_browse = cm_get_access('section-browse', $_SESSION['cm_user_id']);
$show_section_edit = cm_get_access('section-edit', $_SESSION['cm_user_id']);
$show_server_info = cm_get_access('server-info', $_SESSION['cm_user_id']);
$show_settings = cm_get_access('settings', $_SESSION['cm_user_id']);
$show_staff_access = cm_get_access('staff-access', $_SESSION['cm_user_id']);
$show_staff_browse = cm_get_access('staff-browse', $_SESSION['cm_user_id']);
$show_staff_edit = cm_get_access('staff-edit', $_SESSION['cm_user_id']);
$show_index = cm_get_access('index', $_SESSION['cm_user_id']);
$show_article_media = cm_get_access('article-media', $_SESSION['cm_user_id']);
$show_article_edit = cm_get_access('article-edit', $_SESSION['cm_user_id']);
$show_article_browse = cm_get_access('article-browse', $_SESSION['cm_user_id']);
$show_submitted_browse = cm_get_access('submitted-browse', $_SESSION['cm_user_id']);
$show_submitted_edit = cm_get_access('submitted-edit', $_SESSION['cm_user_id']);
$show_poll_browse = cm_get_access('poll-browse', $_SESSION['cm_user_id']);
$show_poll_edit = cm_get_access('poll-edit', $_SESSION['cm_user_id']);
$show_advertising_browse = cm_get_access('advertising-browse', $_SESSION['cm_user_id']);
$show_advertising_edit = cm_get_access('advertising-edit', $_SESSION['cm_user_id']);
$show_advertising_clients = cm_get_access('advertising-clients', $_SESSION['cm_user_id']);
$show_advertising_rates = cm_get_access('advertising-rates', $_SESSION['cm_user_id']);
?>