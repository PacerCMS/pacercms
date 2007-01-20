-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 30, 2006 at 05:52 PM
-- Server version: 5.0.18
-- PHP Version: 5.1.2
-- 
-- Database: `pacercms`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `cm_access`
-- 

CREATE TABLE `cm_access` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `access_type` set('module','string') NOT NULL default '',
  `access_option` varchar(100) NOT NULL default '',
  `access_value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Grants users access to each module';

-- 
-- Dumping data for table `cm_access`
-- 

INSERT INTO `cm_access` VALUES (1, 1, 'string', 'restrict_issue', 'false');
INSERT INTO `cm_access` VALUES (2, 1, 'string', 'restrict_section', 'false');
INSERT INTO `cm_access` VALUES (3, 1, 'module', 'poll-edit', 'true');
INSERT INTO `cm_access` VALUES (4, 1, 'module', 'poll-browse', 'true');
INSERT INTO `cm_access` VALUES (5, 1, 'module', 'submitted-browse', 'true');
INSERT INTO `cm_access` VALUES (6, 1, 'module', 'submitted-edit', 'true');
INSERT INTO `cm_access` VALUES (7, 1, 'module', 'submitted-delete', 'true');
INSERT INTO `cm_access` VALUES (8, 1, 'module', 'staff-edit', 'true');
INSERT INTO `cm_access` VALUES (9, 1, 'module', 'staff-browse', 'true');
INSERT INTO `cm_access` VALUES (10, 1, 'module', 'staff-access', 'true');
INSERT INTO `cm_access` VALUES (11, 1, 'module', 'page-edit', 'true');
INSERT INTO `cm_access` VALUES (12, 1, 'module', 'page-browse', 'true');
INSERT INTO `cm_access` VALUES (13, 1, 'module', 'settings', 'true');
INSERT INTO `cm_access` VALUES (14, 1, 'module', 'server-info', 'true');
INSERT INTO `cm_access` VALUES (15, 1, 'module', 'section-edit', 'true');
INSERT INTO `cm_access` VALUES (16, 1, 'module', 'section-browse', 'true');
INSERT INTO `cm_access` VALUES (17, 1, 'module', 'profile', 'true');
INSERT INTO `cm_access` VALUES (18, 1, 'module', 'issue-edit', 'true');
INSERT INTO `cm_access` VALUES (19, 1, 'module', 'issue-browse', 'true');
INSERT INTO `cm_access` VALUES (20, 1, 'module', 'index', 'true');
INSERT INTO `cm_access` VALUES (21, 1, 'module', 'article-media', 'true');
INSERT INTO `cm_access` VALUES (22, 1, 'module', 'article-edit', 'true');
INSERT INTO `cm_access` VALUES (23, 1, 'module', 'article-browse', 'true');
-- --------------------------------------------------------

-- 
-- Table structure for table `cm_articles`
-- 

CREATE TABLE `cm_articles` (
  `id` int(11) NOT NULL auto_increment,
  `issue_id` int(5) NOT NULL default '0',
  `section_id` int(5) NOT NULL default '0',
  `article_author` varchar(40) default NULL,
  `article_author_title` varchar(40) default NULL,
  `article_title` varchar(200) default NULL,
  `article_subtitle` varchar(200) default NULL,
  `article_summary` text,
  `article_text` text,
  `article_priority` int(2) default '0',
  `article_keywords` tinytext,
  `article_publish` datetime NOT NULL default '0000-00-00 00:00:00',
  `article_edit` datetime NOT NULL default '0000-00-00 00:00:00',
  `article_word_count` int(7) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `article_author` (`article_author`),
  FULLTEXT KEY `article_keywords` (`article_keywords`),
  FULLTEXT KEY `article_text` (`article_text`,`article_title`,`article_subtitle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Principle article database';

-- 
-- Dumping data for table `cm_articles`
-- 

INSERT INTO `cm_articles` VALUES (1, 1, 1, 'PacerCMS Team', 'Developers', 'Welcome to PacerCMS!', '', 'If you are seeing this message, it means your site is up and running. Congratulations! Now, simply log into the administration panel to get started.', 'If you are seeing this message, it means your site is up and running. Congratulations! Now, simply log into the administration panel to get started.\r\n\r\n<strong><a href="siteadmin/">Site Administrator</a> - Bookmark this link</strong>', 1, 'welcome, pacercms', now(), now(), 30);


-- --------------------------------------------------------

-- 
-- Table structure for table `cm_issues`
-- 

CREATE TABLE `cm_issues` (
  `id` int(11) NOT NULL auto_increment,
  `issue_date` date NOT NULL default '0000-00-00',
  `issue_number` int(5) NOT NULL default '0',
  `issue_volume` int(5) NOT NULL default '0',
  `issue_circulation` int(5) NOT NULL default '0',
  `online_only` set('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Lists each issue with volume information';

-- 
-- Dumping data for table `cm_issues`
-- 

INSERT INTO `cm_issues` VALUES (1, now(), 1, 1, 3000, '0');
INSERT INTO `cm_issues` VALUES (2, DATE_ADD(now(), INTERVAL 7 DAY), 2, 1, 3000, '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `cm_media`
-- 

CREATE TABLE `cm_media` (
  `id` int(11) NOT NULL auto_increment,
  `article_id` int(11) NOT NULL default '0',
  `media_title` varchar(255) NOT NULL default '[Image]',
  `media_src` varchar(100) NOT NULL default '',
  `media_type` set('jpg','gif','png','pdf','doc','mp3','wav','swf','url') NOT NULL default 'jpg',
  `media_caption` tinytext NOT NULL,
  `media_credit` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Media attachments for articles';

-- --------------------------------------------------------

-- 
-- Dumping data for table `cm_media`
-- 

INSERT INTO `cm_media` VALUES (1, 1, 'PacerCMS', 'http://pacercms.sourceforge.net/wp-content/uploads/2006/10/PacerCMS_300x213.png', 'png', 'Welcome to PacerCMS!', 'PacerCMS Development Team');INSERT INTO `cm_media` VALUES (2, 1, 'PacerCMS Official Web Site', 'http://pacercms.sourceforge.net/', 'url', 'This would be a good site to bookmark.', 'PacerCMS Development Team');

-- 
-- Table structure for table `cm_pages`
-- 

CREATE TABLE `cm_pages` (
  `id` int(11) NOT NULL auto_increment,
  `page_title` varchar(255) NOT NULL default '',
  `page_short_title` varchar(50) NOT NULL default '',
  `page_text` text NOT NULL,
  `page_side_text` text NOT NULL,
  `page_edited` datetime NOT NULL default '0000-00-00 00:00:00',
  `page_slug` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Static pages of information';

-- 
-- Dumping data for table `cm_pages`
-- 

INSERT INTO `cm_pages` VALUES (1, 'About Our Newspaper', 'About Us', 'This is where you would put a short blurb about your history or editorial policies', '<h4>Our Staff</h4>\r\n\r\nStaff list', now(), 'about-us');
INSERT INTO `cm_pages` VALUES (2, 'Advertising Rates', 'Advertising', 'This is where you would post your rate card and advertising policies', '<h4>Contact Us</h4>\r\n\r\nAddress and phone numbers', now(), 'advertise');

-- --------------------------------------------------------

-- 
-- Table structure for table `cm_poll_ballot`
-- 

CREATE TABLE `cm_poll_ballot` (
  `id` int(11) NOT NULL auto_increment,
  `poll_id` int(11) NOT NULL default '0',
  `ballot_response` int(2) NOT NULL default '0',
  `ballot_ip_address` varchar(20) NOT NULL default '',
  `ballot_hostname` varchar(225) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains each ballot cast for the poll questions';


-- --------------------------------------------------------

-- 
-- Table structure for table `cm_poll_questions`
-- 

CREATE TABLE `cm_poll_questions` (
  `id` int(11) NOT NULL auto_increment,
  `poll_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `poll_question` varchar(200) NOT NULL default '',
  `poll_response_1` varchar(150) NOT NULL default '',
  `poll_response_2` varchar(150) NOT NULL default '',
  `poll_response_3` varchar(150) NOT NULL default '',
  `poll_response_4` varchar(150) NOT NULL default '',
  `poll_response_5` varchar(150) NOT NULL default '',
  `poll_response_6` varchar(150) NOT NULL default '',
  `poll_response_7` varchar(150) NOT NULL default '',
  `poll_response_8` varchar(150) NOT NULL default '',
  `poll_response_9` varchar(150) NOT NULL default '',
  `poll_response_10` varchar(150) NOT NULL default '',
  `article_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `question` (`poll_question`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains the poll questions';

-- 
-- Dumping data for table `cm_poll_questions`
-- 

INSERT INTO `cm_poll_questions` VALUES (1, now(), 'What do you think of PacerCMS?', 'Simply "Wow."', 'I like it.', 'It needs work.', 'I think I meant to download "Nestopia."', '', '', '', '', '', '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `cm_sections`
-- 

CREATE TABLE `cm_sections` (
  `id` int(11) NOT NULL auto_increment,
  `section_name` varchar(40) NOT NULL default '',
  `section_url` varchar(225) NOT NULL default '',
  `section_editor` varchar(40) NOT NULL default '',
  `section_editor_title` varchar(40) NOT NULL default '',
  `section_editor_email` varchar(100) NOT NULL default '',
  `section_sidebar` text NOT NULL,
  `section_feed_image` VARCHAR(225) NOT NULL,
  `section_priority` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Newspaper section-specific data';

-- 
-- Dumping data for table `cm_sections`
-- 

INSERT INTO `cm_sections` VALUES (1, 'Cover', 'http://localhost/pacercms', 'Samuel L. Clemens', 'Executive Editor', 'editor@example.com', 'Customize this space in the Sections setting.', '', 10);
INSERT INTO `cm_sections` VALUES (2, 'Opinions', 'http://localhost/pacercms/section.php?id=2', 'Walter Winchell', 'Opinions Editor', 'viewpoints@example.com', 'Customize this space in the Sections setting.', '', 20);
INSERT INTO `cm_sections` VALUES (3, 'News', 'http://localhost/pacercms/section.php?id=3', 'Mathew Brady', 'News Editor', 'news@example.com', 'Customize this space in the Sections setting.', '', 30);
INSERT INTO `cm_sections` VALUES (4, 'Entertainment', 'http://localhost/pacercms/section.php?id=4', 'Ernie Pyle', 'Entertainment Editor', 'features@example.com', 'Customize this space in the Sections setting.', '', 40);
INSERT INTO `cm_sections` VALUES (5, 'Sports', 'http://localhost/pacercms/section.php?id=5', 'Terry Frei', 'Sports Editor', 'sports@example.com', 'Customize this space in the Sections setting.', '', 50);

-- --------------------------------------------------------

-- 
-- Table structure for table `cm_settings`
-- 

CREATE TABLE `cm_settings` (
  `id` int(11) NOT NULL auto_increment,
  `site_name` varchar(100) NOT NULL default '',
  `site_url` varchar(225) NOT NULL default '',
  `site_email` varchar(100) NOT NULL default '',
  `site_address` varchar(225) NOT NULL default '',
  `site_city` varchar(40) NOT NULL default '',
  `site_state` varchar(40) NOT NULL default '',
  `site_zipcode` varchar(10) NOT NULL default '',
  `site_telephone` varchar(40) NOT NULL default '',
  `site_fax` varchar(40) NOT NULL default '',
  `site_announcement` text NOT NULL,
  `site_description` text NOT NULL,
  `current_issue` int(11) NOT NULL default '0',
  `next_issue` int(11) NOT NULL default '0',
  `active_poll` int(11) NOT NULL default '0',
  `database_version` varchar(25) NOT NULL default '0.04',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Sets publication issue and other settings';

-- 
-- Dumping data for table `cm_settings`
-- 

INSERT INTO `cm_settings` VALUES (1, 'The Pacer', 'http://localhost/pacercms', 'user@example.com', '', '', '', '', '', '', 'Customize this message under the "Settings" menu item. It is displayed for every user that logs into the system.','Your student newspaper.', 1, 2, 1, '0.5');

-- --------------------------------------------------------

-- 
-- Table structure for table `cm_submitted`
-- 

CREATE TABLE `cm_submitted` (
  `id` int(11) NOT NULL auto_increment,
  `submitted_title` varchar(200) NOT NULL default '',
  `submitted_text` text NOT NULL,
  `submitted_keyword` varchar(150) NOT NULL default '',
  `submitted_author` varchar(150) NOT NULL default '',
  `submitted_author_email` varchar(255) NOT NULL default '',
  `submitted_author_classification` varchar(100) NOT NULL default '',
  `submitted_author_major` varchar(255) NOT NULL default '',
  `submitted_author_city` varchar(255) NOT NULL default '',
  `submitted_author_telephone` varchar(150) NOT NULL default '',
  `submitted_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  `submitted_words` int(5) NOT NULL default '0',
  `issue_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Contains submitted articles via web submission';


-- 
-- Table structure for table `cm_users`
-- 

CREATE TABLE `cm_users` (
  `id` int(11) NOT NULL auto_increment,
  `user_login` varchar(25) NOT NULL default '',
  `user_password` varchar(32) NOT NULL default '',
  `user_first_name` varchar(40) NOT NULL default '',
  `user_middle_name` varchar(40) NOT NULL default '',
  `user_last_name` varchar(40) NOT NULL default '',
  `user_job_title` varchar(100) NOT NULL default '',
  `user_email` varchar(100) NOT NULL default '',
  `user_telephone` varchar(40) NOT NULL default '',
  `user_mobile` varchar(40) NOT NULL default '',
  `user_address` varchar(225) NOT NULL default '',
  `user_city` varchar(40) NOT NULL default '',
  `user_state` char(3) NOT NULL default '',
  `user_zipcode` varchar(10) NOT NULL default '',
  `user_im_aol` varchar(40) NOT NULL default '',
  `user_im_msn` varchar(40) NOT NULL default '',
  `user_im_yahoo` varchar(40) NOT NULL default '',
  `user_im_jabber` varchar(40) NOT NULL default '',
  `user_profile` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Principle user table';

-- 
-- Dumping data for table `cm_users`
-- 

INSERT INTO `cm_users` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'System', '', 'Administrator', '', '', '', '', '', '', '', '', '', '', '', '', '');
