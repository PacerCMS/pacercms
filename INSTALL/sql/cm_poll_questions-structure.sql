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
) ENGINE=MyISAM DEFAULT CHARSET={charset} COMMENT='Contains the poll questions';