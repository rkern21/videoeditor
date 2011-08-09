DROP TABLE IF EXISTS `#__facileforms_config`;
CREATE TABLE `#__facileforms_config` (
  `id` varchar(30) NOT NULL default '',
  `value` text,
  PRIMARY KEY (`id`)
) TYPE = MYISAM;

ALTER TABLE `#__facileforms_forms`    ADD `description` TEXT AFTER `title`;
ALTER TABLE `#__facileforms_forms`    ADD `emailxml`    TINYINT(1) DEFAULT '0' NOT NULL AFTER `emaillog`;
ALTER TABLE `#__facileforms_elements` ADD `data3`       TEXT AFTER `data2`;
ALTER TABLE `#__facileforms_scripts`  ADD `description` TEXT AFTER `title`;
ALTER TABLE `#__facileforms_pieces`   ADD `description` TEXT AFTER `title`;
ALTER TABLE `#__facileforms_records`  ADD `opsys`       VARCHAR(255) NOT NULL AFTER `browser` ;
ALTER TABLE `#__facileforms_records`  CHANGE `provider` `provider` VARCHAR(255) NOT NULL;
