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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={charset} COMMENT='Contains submitted articles via web submission';