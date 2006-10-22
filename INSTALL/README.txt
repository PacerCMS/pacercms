
*** PacerCMS ***

Status: Not suitbable for general public
Reason: Packaged to fish for other developers

== Helpless Instructions ==

This file contains two folders, 'PacerCMS' and 'Database'. Place the 'PacerCMS' folder on a PHP/MySQL-enabled Web server. Import the database dump into MySQL as per your prefered method. Depending on your system, work with the 'cm_settings' table to reflect the paths to your testing server. Next, open remove the "-sample" from these files ...

./includes/config-sample.php
./siteadmin/includes/config-sample.php

to

./includes/config.php
./siteadmin/includes/config.php


... edit them to reflect your database logins and file paths. You will also want to open up the ./siteadmin/ folder in a Web browser and head to 'settings' to make sure your site links properly and not to my testing server. Your username and password are "admin." This should be the first thing you change, even on a testing account.

== Further Helpless Help ==
I can be reached at stephen.yeargin@gmail.com. I will be happy to help with coding level problems, but cannot do much for you in regards to design or configuration. I can explain what I was thinking when I developed a particular aspect of the system, but most were completed with a specific publication in mind. Your mileage may vary.

*** E-mail that says "I can't get my site to work" will likely be ignored. Help me help you ***

== Upgrade Fix ==
If you are upgrading, please find the proper database query to run in the 'Database' folder.


=== RELEASE OF CODE ===

This application is released under the GNU General Public License (Version 2, June 1991). For a copy of this rather long and boring legal document, please visit http://www.gnu.org/licenses/gpl.txt


[/END]