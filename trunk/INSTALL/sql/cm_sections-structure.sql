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
) ENGINE=MyISAM DEFAULT CHARSET={charset} COMMENT='Newspaper section-specific data';