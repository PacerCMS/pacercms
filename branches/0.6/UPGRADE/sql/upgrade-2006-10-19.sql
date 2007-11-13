ALTER TABLE `cm_settings` ADD `site_description` TEXT NOT NULL AFTER `site_announcement` ;
ALTER TABLE `cm_settings` ADD `database_version` VARCHAR( 5 ) NOT NULL AFTER `active_poll`;
UPDATE `cm_settings` SET `site_description` = 'News and information from your student newspaper.', `database_version` = '0.03' WHERE `id` =1 LIMIT 1 ;