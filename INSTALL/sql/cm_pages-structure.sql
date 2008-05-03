CREATE TABLE `cm_pages` (
  `id` int(11) NOT NULL auto_increment,
  `page_title` varchar(255) NOT NULL default '',
  `page_short_title` varchar(50) NOT NULL default '',
  `page_text` text NOT NULL,
  `page_side_text` text NOT NULL,
  `page_edited` datetime NOT NULL default '0000-00-00 00:00:00',
  `page_slug` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={charset} COMMENT='Static pages of information';