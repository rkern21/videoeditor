ALTER TABLE `#__facileforms_forms`
  ADD `class1` VARCHAR( 30 ) AFTER `description` ,
  ADD `class2` VARCHAR( 30 ) AFTER `class1` ,
  ADD `widthmode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `width` ,
  ADD `heightmode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `height` ,
  ADD `prevwidth` INT( 11 ) AFTER `prevmode` ;

ALTER TABLE `#__facileforms_elements`
  ADD `class1` VARCHAR( 30 ) AFTER `type` ,
  ADD `class2` VARCHAR( 30 ) AFTER `class1` ,
  ADD `posxmode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `posx` ,
  ADD `posymode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `posy` ,
  ADD `widthmode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `width` ,
  ADD `heightmode` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `height` ;
