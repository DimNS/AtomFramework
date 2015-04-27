DROP TABLE IF EXISTS `atom_browsers`;
CREATE TABLE IF NOT EXISTS `atom_browsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `atom_protocol`;
CREATE TABLE IF NOT EXISTS `atom_protocol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `line` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `function` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `atom_session`;
CREATE TABLE IF NOT EXISTS `atom_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ses` char(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_date` int(15) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY (`id_ses`),
  KEY (`id_user`),
  KEY (`last_date`),
  KEY (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

DROP TABLE IF EXISTS `atom_user`;
CREATE TABLE IF NOT EXISTS `atom_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `reset_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `atom_user` SET
  `id` = '1',
  `email` = 'info@bestion.ru',
  `password` = '$2a$11$eeb81127510262464d5a8u4ND6aiJkXXVjzZPnPus5CqWKPOzvBG6',
  `name` = 'Лучшее решение'
;

DROP TABLE IF EXISTS `atom_user_block`;
CREATE TABLE IF NOT EXISTS `atom_user_block` (
  `user_id` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;