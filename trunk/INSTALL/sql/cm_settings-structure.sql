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
) ENGINE=MyISAM DEFAULT CHARSET={charset} COMMENT='Sets publication issue and other settings';