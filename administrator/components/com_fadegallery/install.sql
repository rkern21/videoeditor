INSERT INTO `#__components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
('', 'FadeGallery', 'option=com_fadegallery', 0, 0, '', '', 'com_fadegallery', 0, '', 0, '', 1);

CREATE TABLE IF NOT EXISTS `#__fadegallery` (
  `id` int(10) NOT NULL auto_increment,
  `galleryname` varchar(50) NOT NULL,
  `folder` varchar(255),
  `filelist` text NOT NULL,
  `width` int(10) unsigned NOT NULL DEFAULT '400',
  `height` int(10) unsigned NOT NULL DEFAULT '300',
  `interval` int(10) unsigned NOT NULL DEFAULT '6000',
  `fadetime` int(10) unsigned NOT NULL DEFAULT '2000',
  `fadestep` int(10) unsigned NOT NULL DEFAULT '20',
  `align` varchar(20),
  `cssstyle` varchar(255),
  `padding` int(6) unsigned NOT NULL DEFAULT '0',
  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

