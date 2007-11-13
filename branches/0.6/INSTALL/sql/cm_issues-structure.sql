CREATE TABLE `cm_issues` (
  `id` int(11) NOT NULL auto_increment,
  `issue_date` date NOT NULL default '0000-00-00',
  `issue_number` int(5) NOT NULL default '0',
  `issue_volume` int(5) NOT NULL default '0',
  `issue_circulation` int(5) NOT NULL default '0',
  `online_only` set('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Lists each issue with volume information';