/* ===========================================

  PacerCMS
    Content management solution for student
    and non-daily community newspapers

=========================================== */

== Installation Instructions ==

=== Requirements ===
 * Apache or compliant Web server
 * PHP 4.2 or greater * MySQL 3.23.23 or greater

=== In the INSTALL directory ===
 * This README
 * A Database folder containing
  # The default database dump file
  # Upgrade alterations if you are using an older version of the software

=== Getting Started ===

Note: This section will likely change as we begin work on a true installer. With a bit of experience with setting up a Web application, you should be able to navigate these instructions with relative ease. If you have any questions, just drop us a line in the PacerCMS Developers Group ( http://groups.google.com/group/pacercms-discuss )

==== Database ====
 # Using a tool such as phpMyAdmin, create a database for your tables to reside within.
 # Import the default data found in the ''Database'' folder into your new database.
 # Verify that your tables, all preceded by a ''cm'' were created.
 # Assign read and write permissions to the user that will be accessing the database.
  * Note: We recommend creating a separate user account that only has permissions on this one database for security reasons.

==== PHP Scripts ====
 # Upload the entire contents of the package to your server root, typically an ''htdocs'', ''www'' or ''public'' folder. 
 # If you have access to the command line, change the file permissions for the cache directory
  * chmod -fR 775 ./cache
 # Copy the ''config-sample.php'' file in the ''./includes'' directory to ''config.php''
  * cp ./includes/config-sample.php ./includes/config.php
 # Modify the ''config.php'' file in the ''./includes'' directory to match your database settings.
 # If you are preparing for use on a production server, copy the contents of the ''./templates/default'' directory into the ''./templates/local'' directory. Change the ''TEMPLATE_FOLDER'' declaration in your ''config.php'' file to ''local'' to use the local repository of files.
 # Repeat the process for renaming and modifying the ''config.php'' file in the ''./siteadmin/cm-includes'' directory.

==== Site Settings ====
 # Login to the Site Administration section by opening ''http://www.yourdomain.com/siteadmin''
 # By default, the site uses ''admin'' as both the username and password. Change this immediately.
 # Open the ''Settings'' module from the left sidebar to update the URL for your site and add configuration information.
 # Repeat the URL changes for each section of the Web site in the ''Sections'' module.
 # Open the ''Users'' module to change the password or add contact information for the site administrator.
 # Use the ''Users'' module to create login accounts for each contributor the site, using the ''Access'' module to control how they interact with the Web site.

==== Template Design ====
PacerCMS uses ''The Pacer'' as the default theme. If you have copied the contents of the ''./templates/default'' folder to ''./templates/local'' and made the necessary adjustments in the ''./includes/config.php'' file, you can begin editing the files to match your own desired templates.

Please see our Template Guides at http://code.google.com/p/pacercms/w/list


== Copyright and Indemnification Notice ==

PacerCMS - Content management solution for student and non-daily community newspapers
Copyright (C) 2003-2007  PacerCMS Development Group (http://pacercms.sourceforge.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.


