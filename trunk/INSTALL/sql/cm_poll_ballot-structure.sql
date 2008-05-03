CREATE TABLE `cm_poll_ballot` (
  `id` int(11) NOT NULL auto_increment,
  `poll_id` int(11) NOT NULL default '0',
  `ballot_response` int(2) NOT NULL default '0',
  `ballot_ip_address` varchar(20) NOT NULL default '',
  `ballot_hostname` varchar(225) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={charset} COMMENT='Contains each ballot cast for the poll questions';