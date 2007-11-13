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