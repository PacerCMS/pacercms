<?php

$is_upgrade_page = true;

// Loads everyting needed to run PacerCMS
include('cm-includes/cm-header.php');

// Require settings authorization for upgrade
cm_auth_module('settings');

/*
 * Upgrade PRE (February 2008)
 */
function upgrade_pre() {
    // Add Site Description field
    cm_run_query("ALTER TABLE cm_settings ADD site_description TEXT NOT NULL AFTER site_announcement; ");
    // Add Database Version field
    cm_run_query("ALTER TABLE cm_settings ADD database_version VARCHAR(5) NOT NULL AFTER active_poll; ");
    // Add Setion Sidebar field
    cm_run_query("ALTER TABLE cm_sections ADD section_feed_image VARCHAR(225) NOT NULL AFTER section_sidebar; ");
    // Set DB version to intial value
    cm_run_query("UPDATE cm_settings SET database_version = '0'; ");
    // Relaunch Script
    header("Location: upgrade.php?step=2");
    exit;
}

/*
 * Upgrade #34 (December 2006)
 *
 */
function upgrade_34()
{
    // Add Setion Feed Image field
    cm_run_query("ALTER TABLE cm_sections ADD section_feed_image VARCHAR(225) NOT NULL AFTER section_sidebar; ");
    // Set DB version to 34
    cm_run_query("UPDATE cm_settings SET database_version = '34'; ");
}

/*
 * Upgrade #65 (February 2007)
 */
function upgrade_65() {
    // Set DB version to 65
    cm_run_query("UPDATE cm_settings SET database_version = '65'; ");
}

// This is a two step process.
switch($_REQUEST['step'])
{
    case 2:
        // First, check if database version matches configured version
        $query = "SELECT database_version FROM cm_settings LIMIT 1";
        $result = $cm_db->Execute($query) or die(upgrade_pre());
        $db_version = $result->Fields('database_version');
        
        // Run these checks for each current version
        if ($db_version == DB_VERSION ) { cm_error('Your database is already up to date.'); exit; }
        if ($db_version < 34) { upgrade_34(); $db_version = 34; }
        if ($db_version < 65) { upgrade_65(); $db_version = 65; }
        
        // Set new version in settings variable
        $_SESSION['setting_data']['database_version'] = $db_version;

        // Show complete message
        get_cm_header();
        print "<h2>Upgrade complete!</h2>\n";
        print "<p>Your database is up to date with version #$db_version.</p>";
        get_cm_footer();       
        break;

    default:
        // Show start of upgrade
        get_cm_header();
        print "<h2>Your database needs to be upgraded</h2>\n";
        print "<p>This should only take a few moments to complete.</p>\n";
        print "<h4><a href=\"?step=2\">Begin Upgrade</a></h4>\n";
        get_cm_footer();       
        break;
}
