DROP TABLE IF EXISTS `#__facileforms_compmenus`;
CREATE TABLE `#__facileforms_compmenus` (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `img`  varchar(255) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `page` int(11) NOT NULL default '1',
  `frame` tinyint(1) NOT NULL default '0',
  `border` tinyint(1) NOT NULL default '0',
  `params`  text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

ALTER TABLE `#__facileforms_forms` ADD `runmode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `published` ;