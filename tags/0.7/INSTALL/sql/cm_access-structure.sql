CREATE TABLE `cm_access` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `access_type` set('module','string') NOT NULL default '',
  `access_option` varchar(100) NOT NULL default '',
  `access_value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Grants users access to each module';