CREATE TABLE `#__facileforms_packages` (
  `id` varchar(30) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `version` varchar(30) NOT NULL default '',
  `created` varchar(20) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `author` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `url` varchar(50) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `copyright` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
INSERT INTO `#__facileforms_packages` VALUES (
  '',
  'mypck_001',
  '0.0.1',
  '2005-07-31 22:21:23',
  'My First Package',
  'My Name',
  'my.name@my.domain',
  'http://www.my.domain',
  'This is the first package that I created',
  'This FacileForms package is released under the GNU/GPL license'
);
DELETE FROM `#__facileforms_config` WHERE id LIKE 'pkg_%';
ALTER TABLE `#__facileforms_compmenus` ADD `package` VARCHAR( 30 ) NOT NULL default '' AFTER `id` ;
ALTER TABLE `#__facileforms_forms` ADD `package` VARCHAR( 30 ) NOT NULL default '' AFTER `id` ;
ALTER TABLE `#__facileforms_pieces` ADD `package` VARCHAR( 30 ) NOT NULL default '' AFTER `published` ;
ALTER TABLE `#__facileforms_scripts` ADD `package` VARCHAR( 30 ) NOT NULL default '' AFTER `published` ;