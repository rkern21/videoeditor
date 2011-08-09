<?php
/**
 *    @version [ Wainuiomata ]
 *    @package hwdVideoShare
 *    @copyright (C) 2007 - 2009 Highwood Design
 *    @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 ***
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Version information
 * @package hwdVideoShare
 */
class hwdVideoShareVersion {
	/** @var string Product */
	var $PRODUCT 	= 'hwdVideoShare';
	/** @var int Main Release Level */
	var $RELEASE 	= '2.2';
	/** @var string Development Status */
	var $DEV_STATUS = 'Beta';
	/** @var int Sub Release Level */
	var $DEV_LEVEL 	= '3';
	/** @var int build Number */
	var $BUILD	 	= '655';
	/** @var string Codename */
	var $CODENAME 	= 'Wainuiomata';
	/** @var string Date */
	var $RELDATE 	= '2011 02 16';
	/** @var string Time */
	var $RELTIME 	= '21:39:15';
	/** @var string Timezone */
	var $RELTZ 		= 'GMT';
	/** @var string Copyright Text */
	var $COPYRIGHT 	= "Copyright (C) 2007 - 2009 Highwood Design. All rights reserved.";
	/** @var string URL */
	var $URL 		= 'The <a href="http://hwdmediashare.co.uk">hwdVideoShare</a> component is released under the GNU/GPL License.';
	/** @var string Whether site is a production = 1 or demo site = 0: 1 is default */
	var $SITE 		= 1;
	/** @var string Whether site has restricted functionality mostly used for demo sites: 0 is default */
	var $RESTRICT	= 0;
	/** @var string Whether site is still in development phase (disables checks for /installation folder) - should be set to 0 for package release: 0 is default */
	var $SVN		= 0;


	/**
	 * @return string Long format version
	 */
	function getLongVersion() {
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL .' Revision '
			. $this->BUILD.' '.$this->DEV_STATUS
			.' [ '.$this->CODENAME .' ] '. $this->RELDATE .' '
			. $this->RELTIME .' '. $this->RELTZ;
	}

	/**
	 * @return string Short version format
	 */
	function getShortVersion() {
		return $this->RELEASE .'.'. $this->DEV_LEVEL;
	}

}
$_VERSION = new hwdVideoShareVersion();

$version = $_VERSION->PRODUCT .' '. $_VERSION->RELEASE .'.'. $_VERSION->DEV_LEVEL .' '
. $_VERSION->DEV_STATUS
.' [ '.$_VERSION->CODENAME .' ] '. $_VERSION->RELDATE .' '
. $_VERSION->RELTIME .' '. $_VERSION->RELTZ;
?>
