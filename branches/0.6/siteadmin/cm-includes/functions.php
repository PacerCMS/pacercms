<?php

// Starting sessions
session_start();

// Database Connection
$cm_db = ADONewConnection('mysql');
$cm_db->Connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// File handling for templates
function get_cm_header() { include_once('header.php'); }
function get_cm_footer() { include_once('footer.php'); }
function get_cm_menu() { include_once('menu.php'); }

/**
 * Error handler
 *
 * Generates an error message within the header and footer wrapper.
 *
 * @param    string  Error message to produce
 * @return   null    Script terminates on exit
 */
function cm_error($msg)
{
    get_cm_header();
    echo "<h2>Error!</h2>";
    echo "<p class=\"alertMessage\">$msg</p>";
    echo "<p><a href=\"javascript:history.back();\">Go Back</a>";
    get_cm_footer();
    exit;
}

#==========================================#
######## Security-Related Functions ########
#==========================================#

/**
 * Authenticate user
 *
 * Queries users table for username and password match
 *
 * @param    string  Username to authenticate
 * @param    string  Password
 * @return   int     Number of rows found; 1 - Authenticated, !=1 - Not Authenticated 
 */
function cm_auth_user($username,$password)
{
    $username = htmlentities($username, ENT_QUOTES, 'UTF-8');
    $password = htmlentities($password, ENT_QUOTES, 'UTF-8');

    // Database Query
    $query = "SELECT id FROM cm_users";
    $query .= " WHERE user_login = '$username' AND user_password = '$password';";    
    // Run Query
    $result = cm_run_query($query);

    if ($result->RecordCount() == 1)
    {
        // User authenticated
        $_SESSION['user_data']['id'] = $result->Fields('id');
       
        cm_settings_data(); // Load System Settings
        cm_issues_data(); // Load Issue Data
        cm_users_data(); // Load User Data
        cm_access_data(); // Load User Access
        // Authenticated
        return true;
    } else {
       
       // Not authenticated
       return false;
       
    }

}

/**
 * Reset user password
 *
 * Sets users password to seven random charachters, e-mails them.
 *
 * @param    string  Username
 * @param    string  E-mail address to send notification
 * @return   int     Number of rows found; 1 - true, 0 - false
 */
function cm_reset_pass($username,$email)
{       
    $query = "SELECT * FROM cm_users";
    $query .= " WHERE user_login = '$username' AND user_email = '$email'";
    $query .= " LIMIT 1;";    

    $result = cm_run_query($query);
    $id = $result->Fields('id');
    
    if (is_numeric($id))
    {
        // $username
        $salt = "abchefghjkmnpqrstuvwxyz23456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 

        while ($i <= 7)
        { 
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
        $result = cm_run_query($query);
          
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

        // Verified
        return true;

    } else {
       
       // Not verified
       return false;
       
    }

}

/**
 * Load settings data
 *
 * Places contents of settings table into session variables
 *
 * @param    int     ID of selected setting; default 1
 */
function cm_settings_data($sel='1')
{
    // Settings
    $query = "SELECT * FROM cm_settings WHERE id = $sel; ";
    $result = cm_run_query($query);
    // Unset setting data
    unset($_SESSION['setting_data']);
    // Load settings
    $_SESSION['setting_data']['id'] = $result->Fields('id');
    $_SESSION['setting_data']['site_name'] = $result->Fields('site_name');    $_SESSION['setting_data']['site_url'] = $result->Fields('site_url');    $_SESSION['setting_data']['site_email'] = $result->Fields('site_email');    $_SESSION['setting_data']['site_address'] = $result->Fields('site_address');    $_SESSION['setting_data']['site_city'] = $result->Fields('site_city');    $_SESSION['setting_data']['site_state'] = $result->Fields('site_state');    $_SESSION['setting_data']['site_zipcode'] = $result->Fields('state_zipcode');    $_SESSION['setting_data']['site_telephone'] = $result->Fields('site_telephone');    $_SESSION['setting_data']['site_fax'] = $result->Fields('site_fax');    $_SESSION['setting_data']['site_announcement'] = $result->Fields('site_announcement');    $_SESSION['setting_data']['site_description'] = $result->Fields('site_description');    $_SESSION['setting_data']['current_issue'] = $result->Fields('current_issue');    $_SESSION['setting_data']['next_issue'] = $result->Fields('next_issue');    $_SESSION['setting_data']['active_poll'] = $result->Fields('active_poll');    $_SESSION['setting_data']['database_version'] = $result->Fields('database_version');
}

/**
 * Load issues data
 *
 * Places issue data for current and next issue into session variables
 */
function cm_issues_data()
{
    // Unset issue data
    unset($_SESSION['issue_data']);
 
    // Current issue    
    $id = cm_get_settings('current_issue');
    $query = "SELECT id, issue_date, issue_volume, issue_number FROM cm_issues WHERE id = $id; ";
    $result = cm_run_query($query);
    $_SESSION['issue_data']['current_issue_id'] = $result->Fields('id');
    $_SESSION['issue_data']['current_issue_date'] = $result->Fields('issue_date');
    $_SESSION['issue_data']['current_issue_volume'] = $result->Fields('issue_volume');
    $_SESSION['issue_data']['current_issue_number'] = $result->Fields('issue_number');
    // Next Issue
    $id = cm_get_settings('next_issue');
    $query = "SELECT id, issue_date, issue_volume, issue_number FROM cm_issues WHERE id = $id; ";
    $result = cm_run_query($query);
    $_SESSION['issue_data']['next_issue_id'] = $result->Fields('id');
    $_SESSION['issue_data']['next_issue_date'] = $result->Fields('issue_date');
    $_SESSION['issue_data']['next_issue_volume'] = $result->Fields('issue_volume');
    $_SESSION['issue_data']['next_issue_number'] = $result->Fields('issue_number');
}

/**
 * Load user data
 *
 * Places user data for authenticated user into session variables
 */
function cm_users_data()
{    
    $id = $_SESSION['user_data']['id'];
    
    // Unset user data
    unset($_SESSION['user_data']);
    $query = "SELECT * FROM cm_users WHERE id = $id; ";
    $result = cm_run_query($query);
    
    $_SESSION['user_data']['id'] = $result->Fields('id');
    $_SESSION['user_data']['user_login'] = $result->Fields('user_login');
    $_SESSION['user_data']['user_first_name'] = $result->Fields('user_first_name');
    $_SESSION['user_data']['user_middle_name'] = $result->Fields('user_middle_name');
    $_SESSION['user_data']['user_last_name'] = $result->Fields('user_last_name');
    $_SESSION['user_data']['user_job_title'] = $result->Fields('user_job_title');
    $_SESSION['user_data']['user_email'] = $result->Fields('user_email');
}

/**
 * Load user access data
 *
 * Places user access data for authenticated user into session variables
 */
function cm_access_data()
{
    $id = $_SESSION['user_data']['id'];
    // Unset access data
    unset($_SESSION['access_data']);
    $query = "SELECT * FROM cm_access WHERE user_id = $id; ";
    $result = cm_run_query($query);
    $records = $result->GetArray();
    foreach ($records as $record)
    {
        $key = $record['access_option'];
        $value = $record['access_value'];
        $_SESSION['access_data'][$key] = $value;
    }
}


/**
 * Module authentication
 *
 * See if user has permission to access a specific module.
 *
 * @param    string  Module to authenticate
 * @return   boolean Returns true on allowed, errors out on false
 */
function cm_auth_module($module)
{
    if (!isset($_SESSION['access_data']))
    {
        $dest = $_SERVER['REQUEST_URI'];
        header("Location: login.php?dest=$dest");
        exit;
    }

    if ($_SESSION['access_data'][$module] == "true")
    {
        return true;
    } else {
        cm_error("You do not have permission to access this page.");
        exit;
    }
}

/**
 * Check permission
 *
 * Returns session value for requested module / auth
 *
 * @param    string  Module name
 * @return   string  Stored value
 */
function cm_auth_restrict($string)
{
    return $_SESSION['access_data'][$string];
}

/**
 * Logout
 *
 * Destroys set cookies and sessions and returns to login script
 *
 * @return   header  Sends user to login.php
 */
function cm_logout()
{
    $cookiesSet = array_keys($_COOKIE);
    for ($x=0;$x<count($cookiesSet);$x++) setcookie($cookiesSet[$x],"",time()-1);
    $_SESSION = array();
    if (isset($_COOKIE[session_name()]))
    {
       setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
    header("Location: login.php?msg=logout");
}

///// Working with the cm_access table /////

/**
 * Remove access
 *
 * Removes every entry from access table by username
 *
 * @param    string  Username
 * @return   boolean Returns true on success, false if no records deleted
 */
function cm_clear_access($sel)
{
    $query = "DELETE FROM cm_access WHERE user_id = $sel;";
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Add access
 *
 * Adds entries to access table for a user
 *
 * @param    string  Username
 * @param    string  Type of access entry ('module' or 'string')
 * @param    string  Option name
 * @param    string  Option Value
 * @return   mixed   Returns result resource
 */
function cm_add_access($sel,$type,$option,$value)
{
    if ($value == "") { $value = "false"; }    
    $query = "INSERT INTO cm_access (user_id,access_type,access_option,access_value) VALUES ($sel,'$type','$option','$value');";
    $stat = cm_run_query($query);
    return $stat;
}

#==========================================#
######### Building Lists and Menus #########
#==========================================#

/**
 * Section list
 *
 * Grabs list of sections and places them into <option> tags
 *
 * @todo     [todo item]
 * @param    string  Module name (determines formatting)
 * @param    int     ID of seleted section
 * @param    int     ID of section to exclude from list
 * @return   print   Outputs list of sections
 */
function cm_section_list($module, $sel=1, $exclude=0)
{
    $query = "SELECT * FROM cm_sections WHERE id != $exclude ORDER BY section_priority ASC;";    
    $result = cm_run_query($query);
    $records = $result->GetArray();

    foreach($records as $record)
    {
        $id = $record['id'];
        $section_name = htmlentities($record['section_name'], ENT_QUOTES, 'UTF-8');
        $section_url = htmlentities($record['section_url'], ENT_QUOTES, 'UTF-8');        
        
        switch($module)
        {
            case 'article-browse':
                    if ($sel == $id) { $select = "class=\"selected\""; } else { unset($select); }
                    print "\t<li><a href=\"article-browse.php?section=$id\" $select>$section_name</a></li>\n";
                break;
            case 'menu':
                    print "\t<li><a href=\"$section_url\">$section_name</a></li>\n";
                break;
            case 'section-edit':
                    if ($sel == $id) { $select = "selected"; } else { unset($select); }
                    print "\t<option value=\"$id\" $select>$section_name</option>\n";
                break;
            
            default:
                    if ($sel == $id) { $select = "selected"; } else { unset($select); }
                    print "\t<option value=\"$id\" $select>$section_name</option>\n";
                break;
        }        
    }
}

/**
 * Issue list
 *
 * Grabs list of issues and places them into <option> tags
 *
 * @param    string  Module name (determines formatting -- not used)
 * @param    int     ID of seleted issue
 * @return   print   Outputs list of issues
 */
function cm_issue_list($module, $sel)
{   
    $query = "SELECT * FROM cm_issues ORDER BY issue_date DESC;";    
    $result = cm_run_query($query);
    $records = $result->GetArray();

    foreach ($records as $record)
    {
        $id = $record['id'];
        $date = $record['issue_date'];
        $volume = $record['issue_volume'];
        $number = $record['issue_number'];
        if ($sel == $id) { $selected = " selected=\"selected\""; }
        print "\t<option value=\"$id\" $selected\">$date (Vol. $volume, No. $number)</option>\n";
        unset($selected);
    }
}

/**
 * Volume list
 *
 * Grabs list of volumes and places them into <option> tags
 *
 * @param    string  Module name (determines formatting -- not used)
 * @param    int     Number of seleted volume
 * @return   print   Outputs list of issues
 */
function cm_volume_list($module, $sel=1)
{
    
    $query = "SELECT DISTINCT(issue_volume) FROM cm_issues ORDER BY issue_volume DESC;";    
    $result = cm_run_query($query);
    $records = $result->GetArray();
    
    foreach ($records as $record)
    {
        $volume = $record['issue_volume'];
        if ($sel == $volume) { $selected = "selected=\"selected\""; }
        
        switch ($module)
        {
            default:   
                print "\t<option value=\"$volume\" $selected>Volume $volume</option>\n";
                unset($selected);
                break;
        }
    }
}

/**
 * List article media
 *
 * Grab related media files for an article
 *
 * @param    int     ID of article
 * @param    boolean Show media file preview
 * @return   print   Shows list or media previews
 */
function cm_list_media($sel,$display=false)
{
    $query = "SELECT * FROM cm_media WHERE article_id = '$sel';";    
    $result = cm_run_query($query);
    $records = $result->GetArray();

    if ($display == true) {
        
        echo "<p><strong>Linked Media:</strong></p>";
        if ($result->RecordCount() > 0) {
            foreach ($records as $record) {        
                $id = $record['id'];
                $title = $record['media_title'];
                $src = $recordy['media_src'];
                $type = $record['media_type'];
                print "<p>";
                cm_display_media($src,$type,$title);
                print "</p>\n";
                print "<p class=\"systemMessage\"><a href=\"article-media.php?id=$id\">Edit '$title' ($type)</a> | <a href=\"article-media.php?id=$id#delete\">Delete</a></p>\n";
            }
            print "<p><strong><a href=\"article-media.php?action=new&amp;article_id=$sel\">Add a file</a></strong></p>";
        } else {
            echo "<p><em>No linked files. <a href=\"article-media.php?action=new&amp;article_id=$sel\">Add a file</a></em></p>";
        }
    } else {    
        if ($result->RecordCount() > 0) {
            foreach ($records as $record) {        
                $id = $record['id'];
                $title = $record['media_title'];
                $type = $record['media_type'];
                print "<li><a href=\"article-media.php?id=$id\">#$id: $title</a> ($type)</li>\n";
            }
        } else {
            print "<li><em>No linked files.</em></li>";
        }
    }
}


/**
 * List poll questions
 *
 * Generates list of poll questions in <option> tags
 *
 * @param    int     Selected poll
 * @return   print   Prints list of poll questions
 */
function cm_poll_list($sel)
{

    $query = "SELECT id, poll_question FROM cm_poll_questions ORDER BY poll_created DESC, id DESC;";    
    $result = cm_run_query($query);
    $records = $result->GetArray();
    
    foreach ($records as $record)
    {
        $id = $record['id'];
        $question = trim_text($record['poll_question'], 50);
        if ($sel == $id) { $selected = "selected=\"selected\""; }    
        
        print "\t<option value=\"$id\" $selected>$question</option>\n";
        unset($selected);
    }
}


/**
 * Display media
 *
 * Generate media HTML based on type
 *
 * @param    string  Path to media
 * @param    string  Type of media
 * @param    string  Title of media
 * @return   print   Places HTML for media
 */
function cm_display_media($src,$type,$title='')
{
    if ($type == "jpg" || $type == "png" || $type == "gif") {
        print "<img src=\"$src\" alt=\"$type\" />";
    }
    if ($type == "pdf" || $type == "doc") {
        print "Related Document: <a href=\"$src\">Dowload '$title' ($type)</a>.";
    }
    if ($type == "wav" || $type == "mp3") {
        print "Media File: <a href=\"$src\">Play '$title' ($type)</a>.";
    }
    if ($type == "swf") {
        print "<object type=\"application/x-shockwave-flash\" data=\"$src\" width=\"300\" height=\"250\">\n";
        print "\t<param name=\"movie\" value=\"$src\" />\n";
        print "</object>\n";
    }
    if ($type == "url") {
        print "Related: <a href=\"$src\">Open related link</a>.";
    }
}

#==========================================#
###########  Returns Information ###########
#==========================================#

/**
 * Current issue
 *
 * Reads data from the 'issue_data' session variable
 *
 * @param    string  Desired data type
 * @return   string  Returns desired data
 */
function cm_current_issue($format)
{    
    switch ($format)
    {
        case 'date':    
            $value = $_SESSION['issue_data']['current_issue_date'];
            break;
        case 'volume':
            $value = $_SESSION['issue_data']['current_issue_volume'];
            break;
        case 'number':
            $value = $_SESSION['issue_data']['current_issue_number'];
            break;
        default:
            $value = $_SESSION['issue_data']['current_issue_id'];
            break;
    }
    return $value;
}

/**
 * Next issue
 *
 * Reads data from the 'issue_data' session variable
 *
 * @param    string  Desired data type
 * @return   string  Returns desired data
 */
function cm_next_issue($format)
{    
    switch ($format)
    {
        case 'date':    
            $value = $_SESSION['issue_data']['next_issue_date'];
            break;
        case 'volume':
            $value = $_SESSION['issue_data']['next_issue_volume'];
            break;
        case 'number':
            $value = $_SESSION['issue_data']['next_issue_number'];
            break;
        default:
            $value = $_SESSION['issue_data']['next_issue_id'];
            break;
    }
    return $value;
}

/**
 * Issue info
 *
 * Grabs data from issues table for selected issue
 *
 * @todo     Calls to this should probably return an array of info
 * @param    string  Column name
 * @param    int     Issue ID
 * @return   string  Returns database value
 */
function cm_issue_info($format, $sel)
{    
    $query = "SELECT $format AS myvalue";    
    $query .= " FROM cm_issues";
    $query .= " WHERE id = '$sel'";
    $result = cm_run_query($query);
    return $result->Fields('myvalue');
}

/**
 * Section info
 *
 * Grabs data from sections table
 *
 * @todo     Calls to this should probably return an array of info
 * @param    string  Column name
 * @param    int     Section ID
 * @return   string  Returns database value
 */
function cm_section_info($format, $sel)
{    
    $query = "SELECT $format AS myvalue";    
    $query .= " FROM cm_sections";
    $query .= " WHERE id = '$sel'";
    $result = cm_run_query($query);
    return $result->Fields('myvalue');
}

/**
 * Get settings
 *
 * Reads data from session variable for site setting
 *
 * @param    string  Setting name
 * @return   string  Session variable value
 */
function cm_get_settings($sel)
{
    // Read the session variable
    return $_SESSION['setting_data'][$sel];
}

#==========================================#
############# Managing Pages ############
#==========================================#

/**
 * Add page
 *
 * Adds a static info page
 *
 * @todo     Security
 * @param    array   Keyed array of page data
 * @return   mixed   Database result resource
 */
function cm_add_page($page)
{
    $title = $page['title'];
    $short_title = $page['short_title'];
    $text = $page['text'];
    $side_text = $page['side_text'];
    $slug = $page['slug'];
    $edited = date("Y-m-d h:i:s",time());
    
    $query = "INSERT INTO cm_pages (page_title,page_short_title,page_text,page_side_text,page_slug,page_edited)";
    $query .= " VALUES ('$title','$short_title','$text','$side_text','$slug','$edited')";
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Edit page
 *
 * Edits static info page
 *
 * @todo     Security
 * @param    array   Keyed array of page data
 * @param    int     ID of page to edit
 * @return   mixed   Database result resource
 */
function cm_edit_page($page, $id)
{
    $title = $page['title'];
    $short_title = $page['short_title'];
    $text = $page['text'];
    $side_text = $page['side_text'];
    $slug = $page['slug'];
    $edited = date("Y-m-d h:i:s",time());
    $query = "UPDATE cm_pages SET";
    $query .= " page_title = '$title',";
    $query .= " page_short_title = '$short_title',";
    $query .= " page_slug = '$slug',";
    $query .= " page_text = '$text',";
    $query .= " page_side_text = '$side_text',";
    $query .= " page_edited = '$edited' ";
    $query .= " WHERE id = $id; ";
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Delete page
 *
 * Delete page
 *
 * @todo     Security
 * @param    int     ID of page to delete
 * @return   mixed   Database result resource
 */
function cm_delete_page($sel)
{    
    $query = "DELETE FROM cm_pages WHERE id = $sel; ";
    $stat = cm_run_query($query);
    return $stat;
}

#==========================================#
############# Managing Articles ############
#==========================================#

/**
 * Add article
 *
 * Add an article to article database
 *
 * @todo     Security
 * @param    array   Article to add
 * @return   mixed   Database result resource
 */
function cm_add_article($article)
{
    $title = $article['title'];
    $subtitle = $article['subtitle'];
    $author = $article['author'];
    $author_title = $article['author_title'];
    $summary = $article['summary'];
    $text = $article['text'];
    $keywords = $article['keywords'];
    $priority = $article['priority'];
    $section = $article['section'];
    $issue = $article['issue'];
    $published = $article['published'];
    $edited = date("Y-m-d h:i:s",time());
    $word_count = count_words($text);
    
    $query = "INSERT INTO cm_articles (article_title,article_subtitle,article_author,article_author_title,article_summary,article_text,article_keywords,article_priority,section_id,issue_id,article_publish,article_edit,article_word_count)";
    $query .= " VALUES ('$title','$subtitle','$author','$author_title','$summary','$text','$keywords','$priority','$section','$issue','$published','$edited',$word_count); ";
    
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Edit article
 *
 * Edit an article to article database
 *
 * @todo     Security
 * @param    array   Article to edit
 * @param    int     ID of article
 * @return   mixed   Database result resource
 */
function cm_edit_article($article, $id)
{
    $title = $article['title'];
    $subtitle = $article['subtitle'];
    $author = $article['author'];
    $author_title = $article['author_title'];
    $summary = $article['summary'];
    $text = $article['text'];
    $keywords = $article['keywords'];
    $priority = $article['priority'];
    $section = $article['section'];
    $issue = $article['issue'];
    $published = $article['published'];
    $edited = date("Y-m-d h:i:s",time());
    $word_count = count_words($text);
    
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
    $query .= " WHERE id = $id; ";
    
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Delete article
 *
 * Delete article from article table
 *
 * @todo     Security
 * @param    int     ID of article
 * @return   mixed   Database result resource
 */
function cm_delete_article($sel)
{    
    // Clean up associated media
    $query = "DELETE FROM cm_media WHERE article_id = '$sel'; ";
    $stat = cm_run_query($query);
    
    // Delete the article
    $query = "DELETE FROM cm_articles WHERE id = '$sel'; ";
    $stat = cm_run_query($query);
    
    return $stat;
}

//// Media Module Portion of the System ////

/**
 * Add media
 *
 * Add media to an article
 *
 * @todo     Security
 * @param    array   Media to add
 * @return   mixed   Database result resource
 */
function cm_add_media($media)
{
    $article_id = $media['article_id'];
    $title = $media['title'];
    $src = $media['src'];
    $type = $media['type'];
    $caption = $media['caption'];
    $credit = $media['credit'];
    $query .= "INSERT INTO cm_media (article_id,media_title,media_src,media_type,media_caption,media_credit)";
    $query .= " VALUES ('$article_id','$title','$src','$type','$caption','$credit'); ";
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Edit media
 *
 * Edit article media
 *
 * @todo     Security
 * @param    array   Media to edit
 * @param    int     ID of media
 * @return   mixed   Database result resource
 */
function cm_edit_media($media,$id)
{
    $article_id = $media['article_id'];
    $title = $media['title'];
    $src = $media['src'];
    $type = $media['type'];
    $caption = $media['caption'];
    $credit = $media['credit'];
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
}

/**
 * Delete media
 *
 * Delete article media from media table
 *
 * @todo     Security
 * @param    int     ID of media
 * @return   mixed   Database result resource
 */
function cm_delete_media($sel)
{    
    $query = "DELETE FROM cm_media WHERE id = $sel; ";
    $stat = cm_run_query($query);
    return $stat;
}

#==========================================#
#######  Managing Submitted Articles #######
#==========================================#

/**
 * Delete submitted article
 *
 * Removes submitted article from table
 *
 * @param    int     ID of submitted article
 * @return   mixed   Database result resource
 */
function cm_delete_submitted($sel)
{    
    $query = "DELETE FROM cm_submitted WHERE id = $sel;";
    $stat = cm_run_query($query);
    return $stat;
}

#==========================================#
#############  Managing Issues #############
#==========================================#

/**
 * Add issue
 *
 * Add new issue to the table
 *
 * @todo     Security
 * @param    array   Issue data
 * @return   mixed   Database result resource
 */
function cm_add_issue($issue)
{
    $date = $issue['date'];
    $volume = $issue['volume'];
    $number = $issue['number'];
    $circulation = $issue['circulation'];
    $online_only = $issue['online_only'];
    
    $query = "INSERT INTO cm_issues (issue_date,issue_volume,issue_number,issue_circulation,online_only)";
    $query .= " VALUES ('$date','$volume','$number','$circulation','$online_only'); ";
    
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Edit issue
 *
 * Edit issue data
 *
 * @todo     Security
 * @param    array   Issue data
 * @param    int     ID of issue
 * @return   mixed   Database result resource
 */
function cm_edit_issue($issue,$id)
{
    $date = $issue['date'];
    $volume = $issue['volume'];
    $number = $issue['number'];
    $circulation = $issue['circulation'];
    $online_only = $issue['online_only'];
    $query = "UPDATE cm_issues SET";
    $query .= " issue_date = '$date',";
    $query .= " issue_volume = '$volume',";
    $query .= " issue_number = '$number',";
    $query .= " issue_circulation = '$circulation',";
    $query .= " online_only = '$online_only'";
    $query .= " WHERE id = $id; ";
    
    $stat = cm_run_query($query);
    cm_issues_data();
    return $stat;
}

#==========================================#
############  Managing Sections ############
#==========================================#

/**
 * Add section
 *
 * Add a section to the section table
 *
 * @todo     Security
 * @param    array   Section data
 * @return   mixed   Database result resource
 */
function cm_add_section($section)
{
    $name = $section['name'];
    $editor = $section['editor'];
    $editor_title = $section['editor_title'];
    $editor_email = $section['editor_email'];
    $url = $section['url'];
    $sidebar = $section['sidebar'];
    $feed_image = $section['feed_image'];
    $priority = $section['priority'];
    
    $query = "INSERT INTO cm_sections (section_name,section_editor,section_editor_title,section_editor_email,section_url,section_sidebar,section_feed_image,section_priority)";
    $query .= " VALUES ('$name','$editor','$editor_title','$editor_email','$url','$sidebar','$feed_image','$priority');";

    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Edit section
 *
 * Edit a section in the section table
 *
 * @todo     Security
 * @param    array   Section data
 * @param    int     ID of section
 * @return   mixed   Database result resource
 */
function cm_edit_section($section,$id)
{
    $name = $section['name'];
    $editor = $section['editor'];
    $editor_title = $section['editor_title'];
    $editor_email = $section['editor_email'];
    $url = $section['url'];
    $sidebar = $section['sidebar'];
    $feed_image = $section['feed_image'];
    $priority = $section['priority'];
    $query = "UPDATE cm_sections SET";
    $query .= " section_name = '$name',";
    $query .= " section_editor = '$editor',";
    $query .= " section_editor_title = '$editor_title',";
    $query .= " section_editor_email = '$editor_email',";
    $query .= " section_url = '$url',";
    $query .= " section_sidebar = '$sidebar',";
    $query .= " section_feed_image = '$feed_image',";
    $query .= " section_priority = '$priority'";
    $query .= " WHERE id = $id";
    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Delete section
 *
 * Delete a section and move its articles to a different section
 *
 * @todo     Security
 * @param    int     ID of origin section 
 * @param    int     ID of destination section
 * @return   mixed   Database result resource
 */
function cm_delete_section($sel,$move)
{    
    $stat = cm_move_articles($sel,$move,'section');
    
    $query = "DELETE FROM cm_sections WHERE id = $sel;";
    
    $stat = cm_run_query($query);
    return $stat;    
}

/**
 * Move articles
 *
 * Moves articles from one section to another
 *
 * @todo     Security
 * @param    int     ID of origin section 
 * @param    int     ID of destination section
 * @return   mixed   Database result resource
 */
function cm_move_articles($sel,$move)
{
    // Prevent deleting last section
    if (!is_numeric($move))
    {
        cm_error("Section does not exist. Hint: You cannot delete the last section.");
        exit;
    }
    
    // Reset browse cookie to destination section
    setcookie("article-browse-section", $move);
    
    $query = "UPDATE cm_articles SET section_id = $move WHERE section_id = $sel; ";
    
    $stat = cm_run_query($query);
    return $stat;    
}

#==========================================#
############## Managing Users ##############
#==========================================#

/**
 * Add user
 *
 * Create new user
 *
 * @param    array   User information
 * @return   mixed   Database result resource
 */
function cm_add_user($user)
{
    $login = $user['login'];
    $password = $user['password'];
    $first_name = $user['first_name'];
    $middle_name = $user['middle_name'];
    $last_name = $user['last_name'];
    $job_title = $user['job_title'];
    $email = $user['email'];
    $telephone = $user['telephone'];
    $mobile = $user['mobile'];
    $address = $user['address'];
    $city = $user['city'];
    $state = $user['state'];
    $zipcode = $user['zipcode'];
    $im_aol = $user['im_aol'];
    $im_msn = $user['im_msn'];
    $im_yahoo = $user['im_yahoo'];
    $im_jabber = $user['im_jabber'];
    $profile = $user['profile'];

    $query = "INSERT INTO cm_users (user_login,user_password,user_first_name,user_middle_name,user_last_name,user_job_title,user_email,user_telephone,user_mobile,user_address,user_city,user_state,user_zipcode,user_im_aol,user_im_msn,user_im_yahoo,user_im_jabber,user_profile)";
    $query .= " VALUES ('$login','$password','$first_name','$middle_name','$last_name','$job_title','$email','$telephone','$mobile','$address','$city','$state','$zipcode','$im_aol','$im_msn','$im_yahoo','$im_jabber','$profile');";

    $stat = cm_run_query($query);        
    return $stat;
}

/**
 * Edit User
 *
 * Update existing user data
 *
 * @param    array   User information
 * @param    int     ID of user
 * @return   mixed   Database result resource
 */
function cm_edit_user($user,$id)
{
    $login = $user['login'];
    $password = $user['password'];
    $first_name = $user['first_name'];
    $middle_name = $user['middle_name'];
    $last_name = $user['last_name'];
    $job_title = $user['job_title'];
    $email = $user['email'];
    $telephone = $user['telephone'];
    $mobile = $user['mobile'];
    $address = $user['address'];
    $city = $user['city'];
    $state = $user['state'];
    $zipcode = $user['zipcode'];
    $im_aol = $user['im_aol'];
    $im_msn = $user['im_msn'];
    $im_yahoo = $user['im_yahoo'];
    $im_jabber = $user['im_jabber'];
    $profile = $user['profile'];

    $query = "UPDATE cm_users SET";
    $query .= " user_login = '$login',";
    $query .= " user_password = '$password',";
    $query .= " user_first_name = '$first_name',";
    $query .= " user_middle_name = '$middle_name',";
    $query .= " user_last_name = '$last_name',";
    $query .= " user_job_title = '$job_title',";
    $query .= " user_email = '$email',";
    $query .= " user_telephone = '$telephone',";
    $query .= " user_mobile = '$mobile',";
    $query .= " user_address = '$address',";
    $query .= " user_city = '$city',";
    $query .= " user_state = '$state',";
    $query .= " user_zipcode = '$zipcode',";
    $query .= " user_im_aol = '$im_aol',";
    $query .= " user_im_msn = '$im_msn',";
    $query .= " user_im_yahoo = '$im_yahoo',";
    $query .= " user_im_jabber = '$im_jabber',";
    $query .= " user_profile = '$profile'";
    $query .= " WHERE id = $id; ";

    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Delete user
 *
 * Remove user from table, clear access
 *
 * @param    int     ID of user
 * @return   mixed   Database result resource
 */
function cm_delete_user($sel)
{        // Lock out user
    $stat = cm_clear_access($sel);
    $query = "DELETE FROM cm_users WHERE id = $sel; ";
    $stat = cm_run_query($query);
    return $stat;    
}
#==========================================#
##########  Managing User Profiles #########
#==========================================#

/**
 * Edit profile
 *
 * Update current user profile
 *
 * @param    array   User data
 * @return   mixed   Database result resource
 */
function cm_edit_profile($user)
{
    $password = $user['password'];
    $email = $user['email'];
    $telephone = $user['telephone'];
    $mobile = $user['mobile'];
    $address = $user['address'];
    $city = $user['city'];
    $state = $user['state'];
    $zipcode = $user['zipcode'];
    $im_aol = $user['im_aol'];
    $im_msn = $user['im_msn'];
    $im_yahoo = $user['im_yahoo'];
    $im_jabber = $user['im_jabber'];
    $profile = $user['profile'];
    $id = $_SESSION['user_data']['id']; // Locked.
    $query = "UPDATE cm_users SET";
    $query .= " user_password = '$password',";
    $query .= " user_email = '$email',";
    $query .= " user_telephone = '$telephone',";
    $query .= " user_mobile = '$mobile',";
    $query .= " user_address = '$address',";
    $query .= " user_city = '$city',";
    $query .= " user_state = '$state',";
    $query .= " user_zipcode = '$zipcode',";
    $query .= " user_im_aol = '$im_aol',";
    $query .= " user_im_msn = '$im_msn',";
    $query .= " user_im_yahoo = '$im_yahoo',";
    $query .= " user_im_jabber = '$im_jabber',";
    $query .= " user_profile = '$profile'";
    $query .= " WHERE id = $id; ";
    $stat = cm_run_query($query);
    
    return $stat;
}

#==========================================#
########## Managing Poll Questions #########
#==========================================#

/**
 * Add poll
 *
 * Add new poll question
 *
 * @param    array   Poll information
 * @return   mixed   Database result resource
 */
function cm_add_poll($poll)
{
    $question = $poll['question'];
    $r1 = $poll['r1'];
    $r2 = $poll['r2'];
    $r3 = $poll['r3'];
    $r4 = $poll['r4'];
    $r5 = $poll['r5'];
    $r6 = $poll['r6'];
    $r7 = $poll['r7'];
    $r8 = $poll['r8'];
    $r9 = $poll['r9'];
    $r10 = $poll['r10'];
    $article_id = $poll['article_id'];
    $created = date("Y-m-d h:i:s", time());

    $query = "INSERT INTO cm_poll_questions ";
    $query .= " (poll_created, poll_question, poll_response_1, poll_response_2, poll_response_3, poll_response_4, poll_response_5, poll_response_6, poll_response_7, poll_response_8, poll_response_9, poll_response_10, article_id)";
    $query .= "  VALUES ('$created', '$question', '$r1', '$r2', '$r3', '$r4', '$r5', '$r6', '$r7', '$r8', '$r9', '$r10', '$article_id');";

    $stat = cm_run_query($query);
    return $stat;
}

/**
 * Edit poll
 *
 * Edit existing poll question
 *
 * @param    array   Poll information
 * @param    int     ID of poll
 * @return   mixed   Datbase result resource
 */
function cm_edit_poll($poll,$id)
{
    $question = $poll['question'];
    $r1 = $poll['r1'];
    $r2 = $poll['r2'];
    $r3 = $poll['r3'];
    $r4 = $poll['r4'];
    $r5 = $poll['r5'];
    $r6 = $poll['r6'];
    $r7 = $poll['r7'];
    $r8 = $poll['r8'];
    $r9 = $poll['r9'];
    $r10 = $poll['r10'];
    $article_id = $poll['article_id'];

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
    $query .= " WHERE id = '$id'; ";

    $stat = cm_run_query($query);
    return $stat;
}
/**
 * Delete poll
 *
 * Remove poll from database and delete ballots
 *
 * @param    int     ID of poll
 * @return   mixed   Datbase result reseource
 */
function cm_delete_poll($sel)
{    
    // Disable polls if current poll
    $query = "UPDATE cm_settings SET active_poll = 0 WHERE active_poll = $sel; ";
    $stat = cm_run_query($query);
    
    // Delete ballots
    $query = "DELETE FROM cm_poll_ballot WHERE poll_id = $sel; ";
    $stat = cm_run_query($query);
    
    // Remove question
    $query = "DELETE FROM cm_poll_questions WHERE id = $sel; ";
    $stat = cm_run_query($query);
    return $stat;    
}

/**
 * Poll results
 *
 * Display list of out
 *
 * @param    int     ID of poll
 * @return   print   Prints list of poll results
 */
function cm_poll_results($sel)
{

    $query = "SELECT COUNT(id) AS total";
    $query .= " FROM cm_poll_ballot";
    $query .= " WHERE poll_id = '$sel'";
    
    $result = cm_run_query($query);
    
    $total = $result->Fields('total');
    
    $query = "SELECT ballot_response AS response, COUNT(ballot_response) AS votes, COUNT(id) AS total";
    $query .= " FROM cm_poll_ballot";
    $query .= " WHERE poll_id = '$sel'";
    $query .= " GROUP BY ballot_response;";
    
    $result = cm_run_query($query);
    $records = $result->GetArray();

    if ($total > 0)
    {
        print "<ul>";
        foreach ($records as $record)
        {
            $response = $record['response'];
            $votes = $record['votes'];
            $percent = (100 * ($votes / $total));
            $percent = number_format($percent, 2, '.', '');
            print "<li><strong>Option $response:</strong> $votes votes ($percent%)</li>\n";    
        }
        print "</ul>";
        print "<p><strong>Total votes:</strong> $total</p>";
    } else {
        print "<p>No one has voted yet.</p>";
    }
}

/**
 * Poll cleanup
 *
 * Remove over-votes by IP address
 *
 * @todo     Very elementary way of detecting overvotes
 * @param    int     ID of poll
 * @return   print   Prints result of cleanup
 */
function cm_poll_cleanup($sel)
{
    $query = "SELECT ballot_ip_address, COUNT(ballot_ip_address) AS ballots ";
    $query .= " FROM cm_poll_ballot ";
    $query .= " WHERE poll_id = $sel ";
    $query .= " GROUP BY ballot_ip_address ";
    $query .= " HAVING ballots > 1; ";
    $result = cm_run_query($query);
    $records = $result->GetArray();
    
    if ($result->RecordCount() > 0)
    {
        foreach ($records as $record)
        {
            $ipad = $record['ballot_ip_address'];
            cm_poll_delete_ballots($sel,$ipad);
        }
        print "This poll had $result_row_count multiple voter(s).<br /> All have been cleared.";
    } else {
        print "Poll audit successful.<br /> No multiple-voters detected.";
    }
}

/**
 * Delete poll ballots
 *
 * Removes ballots by IP address for selected poll
 *
 * @param    int     ID of poll
 * @param    string  IP address to match
 * @return   mixed   Database result resource
 */
function cm_poll_delete_ballots($sel,$ipad)
{
    $query = "DELETE FROM cm_poll_ballot";
    $query .= " WHERE ballot_ip_address = '$ipad'";
    $query .= " AND poll_id = '$sel'";
    $stat = cm_run_query($query);
    return $stat;    
}

#==========================================#
##############  Site Settings ##############
#==========================================#

/**
 * Edit publish settings
 *
 * Set current and next issue
 *
 * @param    int     ID of current issue
 * @param    int     ID of next issue
 * @param    int     ID of selected setting; default 1
 * @return   mixed   Database result resource
 */
function cm_edit_publish_settings($current_issue,$next_issue,$id=1)
{
    $query = "UPDATE cm_settings SET current_issue = $current_issue, next_issue = $next_issue WHERE id = $id";
    $stat = cm_run_query($query);
    
    // Reset session variables
    cm_settings_data();
    cm_issues_data();
    
    return $stat;
}

/**
 * Poll settings
 *
 * Set active poll
 *
 * @param    int     ID of poll
 * @return   mixed   Database result resource
 * @param    int     ID of selected setting; default 1
 */
function cm_edit_poll_settings($active_poll,$id=1)
{
    if (!is_numeric($active_poll)) {
        $active_poll = 0;
    }
    $query = "UPDATE cm_settings SET active_poll = $active_poll WHERE id = $id";
    $stat = cm_run_query($query);
    
    // Reset session variables
    cm_settings_data();
    
    return $stat;
}

/**
 * Edit settings
 *
 * Update site settings
 *
 * @param    array   Setting data
 * @return   mixed   Database result resource
 * @param    int     ID of selected setting; default 1
 */
function cm_edit_settings($settings,$id=1)
{
    $name = $settings['name'];
    $description = $settings['description'];
    $url = $settings['url'];
    $email = $settings['email'];
    $address = $settings['address'];
    $city = $settings['city'];
    $state = $settings['state'];
    $zipcode = $settings['zipcode'];
    $telephone = $settings['telephone'];
    $fax = $settings['fax'];
    $announce = $settings['announce'];
    
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
    
    // Reset session variables
    cm_settings_data();    
    
    return $stat;
}

#==========================================#
###########  Universal Functions ###########
#==========================================#

/**
 * Execute query
 *
 * Handles all database queries
 *
 * @param    string  Query
 * @return   mixed   Database result resource
 */
function cm_run_query($query)
{
    global $cm_db;

    $result = $cm_db->Execute($query) or die(cm_error($cm_db->ErrorMsg()));
    return $result;
}

/**
 * AutoP
 *
 * Clever way to turn text into XHTML
 *
 * @author   Matthew Mullenweg
 * @link     http://photomatt.net/scripts/autop/
 * @param    string  Text to format
 * @param    boolean Flag to make line break; default 1
 * @return   string  Formatted text
 */
function autop($pee, $br = 1) {
    $pee = $pee . "\n"; // just to make things a little easier, pad the end
    $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
    // Space things out a little
    $allblocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
    $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
    $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
    $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
    $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
    $pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end
    $pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
    $pee = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $pee);
    $pee = preg_replace( '|<p>|', "$1<p>", $pee );
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
    $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
    if ($br) {
        $pee = preg_replace('/<(script|style).*?<\/\\1>/se', 'str_replace("\n", "<WPPreserveNewline />", "\\0")', $pee);
        $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
        $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
    }
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
    if (strpos($pee, '<pre') !== false)
        $pee = preg_replace_callback('!(<pre.*?>)(.*?)</pre>!is', 'clean_pre', $pee );
    $pee = preg_replace( "|\n</p>$|", '</p>', $pee );
    
    return $pee;
}

/**
 * Count words
 *
 * Counds the total words in a string
 *
 * @param    string  Text to count
 * @return   int     Word count
 */
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

/**
 * Trim text
 *
 * Take a long piece of text and trim to a length
 *
 * @param    string  Text to trim
 * @param    int     Length of string
 * @return   string  Trimmed text
 */
function trim_text($text,$length)
{
    if (strlen($text) > $length) {
        $text = $text." ";
        $text = substr($text,0,$length);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
    }
    return $text;
}

/**
 * Prepare string
 *
 * Magic Quotes-aware method of preparing a string for database entry
 *
 * @param    string  Text to prepare
 * @return   string  Prepared text
 */
function prep_string($string){    if (get_magic_quotes_gpc() == 1)    {        return($string);    } else {        return(addslashes($string));    }}