<?php
/**
* Joomla/Mambo Community Builder
* @version $Id: plugin.foundation.php 1459 2011-02-14 00:00:35Z beat $
* @package Community Builder
* @subpackage plugin.foundation.php
* @author JoomlaJoe and Beat
* @copyright (C) JoomlaJoe and Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// ensure this file is being included by a parent file
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

global $ueConfig;
include_once( dirname( __FILE__ ) . '/ue_config.php' );
$ueConfig['version']		=	'1.4';
define( '_CB_JQUERY_VERSION', '1.5.0' );		// IMPORTANT: when changing version here also change in the 2 XML installation files
define( '_CB_SPOOFCHECKS', ( isset( $ueConfig['enableSpoofCheck'] ) && $ueConfig['enableSpoofCheck'] ) ? 1 : 0 );
define( '_CB_VALIDATE_NEW', 1 );		// Comment line with // at begin for old-way mosReq way

/**
 * CB 1.2 Stable Release
 */

/**
 * CB Functions
 */

/**
 * gets Itemid of CB profile, or by default of homepage
 * @deprecated  CB 1.2.3  (use $_CB_framework->userProfiler...Url and ->viewUrl from CB 1.2.3 on)
 *
 * @param  boolean $htmlspecialchars  TRUE if should return "&amp:Itemid...." instead of "&Itemid..." (with FALSE as default), === 0 if return only int
 * @param  string $task               task/view  e.g. 'userslist'   (since CB 1.2.3)
 * @return string                     "&Itemid=xxx"
 */
function getCBprofileItemid( $htmlspecialchars = false, $task = 'userprofile' ) {
	global $_CB_database, $_CB_framework;
	static $cacheItemids		=	array();

	if ( ! isset( $cacheItemids[$task] ) ) {
		if ( $task !== 'userprofile' && is_string( $task ) ) {
			$_CB_database->setQuery( 'SELECT id FROM #__menu WHERE link LIKE '
									. $_CB_database->Quote( 'index.php?option=com_comprofiler&task=' . $_CB_database->getEscaped( $task, true ) . '%', false )
									. ' AND published=1 AND access ' . ( $_CB_framework->myCmsGid() == 0 ? '= ' : '<= ' ) . (int) $_CB_framework->myCmsGid() );
			$Itemid				=	(int) $_CB_database->loadResult();
		} else {
			$Itemid				=	null;
		}
		if ( ( $task === 'userprofile' ) || ( ( ! $Itemid ) && ! in_array( $task, array( 'login', 'logout', 'registers', 'lostpassword' ) ) ) ) {
			// $task used to be a boolean before CB 1.2.3 but with no effect:
			$task				=	'userprofile';
			$_CB_database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_comprofiler' AND published=1 AND access " . ( $_CB_framework->myCmsGid() == 0 ? "= " : "<= " ) . (int) $_CB_framework->myCmsGid() );
			$Itemid = (int) $_CB_database->loadResult();
			if ( ! $Itemid ) {		// if no user profile, try getting itemid of the default list:
				$_CB_database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_comprofiler&task=usersList' AND published=1 AND access " . ( $_CB_framework->myCmsGid() == 0 ? "= " : "<= " ) . (int) $_CB_framework->myCmsGid() );
				$Itemid = (int) $_CB_database->loadResult();
			}
		}
		$cacheItemids[$task]	=	$Itemid;
	}
	if ( $cacheItemids[$task] ) {
		if ( is_bool( $htmlspecialchars ) ) {
			return ( $htmlspecialchars ? "&amp;" : "&") . "Itemid=" . $cacheItemids[$task];
		} else {
			return $cacheItemids[$task];
		}
	} else {
		return null;
	}
}

/**
 * Includes CB library
 * --- usage: cbimport('cb.xml.simplexml');
 *
 * @param string $path
 */
function cbimport( $lib ) {
	global $_CB_framework;
	static $imported			=	array();
	static $tmpClasses			=	array( 'cb.html', 'cb.tabs', 'cb.field', 'cb.calendar', 'cb.connection', 'cb.notification' );

	if ( ! isset( $imported[$lib] ) ) {
		$imported[$lib]			=	true;

		$liblow					=	strtolower( $lib );
		$pathAr					=	explode( '.', $liblow );
		if ( $pathAr[0] == 'language' ) {
			$langPath			=	$_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/language';
			$lang				=	$_CB_framework->getCfg( 'lang' );
			if ( in_array( $pathAr[1], array( 'front', 'all' ) ) ) {
				$filename		=	$lang . '.php';
			} else {
				$filename		=	$pathAr[1] . '_language.php';
			}
			if ( ! file_exists( $langPath . '/' . $lang . '/' . $filename ) ) {
				$lang			=	strtolower( $_CB_framework->getCfg( 'lang_tag' ) );
				if ( in_array( $pathAr[1], array( 'front', 'all' ) ) ) {
					$filename	=	'language.php';
				}
			}
			if ( ! file_exists( $langPath . '/' . $lang . '/' . $filename ) ) {
				$lang			=	'default_language';
				if ( in_array( $pathAr[1], array( 'front', 'all' ) ) ) {
					$filename	=	$lang . '.php';
				}
			}
			if ( file_exists( $langPath . '/' . $lang . '/' . $filename ) ) {
				include_once( $langPath . '/' . $lang . '/' . $filename );
			}
		} elseif ( $lib == 'cb.plugins' ) {
			// this part is temporary until we refactor those 2 files into the corresponding CB libraries:
			require_once( $_CB_framework->getCfg('absolute_path') . '/administrator/components/com_comprofiler/plugin.class.php' );
		} elseif ( in_array( $lib, $tmpClasses ) ) {
			// this part is temporary until we refactor those 2 files into the corresponding CB libraries:
			if ( ! isset( $imported['cb.plugins'] ) ) {
				$imported['cb.plugins']	=	true;
				require_once( $_CB_framework->getCfg('absolute_path') . '/administrator/components/com_comprofiler/plugin.class.php' );
			}
			if ( ! isset( $imported['class'] ) ) {
				$imported['class']	=	true;
				require_once( $_CB_framework->getCfg('absolute_path') . '/administrator/components/com_comprofiler/comprofiler.class.php' );
			}
		} elseif ( $lib == 'cb.imgtoolbox' ) {
			// this part is temporary until we refactor those 2 files into the corresponding CB libraries:
			require_once( $_CB_framework->getCfg('absolute_path') . '/administrator/components/com_comprofiler/imgToolbox.class.php' );
		} elseif ( $lib == 'cb.snoopy' ) {
			require_once( $_CB_framework->getCfg('absolute_path') . '/administrator/components/com_comprofiler/Snoopy.class.php' );
		} else {
			array_pop( $pathAr );
			$filepath		=	implode( '/', $pathAr ) . (count( $pathAr ) ? '/' : '' ) . $liblow . '.php';

			require_once( $_CB_framework->getCfg('absolute_path') . '/administrator/components/com_comprofiler/library/' . $filepath );
		}
	}
}
/**
 * Sanitizes an array of (int)
 * 
 * @param  array $array  in/out
 * @return array
 */
function & cbArrayToInts( &$array ) {
	foreach ( $array as $k => $v ) {
		$array[$k]	=	(int) $v;
	}
	return $array;
}
/**
 * Does the opposite of htmlspecialchars()
 *
 * @param  string  $text
 * @return string
 */
function cbUnHtmlspecialchars( $text ) {
	return str_replace( array( "&amp;", "&quot;", "&#039;", "&lt;", "&gt;" ), array( "&", "\"", "'", "<", ">" ), $text );
}
/**
* String based find and replace that is case insensitive and works on php4 too
* same as PHP5 str_ireplace()
*
* @param  string  $search   value to look for
* @param  string  $replace  value to replace with
* @param  string  $subject  text to be searched
* @return string            with text searched and replaced
*/
function cbstr_ireplace( $search, $replace, $subject ) {
	if ( function_exists('str_ireplace') ) {
		return str_ireplace($search,$replace,$subject);		// php 5 only
	}
	$srchlen = strlen($search);    // lenght of searched string
	$result  = "";

	while ( true == ( $find = stristr( $subject, $search ) ) ) {	// find $search text in $subject - case insensitiv
		$srchtxt = substr($find,0,$srchlen);    			// get new case-sensitively-correct search text
		$pos	 = strpos( $subject, $srchtxt );			// stripos is php5 only...
		$result	 .= substr( $subject, 0, $pos ) . $replace;	// replace found case insensitive search text with $replace
		$subject = substr( $subject, $pos + $srchlen );
	}
	return $result . $subject;
}

/**
 * Translates text strings from CB and core cms ('_UE_....') into current language
 *
 * @param  string  $text
 * @return string
 */
function getLangDefinition($text) {
	// check for '::' as a workaround of bug #42770 in PHP 5.2.4 with optimizers:
	if ( ( strpos( $text, '::' ) === false ) && defined( $text ) ) {
		$returnText		=	constant( $text );
	} else {
		$returnText		=	$text;			// not yet: CBTxt::T( $text );
	}
	return $returnText;
}

/**
 * Check Mambo/Joomla/others version for API
 *
 * @param  string  $info  'api', 'product', 'release'
 * @return mixed          'api'     : API version: =0 = mambo 4.5.0-4.5.3+Joomla 1.0.x, =1 = Joomla! 1.1, >1 newever ones: maybe compatible, <0: -1: Mambo 4.6
 *                        'product' : product name
 *                        'release' : php-style release number
 */
function checkJversion( $info = 'api' ) {
	static $version						=	array();

	if ( isset( $version[$info] ) ) {
		return $version[$info];
	}

	if ( class_exists( 'JVersion' ) ) {
		$VO								=	new JVersion();
	} else {
		global $_VERSION;

		if ( $_VERSION ) {
			$VO							=	$_VERSION;
		} else {
			trigger_error( 'Unable to determine CMS version.', E_USER_ERROR );
			die();
		}
	}

	switch ( $info ) {
		case 'api':
			$cms_version				=	substr( $VO->RELEASE, 0, 3 );

			if ( $VO->PRODUCT == 'Mambo' ) {
				if ( strcasecmp( $cms_version, '4.6' ) < 0 ) {
					$version[$info]		=	0;
				} else {
					$version[$info]		=	-1;
				}
			} elseif ( $VO->PRODUCT == 'Elxis' ) {
				$version[$info]			=	0;
			} elseif ( $VO->PRODUCT == 'MiaCMS' ) {
				$version[$info]			=	-1;
			} elseif ( ( $VO->PRODUCT == 'Joomla!' ) || ( $VO->PRODUCT == 'Accessible Joomla!' ) ) {
				if ( strcasecmp( $cms_version, '1.6' ) == 0 ) {
					$version[$info]		=	2;
				} elseif ( strcasecmp( $cms_version, '1.5' ) == 0 ) {
					$version[$info]		=	1;
				} else {
					$version[$info]		=	0;
				}
			} else {
				$version[$info]			=	0;
			}
			break;
		case 'product':
			$version[$info]				=	$VO->PRODUCT;
			break;
		case 'release':
			$version[$info]				=	$VO->RELEASE;
			break;
		case 'dev_level':
			$version[$info]				=	$VO->DEV_LEVEL;
			break;
		default:
			break;
	}
	return $version[$info];
}

/**
 * Utility function to return a value from a named array or a specified default.
 * TO CONTRARY OF MAMBO AND JOOMLA mos Get Param:
 * 1) DOES NOT MODIFY ORIGINAL ARRAY
 * 2) Does sanitize ints
 * 3) Does return default array() for a default value array(0) which indicates sanitizing an array of ints.
 *
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _MOS_NOTRIM prevents trim, _MOS_ALLOWHTML allows safe html, _MOS_ALLOWRAW allows raw input
 */
define( "_CB_NOTRIM", 0x0001 );
//define( "_MOS_ALLOWHTML", 0x0002 );
define( "_CB_ALLOWRAW", 0x0004 );
function cbGetParam( &$arr, $name, $def=null, $mask=0 ) {
	static $noHtmlFilter	=	null;

	if ( isset( $arr[$name] ) ) {
        if ( is_array( $arr[$name] ) ) {
        	$ret			=	array();
        	foreach ( array_keys( $arr[$name] ) as $k ) {
        		$ret[$k]	=	cbGetParam( $arr[$name], $k, $def, $mask);
        		if ( $def === array( 0 ) ) {
        			$ret[$k] =	(int) $ret[$k];
        		}
        	}
        } else {
			$ret			=	$arr[$name];
			if ( is_string( $ret ) ) {
				if ( ! ( $mask & _CB_NOTRIM ) ) {
					$ret	=	trim( $ret );
				}
				if ( ! ( $mask & _CB_ALLOWRAW ) ) {
					if ( is_null( $noHtmlFilter ) ) {
						cbimport( 'phpinputfilter.inputfilter' );
						$noHtmlFilter = new CBInputFilter( /* $tags, $attr, $tag_method, $attr_method, $xss_auto */ );
					}
					$ret	=	$noHtmlFilter->process( $ret );
				}
				if ( is_int( $def ) ) {
					$ret	=	(int) $ret;
				} elseif ( is_float( $def ) ) {
					$ret	=	(float) $ret;
				} elseif ( !  get_magic_quotes_gpc() ) {
					$ret	=	addslashes( $ret );
				}
			}
        }
		return $ret;
	} elseif ( false !== ( $firstSeparator = strpos( $name, '[' )  ) ) {
		// html-input-name-encoded array selection, e.g. a[b][c]
		$indexes			=	null;
		$mainArrName		=	substr( $name, 0, $firstSeparator );
		$count				=	preg_match_all( '/\\[([^\\[\\]]+)\\]/', substr( $name, $firstSeparator ), $indexes );
		if ( isset( $arr[$mainArrName] ) && ( $count > 0 ) ) {
			$a				=	$arr[$mainArrName];
			for ( $i = 0; $i < ( $count - 1 ); $i++ ) {
				if ( ! isset( $a[$indexes[1][$i]] ) ) {
					$a		=	null;
					break;
				}
				$a			=	$a[$indexes[1][$i]];
			}
		} else {
			$a				=	null;
		}
		if ( $a !== null ) {
			return cbGetParam( $a, $indexes[1][$i], $def, $mask );
		}
	}
	if ( $def === array( 0 ) ) {
		return array();
	}
	return $def;
}

/**
 * Redirects browser to new $url with a $message .
 * No return from this function !
 *
 * @param  string  $url
 * @param  string  $message
 * @param  string  $messageType  'message', 'error'
 */
function cbRedirect( $url, $message = '', $messageType = 'message' ) {
	global $_CB_framework, $_CB_database;

	if ( ( $_CB_framework->getCfg( 'debug' ) > 0 ) && ( ob_get_length() || ( $_CB_framework->getCfg( 'debug' ) > 1 ) ) ) {
		$outputBufferLength		=	ob_get_length();
		$ticker					=	( checkJversion() == 2 ? $_CB_database->_db->getTicker() : $_CB_database->_db->_ticker );
		$log					=	( checkJversion() == 2 ? $_CB_database->_db->getLog() : $_CB_database->_db->_log );
		echo '<br /><br /><strong>Site Debug mode: CB redirection';
		if ( $message ) {
			echo ' with ' . $messageType . ' "' . $message . '"';
		}
		if ( $outputBufferLength ) {
			echo ' <u>without empty output</u>';
		}
		echo "<br /><p><em>During its normal operations Community Builder often redirects you between pages and this causes potentially interesting debug information to be missed. "
			. "When your site is in debug mode (global joomla/mambo config is site debug ON), some of these automatic redirects are disabled. "
			. "This is a normal feature of the debug mode and does not directly mean that you have any problems.</em></p>"
			. '</strong>Click this link to proceed with the next page (in non-debug mode this is automatic): ';
		echo '<a href="' . $url . '">' . htmlspecialchars( $url ) . '</a><br /><br /><hr />';

		echo $ticker . ' queries executed'
			. '<pre>';
 		foreach ( $log as $k => $sql ) {
 			echo $k + 1 . "\n" . htmlspecialchars( $sql ) . '<hr />';
		}
		echo '</hr>'
			. '</hr>POST: ';
		var_export( $_POST );
		echo '</pre>';
		die();
	} else {
		$_CB_framework->redirect( $url, $message, $messageType );
	}
}

/**
 * stripslashes() string or nested array of strings
 *
 * @param  string|array  with slashes
 * @return string|array  without slashes
 */
function cbStripslashes( $value ) {
	$striped					=	'';
	if ( is_string( $value ) ) {
		$striped				=	stripslashes( $value );
	} else {
		if ( is_array( $value ) ) {
			$striped			=	array();
			foreach ( array_keys( $value ) as $k ) {
				$striped[$k]	=	cbStripslashes( $value[$k] );
			}
		} else {
			$striped			=	$value;
		}
	}
	return $striped;
}

/**
* Returns full path to template directory, as live URL (live_site, by default), absolute directory path
*
* @param  string  $output        'live_site' (with trailing /), 'absolute_path' (without trailing /), 'dir' name only (depreciated was: int  DEPRECIATED: info for backwards-compatibility: user interface : 1: frontend, 2: backend (not used anymore)
* @param  string  $templateName  null: according to settings, string: name of template (directory)
* @return string                 Template directory path with trailing '/'
*/
function selectTemplate( $output = 'live_site', $templateName = null ) {
	global $_CB_framework, $ueConfig;

	if ( $templateName == null ) {
		if ( $_CB_framework->getUi() == 1 ) {
			$templateName	=	$ueConfig['templatedir'];
		} else {
			$templateName	=	'luna';
		}
	}
	if ( $output == 'dir' ) {
		return $templateName;
	} elseif ( $output == 'absolute_path' ) {
		return $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/plugin/templates/' . $templateName;
	} else {
		return ( $_CB_framework->getUi() == 2 ? '..' : $_CB_framework->getCfg( 'live_site' ) ) . '/components/com_comprofiler/plugin/templates/' . $templateName . '/';
	}
}



function cbSpoofString( $string = null, $secret = null ) {
	global $_CB_framework;

	$date			=	date( 'dmY' );
	if ( $string === null ) {
		$salt		=	array();
		$salt[0]	=	mt_rand( 1, 2147483647 );
		$salt[1]	=	mt_rand( 1, 2147483647 );		// 2 * 31 bits random
	} else {
		$salt		=	sscanf( $string, 'cbm_%08x_%08x_%s' );
		if ( $string != sprintf( 'cbm_%08x_%08x_%s', $salt[0], $salt[1], md5( $salt[0] . $date . $_CB_framework->getUi() . $_CB_framework->getCfg( 'db' ) . $_CB_framework->getCfg('secret') . $secret . $salt[1] ) ) ) {
			$date	=	date( 'dmY', time() - 64800 );	// 18 extra-hours of grace after midnight.
		}
	}
	return sprintf( 'cbm_%08x_%08x_%s', $salt[0], $salt[1], md5( $salt[0] . $date . $_CB_framework->getUi() . $_CB_framework->getCfg( 'db' ) . $_CB_framework->getCfg('secret') . $secret . $salt[1] ) );
}
function cbSpoofField() {
	return 'cbsecuritym3';
}
/**
 * Computes and returns an antifspoofing additional input tag
 *
 * @return string "<input type="hidden...\n" tag
 */
function cbGetSpoofInputTag( $secret = null, $cbSpoofString = null ) {
	if ( $cbSpoofString === null ) {
		$cbSpoofString		=	cbSpoofString( null, $secret );
	}
	return "<input type=\"hidden\" name=\"" . cbSpoofField() . "\" value=\"" .  $cbSpoofString . "\" />\n";
}

function _cbjosSpoofCheck($array, $badStrings) {
	foreach ($array as $v) {
		foreach ($badStrings as $v2) {
			if (is_array($v)) {
				_cbjosSpoofCheck($v, $badStrings);
			} else if (strpos( $v, $v2 ) !== false) {
				header( "HTTP/1.0 403 Forbidden" );
				exit( _UE_NOT_AUTHORIZED );
			}
		}
	}
}
/**
 * Checks spoof value and other spoofing and injection tricks
 *
 * @param  string   $secret   extra-hashing value for this particular spoofCheck
 * @param  string   $var      'POST', 'GET', 'REQUEST'
 * @param  int      $mode     1: exits with script to display error and go back, 2: returns true or false.
 * @return boolean  or exit   If $mode = 2 : returns false if session expired.
 */
function cbSpoofCheck( $secret = null, $var = 'POST', $mode = 1 ) {
	global $_POST, $_GET, $_REQUEST;

	if ( _CB_SPOOFCHECKS ) {
		if ( $var == 'GET' ) {
			$validateValue 	=	cbGetParam( $_GET,     cbSpoofField(), '' );
		} elseif ( $var == 'REQUEST' ) {
			$validateValue 	=	cbGetParam( $_REQUEST, cbSpoofField(), '' );
		} else {
			$validateValue 	=	cbGetParam( $_POST,    cbSpoofField(), '' );
		}
		if ( ( ! $validateValue ) || ( $validateValue != cbSpoofString( $validateValue, $secret ) ) ) {
			if ( $mode == 2 ) {
				return false;
			}
			_cbExpiredSessionJSterminate( 200 );
			exit;
		}
	}
	// First, make sure the form was posted from a browser.
	// For basic web-forms, we don't care about anything
	// other than requests from a browser:
	if (!isset( $_SERVER['HTTP_USER_AGENT'] )) {
		header( 'HTTP/1.0 403 Forbidden' );
		exit( _UE_NOT_AUTHORIZED );
	}

	// Make sure the form was indeed POST'ed:
	//  (requires your html form to use: action="post")
	if (!$_SERVER['REQUEST_METHOD'] == 'POST' ) {
		header( 'HTTP/1.0 403 Forbidden' );
		exit( _UE_NOT_AUTHORIZED );
	}

	// Attempt to defend against header injections:
	$badStrings = array(
		'Content-Type:',
		'MIME-Version:',
		'Content-Transfer-Encoding:',
		'bcc:',
		'cc:'
	);

	// Loop through each POST'ed value and test if it contains
	// one of the $badStrings:
	foreach ($_POST as $v){
		foreach ($badStrings as $v2) {
			if (is_array($v)) {
				_cbjosSpoofCheck($v, $badStrings);
			} else if (strpos( $v, $v2 ) !== false) {
				header( "HTTP/1.0 403 Forbidden" );
				exit( _UE_NOT_AUTHORIZED );
			}
		}
	}

	// Made it past spammer test, free up some memory
	// and continue rest of script:
	unset( $v, $v2, $badStrings );
	return true;
}
function _cbExpiredSessionJSterminate( $code = 403 ) {
	if ( $code == 403 ) {
		header( 'HTTP/1.0 403 Forbidden' );
	}
	echo "<script type=\"text/javascript\">alert('" . addslashes( _UE_SESSION_EXPIRED . ' ' . _UE_PLEASE_REFRESH ) . "'); window.history.go(-1);</script> \n";
	exit;
}

/**
 * CB Classes
 */
class cbObject {
	/**
	* Gets a param value
	*
	* @param  string  $key      The name of the param
	* @param  mixed   $default  The default value if not found (if array(), the return will be an array too)
	* @return string|array
	*/
	function get( $key, $default = null ) {
		if ( isset( $this->$key ) ) {
			return $this->$key;
		}
		return $default;
	}
	/**
	* Sets a value to a param
	*
	* @param  string  $key    The name of the param
	* @param  string  $value  The value of the parameter
	* @return cbObject        For chaining
	*/
	function set( $key, $value='' ) {
		$this->$key		=	$value;
		return $this;
	}
}
/**
* Parameters handler
* @package Joomla/Mambo Community Builder
*/
class cbParamsBase {
	/** @var object */
	var $_params = null;
	/** @var string The raw params string */
	var $_raw = null;
	/**
	* Constructor
	*
	* @param  string  $paramsValues  The raw parms text
	*/
	function cbParamsBase( $paramsValues ) {
	    $this->_params = $this->parse( $paramsValues );
	    $this->_raw = $paramsValues;
	}
	/**
	* Loads from the plugins database
	*
	* @param  string   $element  The plugin element name
	* @return boolean            true: could load, false: query error.
	*/
	function loadFromDB( $element ) {
		global $_CB_database;

	    $_CB_database->setQuery("SELECT params FROM `#__comprofiler_plugin` WHERE element = '" . $_CB_database->getEscaped( $element ) . "'" );
	    $text = $_CB_database->loadResult();
	    $this->_params = $this->parse( $text );
	    $this->_raw = $text;
	    return ( $text !== null );
	}
	/**
	* Transforms the existing params to a ini string
	* @since 1.2.1
	*
	* @return string
	*/
	function toIniString() {
		$txt		=	array();
		foreach ( get_object_vars( $this->_params ) as $k => $v ) {
			if ( strstr( $v, "\n" ) ) {
				$v = str_replace( array( "\\", "\n", "\r" ), array( "\\\\", '\\n', '\\r'  ) , $v );
			}
			$txt[] = $k . '=' . $v;
		}
		return implode( "\n", $txt );
	}
	/**
	 * Returns an array of all current params
	 *
	 * @return array
	 */
	function toParamsArray( ) {
		return get_object_vars( $this->_params );
	}
	/**
	* Sets a value to a param
	*
	* @param  string  $key    The name of the param
	* @param  string  $value  The value of the parameter
	* @return string  The set value
	*/
	function set( $key, $value='' ) {
		$this->_params->$key = $value;
		return $value;
	}
	/**
	* Un-Sets a param
	* @since 1.2.1
	*
	* @param  string  $key    The name of the param
	*/
	function unsetParam( $key ) {
		unset( $this->_params->$key );
	}
	/**
	* Sets a default value to param if not alreay assigned
	*
	* @param  string  $key    The name of the param
	* @param  string  $value  The value of the parameter
	* @return string  The set value
	*/
	function def( $key, $value='' ) {
	    return $this->set( $key, $this->get( $key, $value ) );
	}
	/**
	* Gets a param value
	*
	* @param  string  $key      The name of the param
	* @param  mixed   $default  The default value if not found (if array(), the return will be an array too)
	* @return string|array
	*/
	function get( $key, $default = null ) {
	    if ( isset( $this->_params->$key ) ) {
	    	if ( is_array( $default ) ) {
	    		if ( strpos( $this->_params->$key, '|**|' ) === 0 ) {
	    			// indexed array:
		    		$parts				=	explode( '|**|', substr( $this->_params->$key, 4 ) );
		    		$r					=	array();
					foreach ( $parts as $v ) {
						$p				=	explode( '=', $v, 2 );
						if ( isset( $p[1] ) ) {
							$r[$p[0]]	=	$p[1];
						}
					}
					return $r;
	    		} else {
	    			// non-indexed array:
		    		return explode( '|*|', $this->_params->$key );
	    		}
	    	} else {
		        return $this->_params->$key;
	    	}
		} else {
			$isArray		=	strpos( $key, '[' );
			if ( $isArray ) {
				// case of indexed arrays:
				$value		=	$default;
				$arrayString =	$this->get( substr( $key, 0, $isArray ) );
				if ( $arrayString && ( strpos( $arrayString, '|**|' ) === 0 ) ) {
					$index	=	substr( $key, $isArray + 1, strpos( $key, ']' ) - $isArray -1 );
					$parts	=	explode( '|**|', substr( $arrayString, 4 ) );
					foreach ( $parts as $v ) {
						$p	=	explode( '=', $v, 2 );
						if ( $p[0] == $index ) {
							if ( isset( $p[1] ) ) {
								return $p[1];
							}
						}
					}
				}
			}
			return $default;
		}
	}
	/**
	* Parse an .ini string, based on phpDocumentor phpDocumentor_parse_ini_file function
	*
	* @param  mixed    $txt               The ini string or array of lines
	* @param  boolean  $process_sections  Add an associative index for each section [in brackets]
	* @param  boolean  $asArray           Returns an array instead of an object
	* @return object|array
	*/
	function parse( $txt, $process_sections = false, $asArray = false ) {
		if (is_string( $txt )) {
			$lines = explode( "\n", $txt );
		} else if (is_array( $txt )) {
			$lines = $txt;
		} else {
			$lines = array();
		}
		$obj = $asArray ? array() : new cbObject();

		$sec_name = '';
		$unparsed = 0;
		if (!$lines) {
			return $obj;
		}
		foreach ($lines as $line) {
			// ignore comments
			if ($line && $line[0] == ';') {
				continue;
			}
			$line = trim( $line );

			if ($line == '') {
				continue;
			}
			if ($line && $line[0] == '[' && $line[strlen($line) - 1] == ']') {
				$sec_name = substr( $line, 1, strlen($line) - 2 );
				if ($process_sections) {
					if ($asArray) {
						$obj[$sec_name] = array();
					} else {
						$obj->$sec_name = new cbObject();
					}
				}
			} else {
				if ( false !== ( $pos = strpos( $line, '=' ) ) ) {
					$property = trim( substr( $line, 0, $pos ) );

					if (substr($property, 0, 1) == '"' && substr($property, -1) == '"') {
						$property = stripcslashes(substr($property,1,count($property) - 2));
					}
					$value = trim( substr( $line, $pos + 1 ) );
					if ($value == 'false') {
						$value = false;
					}
					if ($value == 'true') {
						$value = true;
					}
					if (substr( $value, 0, 1 ) == '"' && substr( $value, -1 ) == '"') {
						$value = stripcslashes( substr( $value, 1, count( $value ) - 2 ) );
					}

					if ($process_sections) {
						$value = str_replace( array( '\n', '\r', '\\\\' ), array( "\n", "\r", '\\' ), $value );
						if ($sec_name != '') {
							if ($asArray) {
								$obj[$sec_name][$property] = $value;
							} else {
								$obj->$sec_name->$property = $value;
							}
						} else {
							if ($asArray) {
								$obj[$property] = $value;
							} else {
								$obj->$property = $value;
							}
						}
					} else {
						$value = str_replace( array( '\n', '\r', '\\\\' ), array( "\n", "\r", '\\' ), $value );
						if ($asArray) {
							$obj[$property] = $value;
						} else {
							$obj->$property = $value;
						}
					}
				} else {
					if ($line && trim($line[0]) == ';') {
						continue;
					}
					if ($process_sections) {
						$property = '__invalid' . $unparsed++ . '__';
						if ($process_sections) {
							if ($sec_name != '') {
								if ($asArray) {
									$obj[$sec_name][$property] = trim($line);
								} else {
									$obj->$sec_name->$property = trim($line);
								}
							} else {
								if ($asArray) {
									$obj[$property] = trim($line);
								} else {
									$obj->$property = trim($line);
								}
							}
						} else {
							if ($asArray) {
								$obj[$property] = trim($line);
							} else {
								$obj->$property = trim($line);
							}
						}
					}
				}
			}
		}
		return $obj;
	}
}

/**
 * Lightweight CB user class read-only for use outside CB
 *
 * @author Beat
 * @license GPL v2
 */
class CBuser {
	/**
	 * CB user object for database tables
	 * @var moscomprofilerUser
	 */
	var $_cbuser;
	/**
	 * the CB tabs object for that user
	 * @var cbTabs
	 */
	var $_cbtabs	=	null;
	/** Db
	 * @var CBdatabase */
	var $_db;
	/**
	 * For function advanceNoticeOfUsersNeeded( $usersIds )
	 * @var array of int  id to load at next needed SQL query
	 */
	private static $idsToLoad						=	array();
	/**
	 * Constructor
	 */
	function CBuser( ) {
/*
 		global $_CB_database, $database;
		if ( $_CB_database ) {
			$this->_db	=&	$_CB_database;
		} else {
			$this->_db	=&	$database;
		}
*/
		global $_CB_database;

		$this->_db			=&	$_CB_database;
	}
/*
	 * Gets The reference instance of CBuser for user id, or a new instance if $userId == 0
	 *
	 * @param  int  $userId
	 * @return CBUser|NULL   Returns NULL if Id is specified, but not loaded.
	 *
	function & getInstance( $userId ) {
		static $instances		=	array();

		$userIdInt				=	(int) $userId;
		if ( $userIdInt ) {
			if ( ! isset( $instances[$userIdInt] ) ) {
				$instances[$userIdInt]	=	new CBuser();
				if ( ! $instances[$userIdInt]->load( $userId ) ) {
					$null		=	null;
					return $null;
				}
			}
			return $instances[$userIdInt];
		} else {
			$cbUser				=	new CBuser();
			$cbUser->_cbuser	=	new moscomprofilerUser( $cbUser->_db );
			return $cbUser;
		}
	}
*/
	/**
	 * Gets The reference instance of CBuser for user id, or a new instance if $userId == 0
	 * @static
	 *
	 * @param  int|null     $userId
	 * @return CBuser | null  Returns NULL if Id is specified, but not loaded.
	 */
	static function & getInstance( $userId ) {
		if ( $userId !== null ) {
			$userId				=	(int) $userId;
		}
		$user	=& CBuser::_getOrSetInstance( $userId );
		return $user;
	}
	/**
	 * Gets The reference instance of moscomprofilerUser for user id, or a new instance if $userId == 0
	 * @since CB 1.2.3
	 * @static
	 *
	 * @param  int|null     $userId
	 * @return moscomprofilerUser  Check $user->id if Id is specified, but not loaded.
	 */
	static function & getUserDataInstance( $userId ) {
		$cbUser		=	CBuser::getInstance( (int) $userId );
		if ( $cbUser ) {
			$user	=&	$cbUser->getUserData();
		} else {
			global $_CB_database;
			$user	=	new moscomprofilerUser( $_CB_database );
		}
		return $user;
	}
	/**
	 * Creates and sets a new instance of CBuser to $user
	 * @static
	 *
	 * @param  moscomprofilerUser  $user
	 * @return CBuser
	 */
	function & setUserGetCBUserInstance( & $user ) {
		if ( is_object( $user ) ) {
			return CBuser::_getOrSetInstance( $user );
		} else {
			trigger_error( 'CBUser::setUserGetCBUserInstance called without object', E_USER_ERROR );
			$null				=	null;
			return $null;
		}
	}
	/**
	 * Private storage holder of the instances of CBUser
	 * @access private
	 * @static
	 *
	 * @param  int|moscomprofilerUser|null   $userOrValidId
	 * @return CBUser|null
	 */
	static function & _getOrSetInstance( & $userOrValidId ) {
		static $instances							=	array();

		if ( is_int( $userOrValidId ) && ( $userOrValidId !== 0 ) ) {
			if ( ! isset( $instances[$userOrValidId] ) ) {
				if ( count( self::$idsToLoad ) == 0 ) {
					$instances[$userOrValidId]		=	new CBuser();
					if ( ! $instances[$userOrValidId]->load( $userOrValidId ) ) {
						unset( $instances[$userOrValidId] );
						$null						=	null;
						return $null;
					}
				} else {
					self::loadUsersMatchingIdIntoList( self::$idsToLoad, $instances );
					self::$idsToLoad				=	array();
					if ( ! isset( $instances[$userOrValidId] ) ) {
						$null						=	null;
						return $null;
					}
				}
			}
			return $instances[$userOrValidId];
		} elseif ( is_object( $userOrValidId ) && isset( $userOrValidId->id ) && $userOrValidId->id ) {
			// overwrite on purpose previous cached user, if any:
			$instances[(int) $userOrValidId->id]	=	new CBuser();
			$instances[(int) $userOrValidId->id]->loadCbRow( $userOrValidId );
			return $instances[(int) $userOrValidId->id];
		} else {
			$cbUser									=	new CBuser();
			$cbUser->_cbuser						=	new moscomprofilerUser( $cbUser->_db );
			return $cbUser;
		}
	}
	function load( $cbUserId ) {
		cbimport( 'cb.tables' );

		$this->_cbuser		=	new moscomprofilerUser( $this->_db );
		return  $this->_cbuser->load( $cbUserId );
	}
	/**
	 * Loads a list of moscomprofilerUser into an existing array if they are not already in it
	 * (indexed by key of this table)
	 * @since 1.4 (experimental)
	 *
	 * @param  array    $usersIds      array of id to load
	 * @param  array    $objectsArray  IN/OUT   (int) id => moscomprofilerUser users
	 */
	private static function loadUsersMatchingIdIntoList( $usersIds, &$objectsArray ) {
		$cbUser									=	new CBuser();
		$cbUser->_cbuser						=	new moscomprofilerUser( $cbUser->_db );
		$cbUser->_cbuser->loadUsersMatchingIdIntoList( $usersIds, $objectsArray, 'CBuser' );
	}
	/**
	* Copy the named array or object content into this object as vars
	* All $arr values are filled in vars of $this->_cbuser
	* @access private this is just for moscomprofilerUser::loadUsersMatchingIdIntoList()'s use
	* @param  array               $arr    The input array
	*/
	function bindThisUserFromDbArray( $arr ) {
		$this->_cbuser							=	new moscomprofilerUser( $this->_db );
		$this->_cbuser->bindThisUserFromDbArray( $arr );
	}
	/**
	 * Sets an additional list of user records to also load and cache with next SQL query
	 * e.g.:
	 * CBuser::advanceNoticeOfUsersNeeded( array( 66, 67, 65 ) );		// just remembers
	 * CBuser::advanceNoticeOfUsersNeeded( array( 64, 65 ) );			// just remembers
	 * echo CBuser::getUserDataInstance( 64 )->id;		// echo's 64	// and loads 64-67
	 * CBuser::advanceNoticeOfUsersNeeded( array( 68, 67, 69, 71 ) );	// just remembers
	 * echo CBuser::getUserDataInstance( 67 )->id;		// echos 67		// and doesn't load
	 * echo CBuser::getUserDataInstance( 69 )->username;	// echos	// and loads 68,69,71
	 *
	 * @param  array of int   $usersIds
	 */
	public static function advanceNoticeOfUsersNeeded( $usersIds ) {
		self::$idsToLoad	=	array_unique( array_merge( self::$idsToLoad, $usersIds ) );
	}
	function loadCmsUser( $cmsUserId ) {
		return $this->load( $cmsUserId );	// for now it's the same but use right one please
	}
	function loadCbRow( &$row ) {
		$this->_cbuser	=&	$row;
	}
	/**
	 * Returns the User's profile data
	 *
	 * @return moscomprofilerUser
	 */
	function & getUserData( ) {
		return $this->_cbuser;
	}
	// EXPERIMENTAL STUFF NEW IN 1.2 RC 3:
	/**
	 * Creates if needed cbTabs object
	 *
	 * @param  boolean  $outputTabpaneScript
	 * @return cbTabs
	 */
	function & _getCbTabs( $outputTabpaneScript = true ) {
		if ( $this->_cbtabs === null ) {
			global $_CB_framework;

			cbimport('cb.tabs');
			$this->_cbtabs	=	new cbTabs( 0, $_CB_framework->getUi(), null, $outputTabpaneScript );
		}
		return $this->_cbtabs;
	}
	/**
	 * Formatter:
	 * Returns a field in specified format
	 *
	 * @param  string                $fieldName     Name of field to render
	 * @param  mixed                 $defaultValue  Value if field is not in reach of viewer user or innexistant
	 * @param  string                $output        'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string                $formatting    'tr', 'td', 'div', 'span', 'none',   'table'??
	 * @param  string                $reason        'profile' for user profile view and edit, 'register' for registration, 'search' for searches
	 * @param  int                   $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	function getField( $fieldName, $defaultValue = null, $output = 'html', $formatting = 'none', $reason = 'profile', $list_compare_types = 0 ) {
		global $_CB_framework, $_PLUGINS;

		$tabs			=&	$this->_getCbTabs();
		$fields			=	$tabs->_getTabFieldsDb( null, $this->getInstance( $_CB_framework->myId() ), $reason, $fieldName );
		if ( isset( $fields[0] ) ) {
			$field		=	$fields[0];
			$value		=	$_PLUGINS->callField( $field->type, 'getFieldRow', array( &$field, &$this->_cbuser, $output, $formatting, $reason, $list_compare_types ), $field );
		} else {
			$value		=	$defaultValue;
		}
		return $value;
	}
	function getPosition( $position ) {
		$userViewTabs	=	$this->getProfileView( $position );
		if ( isset( $userViewTabs[$position] ) ) {
			return $userViewTabs[$position];
		} else {
			return null;
		}
	}
	function getTab( $tab, $defaultValue = null, $output = 'html', $formatting = null, $reason = 'profile' ) {
		$tabs			=&	$this->_getCbTabs();
		$tabs->generateViewTabsContent( $this->_cbuser, '', $tab, $output, $formatting, $reason );
		return $tabs->getProfileTabHtml( $tab, $defaultValue );
	}
	function getProfileView( $position = '' ) {
		$tabs			=&	$this->_getCbTabs();
		return $tabs->getViewTabs( $this->_cbuser, $position );
	}
	/**
	 * DO NOT USE: This function will disapear in favor of a new one in very next minor release.
	 * you should use
	 * cbUser->getField( 'avatar' , null, 'csv', 'none', 'list' );
	 * instead of this derpreciated call !
	 *
	 * @param unknown_type $show_avatar
	 * @return unknown
	 */
	function avatarFilePath( $show_avatar = 2 ) {
		global $_CB_framework;

		$oValue				=	null;
		if ( $this->_cbuser ) {
			if ( $this->_cbuser->id ) {
				$avatar			=	$this->_cbuser->avatar;
				$avatarapproved	=	$this->_cbuser->avatarapproved;

				$absolute_path	=	$_CB_framework->getCfg( 'absolute_path' );
				$live_site		=	$_CB_framework->getCfg( 'live_site' );

				if ( $avatarapproved == 0 ) {
					return selectTemplate() . 'images/avatar/tnpending_n.png';
				} elseif ( ( $avatar == '' ) && $avatarapproved == 1 ) {
					$oValue		=	null;
				} elseif ( strpos( $avatar, 'gallery/' ) === false ) {
					$oValue		=	'images/comprofiler/tn' . $avatar;
				} else {
					$oValue		=	'images/comprofiler/' . $avatar;
				}
				if ( ! is_file( $absolute_path . '/' . $oValue ) ) {
					$oValue		=	null;
				}
			}
			if ( ( ! $oValue ) && ( $show_avatar == 2 ) ) {
				return selectTemplate() . 'images/avatar/tnnophoto_n.png';
			}
		}
		if ( $oValue ) {
			$oValue			=	$live_site . '/' . $oValue;
		}
		return $oValue;
	}
	/**
	 * Replaces [fieldname] by the content of the user row (except for [password])
	 *
	 * @param  string         $msg
	 * @param  boolean|array  $htmlspecialchars  on replaced values only: FALSE : no htmlspecialchars, TRUE: do htmlspecialchars, ARRAY: callback method
	 * @param  boolean        $menuStats
	 * @param  array          $extraStrings
	 * @param  boolean        $translateLanguage  on $msg only
	 * @return string
	 */
	function replaceUserVars( $msg, $htmlspecialchars = true, $menuStats = true, $extraStrings = null, $translateLanguage = true ){
		if ( $extraStrings === null ) {
			$extraStrings	=	array();
		}
		if ( $translateLanguage ) {
			$msg	=	getLangDefinition( $msg );
		}
		if ( strpos( $msg, '[' ) === false ) {
			return $msg;
		}
		$row		=&	$this->_cbuser;

		$msg		=	$this->_evaluateIfs( $msg );
		$msg		=	$this->_evaluateCbTags( $msg );

		if ( is_object( $row ) ) {
		// old legacy modes:
			$array		=	get_object_vars( $row );
			foreach( $array AS $k => $v ) {
				if( ( ! is_object( $v ) ) && ( ! is_array( $v ) ) ) {
					if ( ! ( ( strtolower( $k ) == "password" ) && ( strlen($v) >= 32 ) ) ) {
						/* do not translate content ! :
						$vTranslated		=	( $translateLanguage ? getLangDefinition( $v ) : $v );
						if ( is_array( $htmlspecialchars ) ) {
							$vTranslated	=	call_user_func_array( $htmlspecialchars, array( $vTranslated ) );
						}
						$msg = cbstr_ireplace("[".$k."]", $htmlspecialchars === true ? htmlspecialchars( $vTranslated ) : $vTranslated, $msg );
						*/
						if ( is_array( $htmlspecialchars ) ) {
							$v	=	call_user_func_array( $htmlspecialchars, array( $v ) );
						}
						$msg	=	cbstr_ireplace("[".$k."]", $htmlspecialchars === true ? htmlspecialchars( $v ) : $v, $msg );
					}
				}
			}
		}
		foreach( $extraStrings AS $k => $v) {
			if( ( ! is_object( $v ) ) && ( ! is_array( $v ) ) ) {
				/* do not translate content ! :
				$vTranslated			=	( $translateLanguage ? getLangDefinition( $v ) : $v );
				if ( is_array( $htmlspecialchars ) ) {
					$vTranslated		=	call_user_func_array( $htmlspecialchars, array( $vTranslated ) );
				}
				$msg = cbstr_ireplace("[".$k."]", $htmlspecialchars === true ? htmlspecialchars( $vTranslated ) : $vTranslated, $msg );
				*/
				if ( is_array( $htmlspecialchars ) ) {
					$v		=	call_user_func_array( $htmlspecialchars, array( $v ) );
				}
				$msg		=	cbstr_ireplace("[".$k."]", $htmlspecialchars === true ? htmlspecialchars( $v ) : $v, $msg );
			}
		}
		if ( $menuStats ) {
			// find [menu .... : path1:path2:path3 /] and replace with HTML code if menu active, otherwise remove it all
			$msg = $this->_replacePragma( $msg, $row, 'menu', 'menuBar' );
			// no more [status ] as they are standard fields !		$msg = $this->_replacePragma( $msg, $row, 'status', 'menuList' );
		}
		$msg = str_replace( array( "&91;", "&93;" ), array( "[", "]" ), $msg );
		return $msg;
	}

	/**
	 * INTERNAL PRIVATE METHODS:
	 */

	/**
	 * Explodes a text like: href="text1" img="text'it" alt='alt"joe'   into an array with defined keys and values, but null for missing ones.
	 * @access private
	 *
	 * @param string $text	text to parse
	 * @param array of string $validTags	valid tag names
	 * @return array of string	array( "tagname" => "tagvalue", "notsetTagname" => null)
	 */
	function _explodeTags( $text, $validTags ) {
		$text = trim($text);
		$result = array();
		foreach ($validTags as $tagName) {
			$result[$tagName] = null;
		}
		while ( $text != "" ) {
			$posEqual = strpos( $text, "=" );
			if ( $posEqual !== false ) {
				$tagName	= trim( substr( $text, 0, $posEqual ) );
				$text		= trim( substr( $text, $posEqual + 1 ) );
				$quoteMark	= substr( $text, 0, 1);
				$posEndQuote	= strpos( $text, $quoteMark, 1 );
				$tagValue	= false;
				if ( ($posEndQuote !== false) && in_array( $quoteMark, array( "'", '"' ) ) ) {
					$tagValue	= substr( $text, 1, $posEndQuote - 1 );
					$text		= trim( substr( $text, $posEndQuote + 1 ) );
					if ( in_array( $tagName, $validTags ) ) {
						$result[$tagName] = $tagValue;
					}
				} else {
					break;
				}
			} else {
				break;
			}
		}
		return $result;
	}
	/**
	 * Replaces "$1" in $text with $cbMenuTagsArray[$cbMenuTagsArrayKey] if non-null but doesn't tag if empty
	 * otherwise replace by $cbMenu[$cbMenuKey] if set and non-empty
	 * @access private
	 *
	 * @param array of string	$cbMenuTagsArray
	 * @param string			$cbMenuTagsArrayKey
	 * @param array of string	$cbMenu
	 * @param string			$cbMenuKey
	 * @param string			$text
	 * @return string
	 */
	function _placeTags( $cbMenuTagsArray, $cbMenuTagsArrayKey, $cbMenu, $cbMenuKey, $text ) {
		if ( $cbMenuTagsArray[$cbMenuTagsArrayKey] !== null) {
			if ( $cbMenuTagsArray[$cbMenuTagsArrayKey] != "" ) {
				return str_replace( '$1', /*allow tags! htmlspecialchars */ ( $cbMenuTagsArray[$cbMenuTagsArrayKey] ), $text );
			} else {
				return null;
			}
		} elseif ( isset($cbMenu[$cbMenuKey]) && ( $cbMenu[$cbMenuKey] !== null ) && ( $cbMenu[$cbMenuKey] !== "" ) ) {
			return str_replace( '$1', $cbMenu[$cbMenuKey], $text );
		} else {
			return null;
		}
	}
	/**
	 * Replaces complex pragmas
	 *
	 * @param  string    $msg
	 * @param  stdClass  $row
	 * @param  string    $pragma           the tag between the brackets "[$pragma]"
	 * @param  string    $position       the CB menu position
	 * @param  boolean   $htmlspecialcharsEncoded  True if menu tags should remain htmlspecialchared
	 * @return unknown
	 */
	function _replacePragma( $msg, $row, $pragma, $position, $htmlspecialcharsEncoded = true ) {
		global $_PLUGINS;

		$msgResult = "";
		$pragmaLen = strlen( $pragma );
	    while ( ( $foundPosBegin = strpos( $msg, "[" . $pragma ) ) !== false ) {
	   		$foundPosEnd = strpos( $msg, "[/" . $pragma . "]", $foundPosBegin + $pragmaLen + 1 );
			if ( $foundPosEnd !== false ) {
				$foundPosTagEnd = strpos( $msg, "]", $foundPosBegin + $pragmaLen + 1 );
				if ( ( $foundPosTagEnd !== false ) && ( $foundPosTagEnd < $foundPosEnd ) ) {
					// found [menu .... : $cbMenuTreePath /] : check to see if $cbMenuTreePath is in current menu:
			    	$cbMenuTreePath = substr( $msg, $foundPosTagEnd + 1, $foundPosEnd - ($foundPosTagEnd + 1) );
			    	$cbMenuTreePathArray = explode( ":", $cbMenuTreePath );
		    		$pm = $_PLUGINS->getMenus();
		    		$pmc=count($pm);
					for ( $i=0; $i<$pmc; $i++ ) {
						if ( $pm[$i]['position'] == $position ) {
							$arrayPos = $pm[$i]['arrayPos'];
							foreach ( $cbMenuTreePathArray as $menuName ) {
								if ( key( $arrayPos ) == trim( $menuName ) ) {
									$arrayPos = $arrayPos[key( $arrayPos )];
								} else {
									// not matching full menu path: check next:
									break;
								}
							}
							if ( !is_array( $arrayPos ) ) {
								// came to end of path: match found: stop searching:
								break;
							}
						}
					}
					// replace by nothing in case not found:
					$replaceString = "";
					if ( $i < $pmc ) {
						// found: replace with menu item: first check for qualifiers for special changes:
			    		$cbMenuTags = substr( $msg, $foundPosBegin + $pragmaLen + 1, $foundPosTagEnd - ($foundPosBegin + $pragmaLen + 1) );
			    		if ($htmlspecialcharsEncoded) {
			    			$cbMenuTags = cbUnHtmlspecialchars( $cbMenuTags );
			    		}
						$cbMenuTagsArray = $this->_explodeTags( $cbMenuTags, array( "href", "target", "title", "class", "style", "img", "caption") );
						if (substr(ltrim( $pm[$i]['url'] ),0,2) == '<a') {
							$matches			=	null;
							if ( preg_match( '/ href="([^"]+)"/i', $pm[$i]['url'], $matches ) ) {
								$pm[$i]['url']	=	$matches[1];
							}
						}
						$replaceString .= $this->_placeTags( $cbMenuTagsArray, 'href', $pm[$i], 'url', '<a href="$1"'
													. $this->_placeTags( $cbMenuTagsArray, 'target', $pm[$i], 'target', ' target="$1"' )
													. $this->_placeTags( $cbMenuTagsArray, 'title', $pm[$i], 'tooltip', ' title="$1"' )
													. $this->_placeTags( $cbMenuTagsArray, 'class', $pm[$i], 'undef', ' class="$1"' )
													. $this->_placeTags( $cbMenuTagsArray, 'style', $pm[$i], 'undef', ' style="$1"' )
													. ">"
												  );
						$replaceString .= $this->_placeTags( $cbMenuTagsArray, 'img', $pm[$i], 'img', '$1' );
						$replaceString .= $this->_placeTags( $cbMenuTagsArray, 'caption', $pm[$i], 'caption', '$1' );
						$replaceString .= $this->_placeTags( $cbMenuTagsArray, 'href', $pm[$i], 'url', '</a>' );

								/*	$this->menuBar->addObjectItem( $pm[$i]['arrayPos'], $pm[$i]['caption'],
									isset($pm[$i]['url'])	?$pm[$i]['url']		:"",
									isset($pm[$i]['target'])?$pm[$i]['target']	:"",
									isset($pm[$i]['img'])	?$pm[$i]['img']		:null,
									isset($pm[$i]['alt'])	?$pm[$i]['alt']		:null,
									isset($pm[$i]['tooltip'])?$pm[$i]['tooltip']:null,
									isset($pm[$i]['keystroke'])?$pm[$i]['keystroke']:null );
								*/
					}
					$msgResult .= substr( $msg, 0, $foundPosBegin );
					$msgResult .= $replaceString;
					$msg		= substr( $msg, $foundPosEnd + $pragmaLen + 3 );
			//        $srchtxt = "[menu:".$cbMenuTreePath."]";    // get new search text
			//        $msg = str_replace($srchtxt,$replaceString,$msg);    // replace founded case insensitive search text with $replace
				} else {
					break;
				}
	    	} else {
	    		break;
	    	}
	    }
	   	return $msgResult . $msg;
	}

	function & _evaluateUserAttrib( $userAttrVal ) {
		global $_CB_framework;

		if ( $userAttrVal !== '' ) {
			$uid			=	null;
			if ( ( $userAttrVal == '#displayed' ) || ( $userAttrVal == '#displayedOrMe' ) ) {
				$uid		=	$_CB_framework->displayedUser();
			}
			if ( ( $uid === null ) && ( ( $userAttrVal == '#displayedOrMe' ) || ( $userAttrVal == '#me' ) ) ) {
				$uid		=	$_CB_framework->myId();
			}
			if ( ( $uid === null ) && preg_match( '/^[1-9][0-9]*$/', $userAttrVal ) ) {
				$uid		=	(int) $userAttrVal;
			}
			if ( $uid ) {
				if ( $uid == $this->_cbuser->id ) {
					$user	=&	$this;
				} else {
					$user	=&	CBuser::getInstance( (int) $uid );
				}
			} else {
				$user		=	null;
			}

		} else {
			$user			=&	$this;
		}
		return $user;
	}

	function _evaluateIfs( $input ) {
//		$regex		=	"#\[if ([^\]]+)\](.*?)\[/if\]#s";
//		$regex = '#\[indent]((?:[^[]|\[(?!/?indent])|(?R))+)\[/indent]#s';
		$regex = '#\[cb:if(?: +user="([^"/\[\] ]+)")?( +[^\]]+)\]((?:[^\[]|\[(?!/?cb:if[^\]]*])|(?R))+)\[/cb:if]#';
		if ( is_array( $input ) ) {
			$regex2					=	'# +(?:(&&|and|\|\||or|) +)?([^=<!>~ ]+) *(=|<|>|>=|<=|<>|!=|=~|!~) *"([^"]*)"#';
			$conditions				=	null;
			if (preg_match_all( $regex2, $input[2], $conditions ) ) {
				$user				=&	$this->_evaluateUserAttrib( $input[1] );
				if ( ( $user !== null ) || ( ( count( $conditions[0] ) == 1 ) && ( $conditions[2][0] == 'user_id' ) && ( $conditions[4][0] === '0' ) ) ) {
					$resultsIdx		=	0;
					$results		=	array( $resultsIdx => true );
					for ( $i = 0, $n = count( $conditions[0] ); $i < $n; $i++ ) {
						$operator	=	$conditions[1][$i];
						$field		=	$conditions[2][$i];
						$compare	=	$conditions[3][$i];
						$value		=	$conditions[4][$i];
						if ( $user === null ) {
							$var	=	'0';
						} elseif ( $field && isset( $user->_cbuser->$field ) ) {
							$var	=	$user->_cbuser->$field;
						} else {
							$var	=	null;
						}
						if ( ( $field == 'user_id' ) && ( $value == 'myid' ) ) {
							global $_CB_framework;
							$value	=	$_CB_framework->myId();
						}
						switch ( $compare ) {
							case '=':
								$r	=	( $var == $value );
								break;
							case '<':
								$r	=	( $var < $value );
								break;
							case '>':
								$r	=	( $var > $value );
								break;
							case '>=':
								$r	=	( $var >= $value );
								break;
							case '<=':
								$r	=	( $var <= $value );
								break;
							case '<>':
							case '!=':
								$r	=	( $var != $value );
								break;
							case '=~':
							case '!~':
								$ma	=	@preg_match( $value, $var );
								$r	=	( $compare == '=~' ? ( $ma === 1 ) : ( $ma == 0 ) );
								if ( $ma === false ) {
									// error in regexp itself:
									global $_CB_framework;
									if ( $_CB_framework->getCfg( 'debug' ) > 0 ) {
										echo sprintf( CBTxt::T("CB Regexp Error %s in expression %s"), ( ( ! is_callable( 'preg_last_error' ) ) ? '' : preg_last_error() ), htmlspecialchars( $value ) );
									}
								}
								break;
						}
						if ( in_array( $operator, array( 'or', '||' ) ) ) {
							$resultsIdx++;
							$results[++$resultsIdx]	=	true;
						}
						// combine and:
						$results[$resultsIdx]	=	$results[$resultsIdx] && $r;
					}
					// combine or:
					$r				=	false;
					foreach ( $results as $rr ) {
						$r			=	$r || $rr;
					}
					$input		=	( $r ? $input[3] : '' );
				} else {
					$input		=	'';
				}
			} else {
				$input		=	'';
			}
		}
		return preg_replace_callback( $regex, array( $this, '_evaluateIfs' ), $input );
	}

	function _evaluateCbTags( $input ) {
		global $_CB_framework;

		$regex				=	'#\[cb:(userdata +field|userfield +field|usertab +tab|userposition +position|date +format|url +location|config +param)="((?:[^"]|\\\\")+)"(?: +user="([^"/\] ]+)")?(?: +default="((?:[^"]|\\\\")+)")?(?: +output="([a-zA-Z]+)")?(?: +formatting="([a-zA-Z]+)")?(?: +reason="([a-zA-Z]+)")?(?: +list="([0-9]+)")? */\]#';
		if ( is_array( $input ) ) {
			if ( isset( $input[3] ) ) {
				$user		=&	$this->_evaluateUserAttrib( $input[3] );
			} else {
				$user		=&	$this;
			}
			if ( ( $user !== null ) && is_object( $user->_cbuser ) && isset( $user->_cbuser->id ) ) {
				$type		=	array_shift( explode( ' ', $input[1] ) );

				switch ( $type ) {
					case 'userdata':
						return $user->_cbuser->get( $input[2] );
						break;
					case 'userfield':
					case 'usertab':
						$default		=	( isset( $input[4] ) ? CBTxt::T( str_replace( '\"', '"', $input[4] ) ) : null );
						$output			=	( isset( $input[5] ) ? ( $input[5] !== '' ? $input[5] : 'html' ) : 'html' );
						$formatting		=	( isset( $input[6] ) ? ( $input[6] !== '' ? $input[6] : 'none' ) : 'none' );
						$reason			=	( isset( $input[7] ) ? ( $input[7] !== '' ? $input[7] : 'profile' ) : 'profile' );
						if ( $type == 'userfield' ) {
							return $user->getField( $input[2], $default, $output, $formatting, $reason );
						} else {
							return $user->getTab( $input[2], $default, ( $output == 'none' ? null : $output ), $formatting, $reason );
						}
						break;
					case 'userposition':
						return $user->getPosition( $input[2] );
						break;
					case 'date':
						return date( $input[2], $_CB_framework->now() );
						break;
					case 'url':
						switch ( $input[2] ) {
							case 'login':
							case 'logout':
							case 'registers':
							case 'lostpassword':
							case 'manageconnections':
								return $_CB_framework->viewUrl( $input[2], false );
								break;
							case 'profile_view':
								return $_CB_framework->userProfileUrl( $user->_cbuser->id, false );
								break;
							case 'profile_edit':
								return $_CB_framework->userProfileEditUrl( $user->_cbuser->id, false );
								break;
							case 'list':
								$list		=	( isset( $input[8] ) ? ( $input[8] !== '' ? $input[8] : null ) : null );
								return $_CB_framework->userProfilesListUrl( $list, false );
								break;
							case 'itemid':
								return getCBprofileItemid( false );
								break;
							default:
								return '';
						}
						break;
					case 'config':
						switch ( $input[2] ) {
							case 'live_site':
							case 'sitename':
							case 'lang':
							case 'lang_name':
							case 'lang_tag':
								return $_CB_framework->getCfg( $input[2] );
								break;
							default:
								return '';
						}
						break;
					default:
						return '';
				}
			}
			return '';
		}
		return preg_replace_callback( $regex, array( $this, '_evaluateCbTags' ), $input );
	}
}
/**
 * CB HTML document class for Mambo 4.5.2+
 * This class is experimental and not part as is of CB 1.2 !
 * Use only $_CB_framework->document to access its public functions
 * @author Beat
 * @license GPL v2
 */
class CBdocumentHtml {
	var $_output					=	'html';
	var $_head;
	var $_cmsDoc					=	null;
	var $_headsOutputed				=	true;
	var $_direction					=	null;
	/**
	 * Constructor
	 * @access private
	 *
	 * @param  callHandler      $getDocFunction
	 * @return CBdocumentHtml
	 */
	function CBdocumentHtml( &$getDocFunction ) {
		if ( $getDocFunction ) {
			$this->_cmsDoc			=	call_user_func_array( $getDocFunction, array() );
		}
		$this->_renderingInit();
	}
	/**
	 * Sets a cms doc object for headers
	 *
	 * @param  object  $cmsDoc
	 */
	function setCmsDoc( $cmsDoc = null ) {
		$this->_cmsDoc				=	$cmsDoc;
	}
	/**
	 * Sets or alters a meta tag.
	 *
	 * @param  string  $name        MUST BE LOWERCASE: Name or http-equiv tag: 'generator', 'description', ...
	 * @param  string  $content     Content tag value
	 * @param  boolean $http_equiv  META type "http-equiv" defaults to null
	 */
	function addHeadMetaData( $name, $content, $http_equiv = false ) {
		if ( ! $this->_tryCmsDoc( 'setMetaData', array( $name, $content, $http_equiv ) ) ) {
			if ( $http_equiv ) {
				$metaTag	=	array( 'http-equiv' => $name, 'content' => $content );
			} else {
				$metaTag	=	array( 'name' => $name, 'content' => $content );
			}

			$this->_head['metaTags'][$http_equiv][$name]	=	$metaTag;
			$this->_renderCheckOutput();
		}
	}
	/**
	 * Adds <link $relType="$relation" href="$url" associativeImplode($attribs) />
	 *
	 * @param  string  $url       Href URL to the linked style sheet
	 * @param  string  $relation  Relation to link
	 * @param  string  $relType   'rel' (default) for forward, or 'rev' for reverse relation
	 * @param  array   $attribs   Additional attributes ( 'attrName' => 'attrValue' )
	 */
	function addHeadLinkCustom( $url, $relation, $relType = 'rel', $attribs = null ) {
		static $i		=	0;
		if ( $attribs === null ) {
			$attribs	=	array();
		}
		if ( ! $this->_tryCmsDoc( 'addHeadLink', array( $url, $relation, $relType, $attribs ) ) ) {
			$this->_head['linksCustom']['link'][$i]		=	array( $relType => $relation, 'href' => $url );
			if ( count( $attribs ) > 0 ) {
				$this->_head['linksCustom']['link'][$i]	=	array_merge( $this->_head['linksCustom']['link'][$i], $attribs );
			}
			$i			+=	1;
			$this->_renderCheckOutput();
		}
	}
	/**
	 * Adds <link type="$type" rel="stylesheet" href="$url" media="$media" />
	 *
	 * @param  string  $url    Href URL to the linked style sheet (either full url, or if starting with '/', live_site will be prepended)
	 * @param  boolean $minVersion  If a minified version ".min.css" exists, will use that one when not debugging
	 * @param  string  $media  Media type for stylesheet
	 * @param  array   $attribs   Additional attributes ( 'attrName' => 'attrValue' )
	 * @param  string  $type   MUST BE LOWERCASE: Mime type ('text/css' by default)
	 */
	function addHeadStyleSheet( $url, $minVersion = false, $media = null, $attribs = null, $type = 'text/css' ) {
		global $_CB_framework;

		if ( $attribs === null ) {
			$attribs	=	array();
		}
		if ( $url[0] == '/' ) {
			if ( $_CB_framework->getUi() == 2 ) {
				$url	=	'..' . $url;		// relative paths in backend
			} else {
				$url	=	$_CB_framework->getCfg( 'live_site' ) . $url;
			}
		}
		if ( ! $this->_tryCmsDoc( 'addStyleSheet', array( $url, $type, $media, $attribs ) ) ) {
			$this->_head['stylesheets'][$url]	=	array( 'type' => $type, 'rel' => 'stylesheet', 'href' => $url );
			if ( $media ) {
				$this->_head['stylesheets'][$url]['media']		=	$media;
			}
			if ( count( $attribs ) > 0 ) {
				$this->_head['stylesheets'][$url]	=	array_merge( $this->_head['stylesheets'][$url], $attribs );
			}
			$this->_renderCheckOutput();
		}
	}
	 /**
	 * Adds <style type="$type">$content</style>
	 *
	 * @param	string  $content   Style declarations
	 * @param	string  $type		Type of stylesheet (defaults to 'text/css')
	 * @return   void
	 */
	function addHeadStyleInline( $content, $type = 'text/css' ) {
		if ( ! $this->_tryCmsDoc( 'addStyleDeclaration', array( $content, $type ) ) ) {
			$this->_head['styles'][$type][]	=	$content;
			$this->_renderCheckOutput();
		}
	}
	 /**
	 * Adds <script type="$type" src="$url"></script>
	 *
	 * @param  string        $url           Src of script (either full url, or if starting with '/', live_site will be prepended) DO htmlspecialchars BEFORE calling if needed (&->&amp;)
	 * @param  boolean       $minVersion    Minified version exist, named .min.js
	 * @param  string        $preScript     Script that must be just before the file inclusion
	 * @param  string        $postScript    Script that must be just after the file
	 * @param  string        $preCustom     Any html code just before the scripts incl. pre
	 * @param  string        $postCustom    Any html code just after the scripts incl. post
	 * @param  string|array  $type          String: type="$type" : MUST BE LOWERCASE: Type of script ('text/javascript' by default), Array: e.g. array( 'type' => 'text/javascript', 'charset' => 'utf-8' )
	 */
	function addHeadScriptUrl( $url, $minVersion = false, $preScript = null, $postScript = null, $preCustom = null, $postCustom = null, $type = 'text/javascript' ) {
		global $_CB_framework;

		if ( $minVersion && ! $_CB_framework->getCfg( 'debug' ) ) {
			$url		=	str_replace( '.js', '.min.js', $url );
		}
		if ( $url[0] == '/' ) {
			if ( $_CB_framework->getUi() == 2 ) {
				$url	=	'..' . $url;		// relative paths in backend
			} else {
				$url	=	$_CB_framework->getCfg( 'live_site' ) . $url;
			}
		}
//		if ( ! $this->_tryCmsDoc( 'addScript', array( $url, $type ) ) ) {							// The core ones are broken as they do not keep the strict ordering of scripts
			$this->_head['scriptsUrl'][$url]		=	array( 'pre' => $preScript, 'post' => $postScript, 'preC' => $preCustom, 'postC' => $postCustom, 'type' => $type );
			$this->_renderCheckOutput();
//		}
	}
	/**
	 * Adds <script type="$type">$content</script>
	 *
	 * @param  string  $content  Script
	 * @param  string  $type     MUST BE LOWERCASE: Mime type ('text/javascript' by default)
	 */
	function addHeadScriptDeclaration( $content, $type = 'text/javascript' ) {
//		if ( ! $this->_tryCmsDoc( 'addScriptDeclaration', array( $content, $type ) ) ) {			// The core ones are broken as they do not keep the strict ordering of scripts
			$this->_head['scripts'][$type][]		=	$content;
			$this->_renderCheckOutput();
//		}
	}
	/**
	 * Adds custom $html into <head> portion
	 *
	 * @param  string  $html
	 */
	function addHeadCustomHtml( $html ) {
//		if ( ! $this->_tryCmsDoc( 'addCustomTag', array( $html ) ) ) {							// The core ones are broken as they do not keep the strict ordering of scripts
			$this->_head['custom'][]				=	$html;
			$this->_renderCheckOutput();
//		}
	}
	 /**
	 * Returns direction 'ltr' or 'rtl' for global document
	 *
	 * @return   string  'ltr' for left-to-right or 'rtl' for right-to-left texts globally on the page
	 */
	function getDirection( ) {
		if ( $this->_direction === null ) {
			if ( $this->_cmsDoc ) {
				$this->_direction	=	call_user_func_array( array( $this->_cmsDoc, 'getDirection' ), array() );
			} else {
				$this->_direction	=	'ltr';
			}
		}
		return $this->_direction;
	}
	 /**
	 * Sets direction 'ltr' or 'rtl' for global document
	 *
	 * @param  string  $textDirection  'ltr' for left-to-right or 'rtl' for right-to-left texts globally on the page
	 */
	function setDirection( $textDirection = 'ltr' ) {
		$this->_direction			=	$textDirection;
	}
	/**
	 * Tries to add head tags to CMS document.
	 * @access private
	 *
	 * @param  string   $type
	 * @param  array    $params
	 * @return boolean           Returns true for success and false if it couldn't use.
	 */
	function _tryCmsDoc( $type, $params ) {
		if ( $this->_cmsDoc ) {
			call_user_func_array( array( $this->_cmsDoc, $type ), $params );
			return true;
		} elseif ( $this->_cmsDoc === false ) {
			// no html headers to output: do as if outputed so they get ignored:
			return true;
		}
		return false;
	}
	function _outputToHeadCollectionStart( ) {
		$this->_headsOutputed		=	false;
	}
	/**
	 * Outputs the headers to the CMS handler or returns them if it can't
	 * @access private
	 *
	 * @return string|null   string for header to be echoed worst case, null if it could echo
	 */
	function _outputToHead( ) {
		global $_CB_framework, $ueConfig;

		$jVersion		=	checkJversion();
		$customHead		=	$this->_renderHead();
		if ( $this->_headsOutputed && ( $jVersion == 0 ) ) {
			return $customHead . "\n";
		}
		if ( ! $this->_tryCmsDoc( 'addCustomTag', array( $customHead ) ) ) {
			if ( isset( $ueConfig['xhtmlComply'] ) && $ueConfig['xhtmlComply']
				&& ( ( ( $_CB_framework->getUi() == 1 ) || ( ( $jVersion == 0 ) && function_exists( 'josHashPassword' ) ) ) && method_exists( $_CB_framework->_baseFramework, 'addCustomHeadTag' ) ) )
			{
				// versions 1.0.13 (in fact 1.0.12 too) and above have it in backend too:
				$_CB_framework->_baseFramework->addCustomHeadTag( $customHead );
			} else {
				return $customHead . "\n";
			}
		}
		$this->_headsOutputed	=	true;
		return null;
	}
	function _renderCheckOutput( ) {
		if ( $this->_headsOutputed && ( $this->_cmsDoc !== false ) ) {
//			$customHead			=	$this->_renderHead();
//			echo $customHead;		// better late than never...
			echo $this->_outputToHead();
		}
	}
	function _renderingInit() {
		$this->_head				=	array( 'metaTags' => array(), 'linksCustom' => array(), 'stylesheets' => array(), 'styles' => array(), 'scriptsUrl' => array(), 'scripts' => array(), 'custom' => array() );
	}
	/**
	 * Renders the portion going into the <head> if CMS doesn't support correct ordering
	 * @access private
	 *
	 * @return string    HTML for <head> or NULL if done by CMS
	 */
	function _renderHead( ) {
		$html					=	null;
		if ( $this->_output == 'html' ) {
			if ( $this->_cmsDoc === null ) {
				// <base> is done outside
				// metaTags:
				foreach ( $this->_head['metaTags'] as $namContentArray ) {
					foreach ( $namContentArray as $metaTagAttrs ) {
						$html[]	=	$this->_renderTag( 'meta', $metaTagAttrs );
					}
				}
				// <title> is done outside
				// links, custom ones:
				foreach ( $this->_head['linksCustom'] as $tagName => $attributes ) {
					$html[]		=	$this->_renderTag( $tagName, $attributes );
				}
				// styleSheets first:
				foreach ( $this->_head['stylesheets'] as $url => $styleSheet ) {
					$html[]		=	$this->_renderTag( 'link', $styleSheet );
				}
				// style inline:
				$html[]			=	$this->_renderInlineHelper( 'style', 'styles' );
			}
			// The core SCRIPT handlers are broken as they do not keep the strict ordering of scripts: so do it here as custom:
			// scriptsUrl:
			foreach ( $this->_head['scriptsUrl'] as $url => $tpp ) {
				$html[]			=	$tpp['preC']
								.	$this->_renderInlineScript( $tpp['pre'] )
								.	$this->_renderScriptUrlTag( $url, $tpp['type'] )
								.	$this->_renderInlineScript( $tpp['post'] )
								.	$tpp['postC']
								;
			}
			// scripts inline
			$html[]				=	$this->_renderInlineHelper( 'script', 'scripts' );
			;
			// if there are custom things:
			foreach ( $this->_head['custom'] as $custom ) {
				$html[]			=	$custom;
			}
		}
		// reset the headers, in case we get late callers from outside the component (modules):
		$this->_renderingInit();
		// finally transform to a string:
		return implode( "\n\t", $html );
	}
	/**
	 * Internal utility to render <$tag implode($attributes) />
	 * (NOT PART of CB API)
	 * @access private
	 *
	 * @param  string  $tag
	 * @param  array   $attributes
	 * @param  string  $tagClose    '/>' (default) or '>'
	 * @return string
	 */
	function _renderTag( $tag, $attributes, $tagClose = '/>' ) {
		$html				=	'<' . $tag .' ';
		foreach ( $attributes as $attr => $val ) {
			$html			.= ' ' . $attr . '="' . $val . '"';
		}
		$html				.=	$tagClose;
		return $html;
	}
	/**
	 * Internal utility to render <script type="$type" src="$url"></script>
	 * (NOT PART of CB API)
	 * @access private
	 *
	 * @param  string  $url
	 * @param  array   $type
	 * @return string
	 */
	function _renderScriptUrlTag( $url, $type ) {
		if ( is_string( $type ) ) {
			return '<script type="' . $type . '" src="' . $url . '"></script>';
		} else {
			$type['src']	=	$url;
			return $this->_renderTag( 'script', $type, '>' ) . '</script>';
		}

	}
	/**
	 * Internal utility to render <$tag type="$type"><!-- implode($attributes) --></$tag>
	 * (NOT PART of CB API)
	 * @access private
	 *
	 * @param  string  $tag
	 * @param  string  $content
	 * @param  string  $type
	 * @return string
	 */
	function _renderInlineScript( $content, $tag = 'script', $type = 'text/javascript' ) {
		if ( $content ) {
			return '<' . $tag . ' type="' . $type . '">'
			.	( $this->_output == 'html' ? "<!--\n" : "<![CDATA[\n" )
			.	$content
			.	( $this->_output == 'html' ? "\n-->" : "\n]]>" )
			.	'</' . $tag . '>'
			;
		}
		return null;
	}
	/**
	 * Internal utility to render an inline head portion (<style> or <script>)
	 * @access private
	 *
	 * @param  string  $tag  <$tag
	 * @param  string $head  index in $this->_head[$head] as array( $type => array( $contents ) )
	 * @return string        HTML
	 */
	function _renderInlineHelper( $tag, $head ) {
		$html				=	null;
		foreach ( $this->_head[$head] as $type => $contentsArray ) {
			$html[]			=	$this->_renderInlineScript( implode( "\n\n", $contentsArray ), $tag, $type );
		}
		if ( $html !== null ) {
			return implode( "\n\t", $html );
		}
		return null;
	}
}	// class CBdocumentHtml
/**
 * CB Framework class for Mambo 4.5.2+
 * @author Beat
 * @license GPL v2
 */
class CBframework {
	/** Base framework class
	 * @var mosMainFrame */
	var $_baseFramework;
	var $_cmsDatabase;
	var $_ui						=	1;
	var $_now;
	var $_myId;
	var $_myUsername;
	var $_myUserType;
	var $_myCmsGid;
	var $_myLanguage				=	null;
	var $_myLanguageTag				=	null;
	/** php gacl compatible instance:
	 * @var CBACL $acl */
	var $acl;
	var $_aclParams					=	array();
	var $_cmsSefFunction;
	var $_sefFuncHtmlEnt;
	var $_cmsUserClassName;
	var $_cmsUserNeedsDb;
	var $_cmsRedirectFunction;
	var $_cbUrlRouting;			//	= array( 'option' => 'com_comprofiler' )
	var $_getVarFunction;
	var $_outputCharset;
	var $_editorDisplay;

	var $_redirectUrl				=	null;
	var $_redirectMessage			=	null;
	var $_redirectMessageType		=	'message';
	/** php gacl instance:
	 * @var CBdocumentHtml */
	var $document;

	function CBframework( &$baseFramework, &$cmsDatabase, &$acl, &$aclParams, $cmsSefFunction, $sefFuncHtmlEnt, $cmsUserClassName, $cmsUserNeedsDb, $cmsRedirectFunction, $myId, $myUsername, $myUserType, $myCmsGid, $myLanguage, $myLanguageTag, $cbUrlRouting, $getVarFunction, &$getDocFunction, $outputCharset, $editorDisplay ) {
		$this->_baseFramework		=&	$baseFramework;
		$this->_cmsDatabase			=&	$cmsDatabase;
		// $this->acl					=&	$acl;
		$this->_aclParams			=&	$aclParams;
		$this->_cmsSefFunction		=	$cmsSefFunction;
		$this->_cmsUserClassName	=	$cmsUserClassName;
		$this->_cmsUserNeedsDb		=	$cmsUserNeedsDb;
		$this->_cmsRedirectFunction	=	$cmsRedirectFunction;
		$this->_myId				=	(int) $myId;
		$this->_myUsername			=	$myUsername;
		$this->_myUserType			=	$myUserType;
		$this->_myCmsGid			=	$myCmsGid;
		$this->_myLanguage			=	$myLanguage;
		$this->_myLanguageTag		=	$myLanguageTag;
		$this->_cbUrlRouting		=	$cbUrlRouting;
		$this->_getVarFunction		=	$getVarFunction;
		$this->_outputCharset		=	$outputCharset;
		$this->_editorDisplay		=	$editorDisplay;
		$this->_now					=	time();
		$this->document				=	new CBdocumentHtml( $getDocFunction );
	}
	/**
	 * User login into CMS framework
	 *
	 * @param  string          $username    The username
	 * @param  string|boolean  $password    if boolean FALSE: login without password if possible
	 * @param  booleean        $rememberme  1 for "remember-me" cookie method
	 * @param  int             $userId      used for "remember-me" login function only
	 * @return boolean                      Login success
	 */
	function login( $username, $password, $rememberme = 0, $userId = null ) {
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');              // needed for IE6 to accept this anti-spam cookie in higher security setting.

		if ( checkJversion() >= 1 ) {		// Joomla 1.5 RC and above:
			if ( $password !== false ) {
				$result				=	$this->_baseFramework->login( array( 'username' => $username, 'password' => $password ), array( 'remember' => $rememberme ) );
			} else {
				// login without password:
				jimport( 'joomla.user.authentication' );
				// load user plugins:
				JPluginHelper::importPlugin( 'user' );
				// get JAuthentication object:
				$authenticate		=&	JAuthentication::getInstance();
				$dispatcher			=&	JDispatcher::getInstance();
				$response			=	new JAuthenticationResponse();
				// prepare our SUCCESS login response including user data:
				global $_CB_database;
				$row				=	new moscomprofilerUser( $_CB_database );
				$row->loadByUsername( stripslashes( $username ) );
				$response->status	=	JAUTHENTICATE_STATUS_SUCCESS;
				$response->username	=	$username;
				$response->fullname	=	$row->name;
				// now we attempt user login and check results:
				if ( checkJversion() == 2 ) {
					$login			=	$dispatcher->trigger( 'onUserLogin', array( (array) $response, array( 'action' => 'core.login.site' ) ) );
				} else {
					$login			=	$dispatcher->trigger( 'onLoginUser', array( (array) $response, array() ) );
				}
				$result				=	! in_array( false, $login, true );
			}
			if ( $result ) {
				$user				=&	JFactory::getUser();
				$this->_myId		=	(int) $user->id;
				$this->_myUsername	=	$user->username;
				$this->_myUserType	=	$user->usertype;
				$this->_myCmsGid	=	$user->get('aid', 0);
				$lang				=&	JFactory::getLanguage();

				if ( checkJversion() == 2 ) {
					$this->_myLanguage	=	strtolower( preg_replace( '/^(\w+).*$/i', '\1', $lang->getName() ) );
				} else {
					$this->_myLanguage	=	$lang->getBackwardLang();
				}
			}
		} else {
			// Mambo 4.5.x and Joomla before 1.0.13+ (in fact RC3+) do need hashed password for login() method:
			if ( $password !== false ) {
				$hashedPwdLogin		=	( ( checkJversion() == 0 ) && ! function_exists( 'josHashPassword' ) );	// more reliable version-checking than the often hacked version.php file!
				if ( $hashedPwdLogin ) {				// Joomla 1.0.12 and below:
					$dummyRow		=	new moscomprofilerUser( $_CB_database );
					$this->_baseFramework->login( $username, $dummyRow->hashAndSaltPassword( $password ), $rememberme, $userId );
				} else {
					$this->_baseFramework->login( $username, $password, $rememberme, $userId );
				}

				// Joomla 1.0 redirects bluntly if login fails! so we need to check by ourselves below:
				$result				=	true;
			} else {
				// login without password:		//TBD MAMBO 4.6 support here !
				global $_CB_database, $mainframe, $_VERSION;

				$row				=	new moscomprofilerUser( $_CB_database );
				$row->loadByUsername( stripslashes( $username ) );

				// prepare login session with user data:
				$session			=&	$mainframe->_session;
				$session->guest		=	0;
				$session->username	=	$row->username;
				$session->userid	=	(int) $row->id;
				$session->usertype	=	$row->usertype;
				$session->gid		=	(int) $row->gid;

				// attempt to login user:
				if ( $session->update() ) {
					$result			=	true;
				}

				// check if site is demo or production:
				if ( $_VERSION->SITE ) {
					// site is production; remove duplicate sessions:
					$query			=	'DELETE FROM ' . $_CB_database->NameQuote( '#__session' )
									.	"\n WHERE " . $_CB_database->NameQuote( 'session_id' ) . ' != ' . $_CB_database->Quote( $session->session_id )
									.	"\n AND " . $_CB_database->NameQuote( 'username' ) . ' = ' . $_CB_database->Quote( $row->username )
									.	"\n AND " . $_CB_database->NameQuote( 'userid' ) . ' = ' . (int) $row->id
									.	"\n AND " . $_CB_database->NameQuote( 'gid' ) . ' = ' . (int) $row->gid
									.	"\n AND " . $_CB_database->NameQuote( 'guest' ) . ' = 0';
					$_CB_database->setQuery( $query );
					if ( ! $_CB_database->query() ) {
						trigger_error( 'loginUser 1 SQL error: ' . $_CB_database->stderr( true ), E_USER_WARNING );
					}
				}

				// get current datetime:
				$currentDate		=	date( 'Y-m-d H:i:s', $this->now() );

				// update user last login with current datetime:
				$query				=	'UPDATE ' . $_CB_database->NameQuote( '#__users' )
									.	"\n SET " . $_CB_database->NameQuote( 'lastvisitDate' ) . " = " . $_CB_database->Quote( $currentDate )
									.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " = " . (int) $session->userid;
				$_CB_database->setQuery( $query );
				if ( ! $_CB_database->query() ) {
					trigger_error( 'loginUser 2 SQL error: ' . $_CB_database->stderr( true ), E_USER_WARNING );
				}

				// clean old cache:
				mosCache::cleanCache();
			}
			if ( checkJversion() == 0 ) {
				global $mainframe;
				$mymy				=	$mainframe->getUser();
				$this->_myId		=	(int) $mymy->id;
				$this->_myUsername	=	$mymy->username;
				$this->_myUserType	=	$mymy->usertype;
				$this->_myCmsGid	=	$mymy->gid;
				if ( ! $this->_myId ) {
					$result			=	false;
				}
			}
			//TBD MAMBO 4.6 support here !
		}
		return $result;
	}
	function logout() {
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');              // needed for IE6 to accept this anti-spam cookie in higher security setting.
		$this->_baseFramework->logout();
	}
	function getCfg( $config ) {
		switch ( $config ) {
			case 'absolute_path':
				if ( checkJversion() >= 1 ) {
					return JPATH_SITE;
				}
				break;
			case 'live_site':
				if ( checkJversion() >= 1 ) {
					if ( $this->getUi() == 1 ) {
						$live_site	=	JURI::base();
					} elseif ( checkJversion() == 2 ) {
						$live_site	=	preg_replace( '%administrator/%', '', JURI::base() );
					} else {
						$live_site	=	$this->_baseFramework->getSiteURL();
					}
					if ( substr( $live_site, -1, 1 ) == '/' ) {
						// fix erroneous ending / in some joomla 1.5 versions:
						return substr( $live_site, 0, -1 );
					} else {
						return $live_site;
					}
				}
				break;
			case 'lang':
				return $this->_myLanguage;
				break;
			case 'lang_tag':
				return $this->_myLanguageTag;
				break;
			case 'uniquemail':
				if ( checkJversion() >= 1 ) {
					return '1';
				}
				break;
			case 'frontend_userparams':
				if ( checkJversion() == -1 ) {
					return '0';
				}
				// NO break; on purpose for fall-through:
			case 'allowUserRegistration':
			case 'useractivation':
			case 'new_usertype':
				if ( checkJversion() >= 1 ) {
					$usersConfig	=	&JComponentHelper::getParams( 'com_users' );
					$setting		=	$usersConfig->get( $config );
					if ( ( $config == 'new_usertype' ) && ( checkJversion() == 2 ) ) {
						$query		=	'SELECT ' . $this->_cmsDatabase->NameQuote( 'title' )
									.	"\n FROM " . $this->_cmsDatabase->NameQuote( '#__usergroups' )
									.	"\n WHERE " . $this->_cmsDatabase->NameQuote( 'id' ) . " = " . (int) $setting;
						$this->_cmsDatabase->setQuery( $query );
						$setting	=	$this->_cmsDatabase->loadResult();
					}
					if ( ( $config == 'new_usertype' ) && ! $setting ) {
						$setting	=	'Registered';
					}
					return $setting;
				} else {
					if ( $config == 'new_usertype' ) {
						return 'Registered';
					}
				}
				break;
			case 'hits':
			case 'vote':
				if ( checkJversion() >= 1 ) {
					$contentConfig	=	&JComponentHelper::getParams( 'com_content' );
					return $contentConfig->get( 'show_' . $config );
				}
				break;
			case 'dirperms':
			case 'fileperms':
				if ( checkJversion() >= 1 ) {
					return '';		//TBD: these two missing configs should one day go to CB
				}
				break;
			// CB-Specific config params:
			case 'tmp_path':
				$abs_path			=	$this->getCfg('absolute_path');
				$tmpDir				=	$abs_path . '/tmp';
				if ( @is_dir( $tmpDir ) && @is_writable( $tmpDir ) ) {
					return $tmpDir;
				}
				$tmpDir				=	$abs_path . '/media';
				if ( @is_dir( $tmpDir ) && @is_writable( $tmpDir ) ) {
					return $tmpDir;
				}
				// First try the new PHP 5.2.1+ function:
				if ( function_exists( 'sys_get_temp_dir' ) ) {
					$tmpDir		=	@sys_get_temp_dir();
					if ( @is_dir( $tmpDir ) && @is_writable( $tmpDir ) ) {
						return $tmpDir;
					}
				}
				// Based on http://www.phpit.net/article/creating-zip-tar-archives-dynamically-php/2/
				$varsToTry	=	array( 'TMP', 'TMPDIR', 'TEMP' );
				foreach ( $varsToTry as $v ) {
					if ( ! empty( $_ENV[$v] ) ) {
						$tmpDir		=	realpath( $v );
						if ( @is_dir( $tmpDir ) && @is_writable( $tmpDir ) ) {
							return $tmpDir;
						}
					}
				}
				// Try the CMS cache directory and other directories desperately:
				$tmpDirToTry		=	array( $this->getCfg( 'cachepath' ), realpath( '/tmp' ), $abs_path.'/tmp', $abs_path.'/images', $abs_path.'/images/stories', $abs_path.'/images/comprofiler' );
				foreach ( $tmpDirToTry as $tmpDir ) {
					if ( @is_dir( $tmpDir ) && @is_writable( $tmpDir ) ) {
						return $tmpDir;
					}
				}
				return null;
				break;
			case 'offset':
				if ( checkJversion() == 2 ) {
					static $jOffset			=	null;
					if ( $jOffset === null ) {
						$dateTimeZoneUTC		=	new DateTimeZone( 'UTC' );
						$dateTimeZoneCurrent	=	new DateTimeZone( $this->_baseFramework->getCfg( 'offset' ) );
						$dateTimeUTC			=	new DateTime( 'now', $dateTimeZoneUTC );
						$timeOffset				=	$dateTimeZoneCurrent->getOffset( $dateTimeUTC );
						$jOffset				=	$timeOffset / 3600;
					}
					return $jOffset;
				}
				break;
			default:
				break;
		}
		return $this->_baseFramework->getCfg( $config );

	}
	function getUi( ) {
		return $this->_ui;
	}
	function myId( ) {
		return $this->_myId;
	}
	function myUsername( ) {
		return $this->_myUsername;
	}
	function myUserType( ) {
		return $this->_myUserType;
	}
	function myCmsGid( ) {
		if ( ( checkJversion() == 0 ) && ( $this->getUi() == 2 ) ) {
			// joomla 1.0 backend has a bug:
			return 2;
		}
		return $this->_myCmsGid;
	}
	function _cms_all_acl( ) {
		return $this->_aclParams;
	}
	function _cms_acl( $action ) {
		if ( isset( $this->_aclParams[$action] ) ) {
			return $this->_aclParams[$action];
		}
		trigger_error( 'acl_check undefined', E_USER_ERROR );
		exit;
	}
	/**
	 * Checks rights of user $userType to perform a $action.
	 *
	 * @param  string  $action  'canEditUsers', 'canBlockUsers', 'canManageUsers', 'canReceiveAdminEmails','canInstallPlugins'
	 *                          'canEditOwnContent', 'canAddAllContent', 'canEditAllContent', 'canPublishContent'
	 * @param  string  $userTye
	 * @return boolean           TRUE: Yes, user can do that, FALSE: forbidden.
	 */
	function check_acl( $action, $userTye ) {
		$aclParams						=	$this->_cms_acl( $action );
		$aclParams[3]					=	$userTye;
		return ( true == call_user_func_array( array( $this->acl, 'acl_check' ), $aclParams ) );
	}
	function outputCharset( ) {
		return $this->_outputCharset;
	}
	function getUrlRoutingOfCb( ) {
		return $this->_cbUrlRouting;
	}
	function getRequestVar( $name, $default = null ) {
		if ( $this->_getVarFunction ) {
			return call_user_func_array( $this->_getVarFunction, array( $name, $default ) );
		} else {
			global $_REQUEST;
			return stripslashes( cbGetParam( $_REQUEST, $name, $default ) );
		}
	}
	function setRedirect( $url, $message = null, $messageType = 'message' ) {	// or 'error'
		$this->_redirectUrl				=	$url;
		$this->_redirectMessage			=	$message;
		$this->_redirectMessageType		=	$messageType;
	}
	function redirect( $url = null, $message = null, $messageType = null ) {
		if ( $url ) {
			$this->_redirectUrl			=	$url;
		}
		if ( $message !== null ) {
			$this->_redirectMessage		=	$message;
		}
		if ( $messageType !== null ) {
			$this->_redirectMessageType	=	$messageType;
		}
		call_user_func_array( $this->_cmsRedirectFunction, array( $this->_redirectUrl, $this->_redirectMessage, $this->_redirectMessageType ) );
	}
	/**
	 * changes "index.php?....." into what's needed for the CMS
	 * @since CB 1.2.3
	 *
	 * @param  string   $link       This URL should be htmlspecialchared already IF $htmlSpecials = TRUE, but NOT if = FALSE
	 * @param  boolean  $htmlSpecials
	 * @param  string   $format         'html', 'component', 'raw', 'rawrel' (same as 'raw' in backend for now)
	 * @return string
	 */
	function backendUrl( $link, $htmlSpecials = true, $format = 'html' ) {
		if ( checkJversion() >= 1 ) {
			// Joomla 1.5, 1.6:
			if ( $format == 'component' ) {
				$link					.=	( $htmlSpecials ? '&amp;' : '&' ) . 'tmpl=' . $format;
			}
			if ( $format == 'rawrel' ) {
				$format			=	'raw';
			}
			if ( $format == 'raw' ) {
				$link					.=	( $htmlSpecials ? '&amp;' : '&' ) . 'format=' . $format;
			}
		} else {
			// Mambo 4.5, 4.6, Joomla 1.0:
			if ( substr( $link, 0, 9 ) == 'index.php' ) {
				if ( $format == 'raw' ) {
					$link					=	'index3.php' . substr( $link, 9 )
											.	( $htmlSpecials ? '&amp;' : '&' ) . 'no_html=1'
											.	( $htmlSpecials ? '&amp;' : '&' ) . 'format=' . $format;
				} else {
					$link					=	'index2.php' . substr( $link, 9 );
				}
			}
		}
		return $link;
	}
	/**
	 * Converts an URL to an absolute URI with SEF format
	 *
	 * @param  string  $string        The relative URL
	 * @param  string  $htmlSpecials  TRUE (default): apply htmlspecialchars to sefed URL, FALSE: don't.
	 * @param  string  $format        'html', 'component', 'raw', 'rawrel'		(added in CB 1.2.3)
	 * @return string                 The absolute URL (relative if rawrel)
	 */
	function cbSef( $string, $htmlSpecials = true, $format = 'html' ) {
		if ( $format == 'html' ) {
			if ( ( $string == 'index.php' ) || ( $string == '' ) ) {
				$uri				=	$this->getCfg( 'live_site' ) . '/';
			} else {
				if ( ( $this->getUi() == 1 )
					 && ( ( substr( $string, 0, 9 ) == 'index.php' ) || ( $string[0] == '?' ) )
					 && is_callable( $this->_cmsSefFunction )
					 && ( ! ( ( checkJversion() == 0 ) && ( strpos( $string, '[' ) !== false ) ) ) )			// this is due to a bug in joomla 1.0 includes/sef.php line 426 and 501 not handling arrays at all.
				{
					$uri			=	call_user_func_array( $this->_cmsSefFunction, array( $this->_sefFuncHtmlEnt ? $string : cbUnHtmlspecialchars( $string ) ) );
				} else {
					$uri			=	$string;
				}
				if ( ! in_array( substr( $uri, 0, 4 ), array( 'http', 'java' ) ) ) {
					if ( ( strlen( $uri ) > 1 ) && ( $uri[0] == '/' ) ) {
						// we got special case of an absolute link without live_site, but an eventual subdirectory of live_site is included...need to strip live_site:
						$matches	=	array();
						if (	( preg_match( '!^([^:]+://)([^/]+)(/.*)$!', $this->getCfg( 'live_site' ), $matches ) )
							&&	( $matches[3] == substr( $uri, 0, strlen( $matches[3] ) ) ) )
						{
							$uri	=	$matches[1] . $matches[2] . $uri;		// 'http://' . 'site.com' . '/......
						} else {
							$uri	=	$this->getCfg( 'live_site' ) . $uri;
						}
					} else {
						$uri		=	$this->getCfg( 'live_site' ) . '/' . $uri;
					}
				}
			}
		} else /* if ( $format == 'raw' || $format == 'rawrel' || $format == 'component' ) */ {
			if ( substr( $string, 0, 9 ) == 'index.php' ) {
				if ( $format == 'rawrel' ) {
					$format			=	'raw';
					$uri			=	'';
				} else {
					$uri			=	$this->getCfg( 'live_site' ) . '/';
				}
				if ( checkJversion() >= 1 ) {
					// Joomla 1.5, 1.6:
					if ( $format == 'component' ) {
						$uri		.=	$string . '&amp;tmpl=' . $format;
					} else {
						$uri		.=	$string . '&amp;format=' . $format;
					}
				} else {
					// Mambo 4.5, 4.6, Joomla 1.0:
					$uri			.=	'index2.php' . substr( $string, 9 );
					if ( $format == 'component' ) {
						$uri		.=	'&amp;tmpl=' . $format;
					} else {
						$uri		.=	'&amp;no_html=1&amp;format=' . $format;
					}
				}
			} else {
				$uri				=	$string;
			}
		}
		if ( ! $htmlSpecials ) {
			$uri					=	cbUnHtmlspecialchars( $uri );
		} else {
			$uri					=	htmlspecialchars( cbUnHtmlspecialchars( $uri ) );	// quite a few sefs, including Mambo and Joomla's non-sef are buggy.
		}
		return $uri;
	}
	/**
	 * gets URL to view a profie
	 * @static
	 *
	 * @param  int     $userId        The user's id (if null, my profile)
	 * @param  string  $htmlSpecials  TRUE (default): apply htmlspecialchars to sefed URL, FALSE: don't.
	 * @param  string  $tab           The tab to open directly
	 * @param  string  $format        'html', 'component', 'raw', 'rawrel'   (since CB 1.2.3)
	 * @return string                 The absolute URL (relative if rawrel)
	 */
	function userProfileUrl( $userId = null, $htmlSpecials = true, $tab = null, $format = 'html' ) {
		if ( $userId && ( $userId == $this->myId() ) ) {
			$userId		=	null;
		}
		return $this->cbSef( 'index.php?option=com_comprofiler' . ( $userId ? '&task=userprofile&user=' . (int) $userId : '' ) . ( $tab ? '&tab=' . urlencode( $tab ) : '' ) . getCBprofileItemid( false ), $htmlSpecials, $format );
	}
	/**
	 * gets URL to edit a profie
	 * @static
	 * @since CB 1.2.3
	 *
	 * @param  int     $userId        The user's id (if null, my profile)
	 * @param  string  $htmlSpecials  TRUE (default): apply htmlspecialchars to sefed URL, FALSE: don't.
	 * @param  string  $tab           The tab to open directly
	 * @param  string  $format        'html', 'component', 'raw', 'rawrel'
	 * @return string                 The absolute URL (relative if rawrel)
	 */
	function userProfileEditUrl( $userId = null, $htmlSpecials = true, $tab = null, $format = 'html' ) {
		if ( $userId && ( $userId == $this->myId() ) ) {
			$userId		=	null;
		}
		return $this->cbSef( 'index.php?option=com_comprofiler&task=userdetails' . ( $userId ? '&uid=' . (int) $userId : '' ) . ( $tab ? '&tab=' . urlencode( $tab ) : '' ) . getCBprofileItemid( false ), $htmlSpecials, $format );
	}
	/**
	 * gets URL to view list of profies
	 * @static
	 * @since CB 1.2.3
	 *
	 * @param  int     $listId        The list id (if null, default list)
	 * @param  string  $htmlSpecials  TRUE (default): apply htmlspecialchars to sefed URL, FALSE: don't.
	 * @param  int     $searchMode    1 for search only, 0 for list (default)
	 * @param  string  $format        'html', 'component', 'raw', 'rawrel'
	 * @return string                 The absolute URL (relative if rawrel)
	 */
	function userProfilesListUrl( $listId = null, $htmlSpecials = true, $searchMode = null, $format = 'html' ) {
		return $this->cbSef( 'index.php?option=com_comprofiler&task=userslist' . ( $listId ? '&listid=' . (int) $listId : '' ) . ( $searchMode ? '&searchmode=' . urlencode( $searchMode ) : '' ) . getCBprofileItemid( false, 'userslist' ), $htmlSpecials, $format );
	}
	/**
	 * gets URL to render a CB view
	 * @static
	 * @since CB 1.2.3
	 *
	 * @param  string  $task          task/view  e.g. 'manageconnections', 'registers', 'lostpassword', 'login', 'logout', 'moderateimages', 'moderatereports', 'moderatebans', 'viewreports', 'processreports', 'pendingapprovaluser'
	 * @param  string  $htmlSpecials  TRUE (default): apply htmlspecialchars to sefed URL, FALSE: don't.
	 * @param  string  $formId        Reserved for future use: If applicable: form id
	 * @param  string  $format        'html', 'component', 'raw', 'rawrel'
	 * @return string                 The absolute URL (relative if rawrel)
	 */
	function viewUrl( $task, $htmlSpecials = true, $formId = null, $format = 'html' ) {
		return $this->cbSef( 'index.php?option=com_comprofiler&task=' . urlencode( $task ) . ( $formId ? '&formid=' . urlencode( $formId ) : '' ) . getCBprofileItemid( false, 'registers' ), $htmlSpecials, $format );
	}
	function & _getCmsUserObject( $cmsUserId = null ) {
		if ( $this->_cmsUserNeedsDb ) {
			global $_CB_database;
			$obj				=	new $this->_cmsUserClassName( $_CB_database );
		} else {
			$obj				=	new $this->_cmsUserClassName();
		}
		if ( $cmsUserId !== null ) {
			if ( ! $obj->load( (int) $cmsUserId ) ) {
				$obj			=	null;
			} else {
				if ( checkJversion() == 2 ) {
					global $_CB_framework;
					$obj->gid	=	(int) $_CB_framework->acl->getBackwardsCompatibleGid( array_values( (array) $obj->groups ) );
			}
		}
		}
		return $obj;
	}
	function getUserIdFrom( $field, $value ) {
		global $_CB_database;

		$_CB_database->setQuery( 'SELECT id FROM #__users u WHERE u.' . $_CB_database->NameQuote( $field ) . ' = ' . $_CB_database->Quote( $value ), 0, 1 );
		$results		=	$_CB_database->loadResultArray();
		if ( $results && ( count( $results ) == 1 ) ) {
			return $results[0];
		}
		return null;
	}
	/**
	 * Returns is user is "online" and last time online of the user
	 *
	 * @param  int  $userId
	 * @return int|null      last online time of the user
	 */
	function userOnlineLastTime( $userId ) {
		static $cache				=	array();
		if ( ! array_key_exists( (int) $userId, $cache ) ) {	// isset doesn't work as offline users return null
			global $_CB_database;
			$_CB_database->setQuery( 'SELECT MAX(time) FROM #__session WHERE userid = ' . (int) $userId . ' AND guest = 0');
			$cache[(int) $userId]	=	$_CB_database->loadResult();
		}
		return $cache[(int) $userId];
	}
	function displayCmsEditor( $hiddenField, $content, $width, $height, $col, $row ) {
		if ( ! $this->_editorDisplay['returns'] ) {
			ob_start();
		}
		if ( $this->_editorDisplay['display']['args'] == 'withid' ) {
			$args		=	array( 'editor' . $hiddenField, htmlspecialchars( $content ), $hiddenField, $width, $height, $col, $row );
		} else {
			$args		=	array( $hiddenField, htmlspecialchars( $content ), $width, $height, $col, $row );
		}
		$return			=	call_user_func_array( $this->_editorDisplay['display']['call'], $args );
		if ( ! $this->_editorDisplay['returns'] ) {
			$return		=	ob_get_contents();
			ob_end_clean();
		}
		return $return;
	}
	function saveCmsEditorJS( $hiddenField, $outputId = 0, $outputOnce = true ) {
		static $outputsDone		=	array();

		if ( ! $this->_editorDisplay['returns'] ) {
			ob_start();
		}
		if ( $this->_editorDisplay['save']['args'] == 'withid' ) {
			$args			=	array( 'editor' . $hiddenField, $hiddenField );
		} else {
			$args			=	array( $hiddenField );
		}
		$return				=	call_user_func_array( $this->_editorDisplay['save']['call'], $args );
		if ( ! $this->_editorDisplay['returns'] ) {
			$return			=	ob_get_contents();
			ob_end_clean();
		}

		if ( $outputOnce ) {
			if ( isset( $outputsDone[$outputId] ) && ( $return == $outputsDone[$outputId] ) ) {
				// in case the save function is identical for all HTML editor fields:
				$return				=	null;
			} else {
				$outputsDone[$outputId]	=	$return;
			}
		}
		return $return;
	}

	/**
	 * Returns the start time of CB's pageload
	 *
	 * @return int     Unix-time in seconds
	 */
	function now( ) {
		return $this->_now;
	}
	/**
	 * Returns date( 'Y-m-d H:i:s' ) but taking in account system offset for database's NOW()
	 *
	 * @return string 'YYYY-MM-DD HH:mm:ss'
	 */
	function dateDbOfNow( ) {
		return date( 'Y-m-d H:i:s', $this->now() - ( 3600 * $this->getCfg( 'offset' ) ) );
	}
	function setPageTitle( $title ) {
		if ( method_exists( $this->document->_cmsDoc, 'setTitle' ) ) {
			return $this->document->_cmsDoc->setTitle( $title );	// J1.6 (and 1.5?)
		} elseif ( method_exists( $this->_baseFramework, 'setPageTitle' ) ) {
			return $this->_baseFramework->setPageTitle( $title );	// J1.0 and Mambo (and 1.5?)
		} else {
			return null;
		}
	}
	function appendPathWay( $title, $link = null ) {
		if ( method_exists( $this->_baseFramework, 'appendPathWay' ) ) {
			if ( checkJversion() == 1 ) {
				return $this->_baseFramework->appendPathWay( $title, $link );
			} else {
				// don't process link, as some version do htmlspecialchar those:
				// if ( $link ) {
				//	$title	=	'<a href="' . $link . '">' . $title . '</a>';
				// }
				return $this->_baseFramework->appendPathWay( $title );
			}
		} else {
			return null;
		}
	}
	/**
	 * DEPRECIATED: DO NOT USE.
	 * Use: addHeadStyleSheet, addHeadScriptUrl, and other $_CB_framework->document->addHead functions.
	 * This was an temporary function for CB 1.2 RC: DO NOT USE
	 * @since      CB 1.2 RC
	 * @deprecated CB 1.2
	 *
	 * @param      string  $tag
	 * @return     void
	 */
	function addCustomHeadTag( $tag ) {
		global $_CB_framework;

		if ( $_CB_framework->getCfg( 'debug' ) == 1 ) {
			$bt		=	@debug_backtrace();
			trigger_error( sprintf('$_CB_framework->addCustomHeadTag CALLED FROM: %s line %s (function %s). This is old depreciated old CB 1.2 RC API. (Use: addHeadStyleSheet, addHeadScriptUrl, and other $_CB_framework->document->addHead functions).' . "\n", @$bt[0]['file'], @$bt[0]['line'], @$bt[1]['class'] . ':' . @$bt[1]['function'] ), E_USER_WARNING );
		}
		$this->document->addHeadCustomHtml( $tag );
		return null;
	}
	function getUserState( $stateName ) {
		return $this->_baseFramework->getUserState( $stateName );
	}
	function getUserStateFromRequest( $stateName, $reqName, $default = null ) {
		return $this->_baseFramework->getUserStateFromRequest( $stateName, $reqName, $default );
	}
	function setUserState( $stateName, $stateValue ) {
		return $this->_baseFramework->setUserState( $stateName, $stateValue );
	}
	function displayedUser( $uid = null ) {
		static $profileOnDisplay = null;
		if ( $uid ) {
			$profileOnDisplay	=	$uid;
		}
		return $profileOnDisplay;
	}
	function cbset( $name, $value ) {
		$this->$name			=	$value;
	}
	function outputCbJs( $javascriptCode ) {
		$this->_jsCodes[]		=	$javascriptCode;
	}
	/**
	 * JS + JQUERY LIB:
	 *
	 */
	var $_jsCodes				=	array();
	var $_jQueryCodes			=	array();
	var $_jQueryPlugins			=	array();
	var $_jQueryPluginsSent	=	array();
	var $_jqueryDependencies	=	array(	'flot'		=>	array( 1	=>	array( 'excanvas' ) ),
											'rating'	=>	array( -1	=>	array( 'metadata' ) ) );
	var $_jqueryCssFiles		=	array(	'slimbox2'	=>	array( 'lightbox.css' => array( false, 'screen' ) ),
											'ui-all'	=>	array( 'jqueryui/ui.all.css' => array( false, null ) ) );

	function _coreJQueryFilePath( $jQueryPlugin, $pathType = 'live_site' ) {
		if ( ( $pathType == 'live_site' ) && ( $this->getUi() == 2 ) ) {
			$base				=	'..';			// relative paths in backend
		} else {
			$base				=	$this->getCfg( $pathType );
		}
		return $base . '/components/com_comprofiler/js/jquery-' . _CB_JQUERY_VERSION . '/jquery.' . $jQueryPlugin . '.js';
	}
	/**
	 * Adds an external JQuery plugin to the known JQuery plugins (if not already known)
	 *
	 * @param  string|array   $jQueryPlugins  Short Name of plugin or array of short names
	 * @param  string|boolean $path           Path to file from root of website (including leading / ) so that it can be appended to absolute_path or live_site (OR TRUE: part of core)
	 * @param  array          $dependencies   array( 1	=>	array( pluginNames ) ) for plugins to load after and -1 for plugins to load before.
	 * @param  array          $cssfiles       array( filename => array( minVersionExists, media ) ) : media = null or 'screen'.
	 */
	function addJQueryPlugin( $jQueryPlugins, $path, $dependencies = null, $cssfiles = null ) {

		$jQueryPlugins										=	(array) $jQueryPlugins;
		foreach ( $jQueryPlugins as $jQueryPlugin ) {

			if ( ( $path === true ) || file_exists( $this->_coreJQueryFilePath( $jQueryPlugin, 'absolute_path' ) ) ) {
				$path										=	$this->_coreJQueryFilePath( $jQueryPlugin );
			} else {
				if ( $dependencies !== null ) {
					$this->_jqueryDependencies				=	array_merge( $this->_jqueryDependencies, array( $jQueryPlugin => $dependencies ) );
				}
				if ( $cssfiles !== null ) {
					$this->_jqueryCssFiles					=	array_merge( $this->_jqueryCssFiles, array( $jQueryPlugin => $cssfiles ) );
				}
			}

			if ( ! isset( $this->_jQueryPlugins[$jQueryPlugin] ) ) {
				// not yet configured for loading: check dependencies: -1: before:
				if ( isset( $this->_jqueryDependencies[$jQueryPlugin][-1] ) ) {
					foreach ( $this->_jqueryDependencies[$jQueryPlugin][-1] as $jLib ) {
						if ( ! isset( $this->_jQueryPlugins[$jLib] ) ) {
							$this->_jQueryPlugins[$jLib]	=	$this->_coreJQueryFilePath( $jLib );
						}
					}
				}
				$this->_jQueryPlugins[$jQueryPlugin]		=	$path;
				// +1: dependencies after:
				if ( isset( $this->_jqueryDependencies[$jQueryPlugin][1] ) ) {
					foreach ( $this->_jqueryDependencies[$jQueryPlugin][1] as $jLib ) {
						if ( ! isset( $this->_jQueryPlugins[$jLib] ) ) {
							$this->_jQueryPlugins[$jLib]	=	$this->_coreJQueryFilePath( $jLib );
						}
					}
				}
			}
		}
	}
	/**
	 * Outputs a JQuery init string into JQuery strings at end of page,
	 * and adds if needed JS file inclusions at begin of page.
	 * Pro-memo, JQuery runs in CB in noConflict mode.
	 *
	 * @param  string  $javascriptCode  Javascript code ended by ; which will be put in between jQuery(document).ready(function($){ AND });
	 * @param  string  $jQueryPlugin    (optional) name of plugin to auto-load (if core plugin, or call first addJQueryPlugin).
	 */
	function outputCbJQuery( $javascriptCode, $jQueryPlugin = null ) {
		if ( $jQueryPlugin ) {
			$this->addJQueryPlugin( $jQueryPlugin, true );
		}
		$this->_jQueryCodes[]	=	$javascriptCode;
		if ( $this->document->_headsOutputed ) {
			$this->getAllJsPageCodes();
		}
	}
	function getAllJsPageCodes( ) {
		$jsCodeTxt			=	'';

		// jQuery code loading:

		if ( count( $this->_jQueryCodes ) > 0 ) {
			foreach ( array_keys( $this->_jQueryPlugins ) as $plugin ) {
				if ( isset( $this->_jqueryCssFiles[$plugin] ) ) {
					foreach ( $this->_jqueryCssFiles[$plugin] as $templateFile => $minExistsmedia ) {
						$templateFileWPath	=	selectTemplate( 'absolute_path' ) . '/' . $templateFile;
						if ( file_exists( $templateFileWPath ) ) {
							$templateFileUrl	=	selectTemplate( 'live_site' ) . $templateFile;
						} else {
							$templateFileUrl	=	selectTemplate( 'live_site', 'default' ) . $templateFile;
						}
						if ( ! isset( $this->_jQueryPluginsSent[$templateFileUrl] ) ) {
							$this->document->addHeadStyleSheet( $templateFileUrl, $minExistsmedia[0], $minExistsmedia[1] );
							$this->_jQueryPluginsSent[$templateFileUrl]		=	true;
						}
					}
				}
			}
			if ( ! defined( 'J_JQUERY_LOADED' ) ) {
				$this->document->addHeadScriptUrl( '/components/com_comprofiler/js/jquery-' . _CB_JQUERY_VERSION . '/jquery-' . _CB_JQUERY_VERSION . '.js', true, null, 'jQuery.noConflict();' );
				define( 'J_JQUERY_LOADED', 1 );
			}
			foreach ( $this->_jQueryPlugins as $plugin => $pluginPath ) {
				if ( ! isset( $this->_jQueryPluginsSent[$plugin] ) ) {
					$this->document->addHeadScriptUrl( $pluginPath, true, null, null, ( $plugin == 'excanvas' ? '<!--[if IE]>' : '' ), ( $plugin == 'excanvas' ? '<![endif]-->' : '' ) );
					$this->_jQueryPluginsSent[$plugin]		=	true;
				}
			}
/*
			$jsCodeTxt		=	"var cbJFrame = window.cbJFrame = function() { return new cbJFrame.prototype.init(); };\n"
							.	"cbJFrame.fn = cbJFrame.prototype = {\n"
							.	"  init: function() { return this; },\n"
							.	"  cbjframe: '" . $ueConfig['version'] . "',\n"
							.	"  jquery: null\n"
							.	"};\n"
							.	"cbJFrame.prototype.init.prototype = cbJFrame.prototype;\n"
							//.	"cbJFrame.jquery = jQuery.noConflict();\n"
							.	'cbJFrame.jquery(document).ready(function($){' . "\n"
							.	implode( "\n", $this->_jQueryCodes )
							.	"});\n";
*/
			$jQcodes		=	trim( implode( "\n", $this->_jQueryCodes ) );
			if ( $jQcodes !== '' ) {
				$jsCodeTxt	=	'jQuery(document).ready(function($){' . "\n"
							.	$jQcodes
							.	"});"
							;
				$this->document->addHeadScriptDeclaration( $jsCodeTxt );
			}
			$this->_jQueryCodes		=	array();
		}

		// classical standalone javascript loading (for compatibility), depreciated ! :

		if ( count( $this->_jsCodes ) > 0 ) {
			$this->document->addHeadScriptDeclaration( implode( "\n", $this->_jsCodes ) );
			$this->_jsCodes	=	array();
		}
	}
}

/**
 * Converts an URL to an absolute URI with SEF format
 *
 * @param  string  $string        The relative URL
 * @param  string  $htmlSpecials  TRUE (default): apply htmlspecialchars to sefed URL, FALSE: don't.
 * @param  string  $format        'html', 'component', 'raw', 'rawrel'		(added in CB 1.2.3)
 * @return string                 The absolute URL (relative if rawrel)
 */
function cbSef( $string, $htmlSpecials = true, $format = 'html' ) {
	global $_CB_framework;
	return $_CB_framework->cbSef( $string, $htmlSpecials, $format );
}
/**
 * Displays "Not authorized", and if not logged-in "you need to login"
 *
 */
function cbNotAuth() {
	global $_CB_framework;

	echo '<div class="error">' . _UE_NOT_AUTHORIZED . '</div>';
	if ($_CB_framework->myId() < 1 ) {
		echo '<div class="error">' . _UE_DO_LOGIN . '</div>';
	}
}


/**
 * Text classes and old function
 *
 */

class CBTxtStorage {
	var $_iso;					// 'UTF-8', 'ISO-8859-1', ...
	var $_mode;					// 1: debug, 2: edit
	var $_lang					=	'en-GB';
	var $_langOld				=	'english';
	var $_strings				=	array();
	var $_usedStrings			=	array();

	function CBTxtStorage( $iso, $mode ) {
		$this->_iso				=	$iso;
		$this->_mode			=	$mode;
		if ( ( $mode > 2 ) && defined( 'JPATH_ADMINISTRATOR' ) ) {
			jimport( 'joomla.plugin.plugin' );
			$app		=&	JFactory::getApplication();
			$app->registerEvent( 'onAfterRender', '_onAfterRender_CB_Txt_display_translations_table' );
		}
	}
	function recordUsedString( $english ) {
		if ( $this->_mode == 3 ) {
			if ( ! isset( $this->_strings[$english] ) ) {
				$this->_usedStrings[$english]	=	null;
			}
		} elseif ( $this->_mode == 4 ) {
			$this->_usedStrings[$english]	=	( isset( $this->_strings[$english] ) ? $this->_strings[$english] : null );
		}
	}
	function listUsedStrings() {
		$r		=	null;
		if ( $this->_usedStrings ) {
			cbimport( 'language.cbteamplugins' );
			$r	= '<table class="adminlist" id="cbtranslatedstrings"><tr class="sectiontableheader"><th>'
			.	( $this->_mode == 3 ? CBTxt::Th('Untranslated strings on this page')
				: CBTxt::Th('Translations on this page') )
			.	': '
			.	CBTxt::Th('English string')
			.	'</th><th>'
			.	CBTxt::Th('Translated string')
			.	'</th></tr>'
			;
			$s	=	0;
			foreach ( $this->_usedStrings as $k => $v ) {
				$r .= '<tr class="sectiontableentry' . ( ( $s & 1 ) + 1 ) . ' row' . ( $s++ & 1 ) . '"><td>'
				.	htmlspecialchars( $k )
				.	'</td><td>'
				.	( $v === null ? '-' : htmlspecialchars( $v ) )
				.	'</td></tr>'
				;
			}
			$r	.=	'</table>';
		}
		return $r;
	}
}
/**
 * Translations debug: can not be a method because of joomla restriction to functions
 */
function _onAfterRender_CB_Txt_display_translations_table() {
	global $_CB_TxtIntStore;

	$html	=	$_CB_TxtIntStore->listUsedStrings();
	if ( $html ) {
		JResponse::setBody( str_replace( '</body>', $html . '</body>', JResponse::getBody() ) );
	}
}
class CBTxt {
	static function T( $english ) {
		global $_CB_TxtIntStore;

		if ( $_CB_TxtIntStore->_mode == 0 ) {
			if ( isset( $_CB_TxtIntStore->_strings[$english] ) ) {
				return CBTxt::utf8ToISO( $_CB_TxtIntStore->_strings[$english] );
			} else {
				return $english;
			}
		} else {
			$_CB_TxtIntStore->recordUsedString( $english );
			if ( isset( $_CB_TxtIntStore->_strings[$english] ) ) {
				return CBTxt::utf8ToISO( '*' . $_CB_TxtIntStore->_strings[$english] . '*' );
			} else {
				return '===\\' . str_replace( '%s', '[%s]', $english ) . '/---';
			}
		}
	}
	static function Th( $english ) {
		global $_CB_TxtIntStore;

		if ( $_CB_TxtIntStore->_mode == 0 ) {
			if ( isset( $_CB_TxtIntStore->_strings[$english] ) ) {
				return CBTxt::utf8ToISO( $_CB_TxtIntStore->_strings[$english] );
			} else {
				return $english;
			}
		} elseif ( $_CB_TxtIntStore->_mode == 1 ) {
			if ( isset( $_CB_TxtIntStore->_strings[$english] ) ) {
				return CBTxt::utf8ToISO( '*' . $_CB_TxtIntStore->_strings[$english] . '*' );
			} else {
				return '===&gt;' . str_replace( '%s', '[%s]', $english ) . '&lt;===';
			}
		} else {
			$_CB_TxtIntStore->recordUsedString( $english );
			if ( isset( $_CB_TxtIntStore->_strings[$english] ) ) {
				return '<span style="color:#CCC;font-style:italic">' . CBTxt::utf8ToISO( $_CB_TxtIntStore->_strings[$english] ) . '</span>';
			} else {
				return '<span style="color:#FF0000;font-weight:bold">' . '===>' . $english . '<===' . '</span>';
			}
		}
	}
	static function Tutf8( $english ) {
		global $_CB_TxtIntStore;

		if ( $_CB_TxtIntStore->_mode == 0 ) {
			if ( isset( $_CB_TxtIntStore->_strings[$english] ) ) {
				return $_CB_TxtIntStore->_strings[$english];
			} else {
				return $english;
			}
		} else {
			$_CB_TxtIntStore->recordUsedString( $english );
			if ( isset( $_CB_TxtIntStore->_strings[$english] ) ) {
				return '*' . $_CB_TxtIntStore->_strings[$english] . '*';
			} else {
				return '===\\' . str_replace( '%s', '[%s]', $english ) . '/---';
			}
		}
	}
	/**
	 * Parse the string through CBTxt::T.
	 * That is, for a particular string find the corresponding translation.
	 * Variable subsitution is performed for the $args parameter.
	 * @since 1.3
	 *
	 * @param string   $english  the string to translate
	 * @param array    $args     a strtr-formatted array of string substitutions
	 * @return string
	*/
	static function P( $english, $args = null ) {
		return CBTxt::_parseReplaceString( CBTxt::T( $english ), $args );
	}
	/**
	 * Parse the string through CBTxt::Th.
	 * That is, for a particular string find the corresponding translation.
	 * Variable subsitution is performed for the $args parameter.
	 * @since 1.3
	 *
	 * @param string   $english  the string to translate
	 * @param array    $args     a strtr-formatted array of string substitutions
	 * @return string
	*/
	static function Ph( $english, $args = null ) {
		return CBTxt::_parseReplaceString( CBTxt::Th( $english ), $args );
	}
	/**
	 * Parse the string through CBTxt::Tutf8.
	 * That is, for a particular string find the corresponding translation.
	 * Variable subsitution is performed for the $args parameter.
	 * @since 1.3
	 *
	 * @param string   $english  the string to translate
	 * @param array    $args     a strtr-formatted array of string substitutions
	 * @return string
	*/
	static function Putf8( $english, $args = null ) {
		return CBTxt::_parseReplaceString( CBTxt::Tutf8( $english ), $args );
	}
	/**
	 * Parse the translated string with strtr
	 * That is, for a particular string find the corresponding translation.
	 * Variable subsitution is performed for the $args parameter.
	 * @since 1.3
	 *
	 * @param string   $string   the string to substitute
	 * @param array    $args     a strtr-formatted array of string substitutions
	 * @return string
	*/
	private static function _parseReplaceString( $string, $args ) {
		if ( $args === null ) {
			$args		=	array();
		}
		return strtr( $string, $args );
	}
	/**
	 * Adds strings to the translations. Used by language plugins
	 *
	 * @param  array  $array
	 */
	static function addStrings( $array ) {
		global $_CB_TxtIntStore;
		$_CB_TxtIntStore->_strings			=	array_merge( $_CB_TxtIntStore->_strings, $array );
	}
	/**
	 * Converts UTF-8 string to CMS charset
	 *
	 * @param  string  $string
	 * @return string
	 */
	static function utf8ToISO( $string ) {
		global $_CB_TxtIntStore;

		if ( $_CB_TxtIntStore->_iso == 'UTF-8' ) {
			return $string;
		} else {
			return CBTxt::charsetConv( $string, 'UTF-8', $_CB_TxtIntStore->_iso );
		}
	}
	/**
	 * Converts or cleans charsets (e.g. 'ISO-8859-1', 'UTF-8')
	 * @since CB 1.2.2
	 *
	 * @param $string
	 * @param $from
	 * @param $to
	 */
	static function charsetConv( $string, $from, $to ) {
		if ( ( $from == 'UTF-8' ) && ( strncmp( $to, 'ISO-8859-1', 9 ) == 0 ) ) {
			return utf8_decode( $string );
		} elseif ( ( $to == 'UTF-8' ) && ( strncmp( $from, 'ISO-8859-1', 9 ) == 0 ) ) {
			return utf8_encode($string);
		} else {
			return CBTxt::_unhtmlentities( htmlentities($string,ENT_NOQUOTES,$from),ENT_NOQUOTES,$to);
		}
	}
	/**
	 * Equivalent of html_entity_decode( $string ) using ENT_COMPAT and the charset of the system
	 * @since 1.2.2
	 *
	 * @param  string  $string
	 * @return string
	 */
	static function html_entity_decode( $string ) {
		global $_CB_TxtIntStore;
		return CBTxt::_unhtmlentities( $string, ENT_COMPAT, $_CB_TxtIntStore->_iso );
	}
	/**
	 * @deprecated CB 1.2.2 : keep in CB 1.x, remove in 2.0, but use without _ as of 1.2.2
	 */
	function _unhtmlentities( $string, $quotes = ENT_COMPAT, $charset = "ISO-8859-1" ) {
		$phpv = phpversion();
		if ( version_compare( $phpv, '4.4.3', '<' )
			 || ( version_compare( $phpv, '5.0.0', '>=' ) && version_compare( $phpv, '5.1.3', '<' ) )
		     || ( version_compare( $phpv, '5.0.0', '<'  ) && ( ! in_array( $charset, array( "ISO-8859-1", "ISO-8859-15", "cp866", "cp1251", "cp1252" ) ) ) )
		     || ( version_compare( $phpv, '5.1.3', '>=' ) && ( ! in_array( $charset, array( "ISO-8859-1", "ISO-8859-15", "cp866", "cp1251", "cp1252",
		     								   "KOI8-R", "BIG5", "GB2312", "UTF-8", "BIG5-HKSCS", "Shift_JIS", "EUC-JP" ) ) ) )
		   ) {
			// For 4.1.0 =< PHP < 4.3.0 use this function instead of html_entity_decode: also php < 5.0 does not support UTF-8 outputs !
			// Plus up to 4.4.2 and 5.1.2 html_entity_decode is deadly buggy
			$trans_tbl = get_html_translation_table( HTML_ENTITIES );
			if ( $charset == "UTF-8" ) {
				foreach ( $trans_tbl as $k => $v ) {
					$ttr[$v] = utf8_encode($k);
				}
			} else {
				$ttr = array_flip( $trans_tbl );
			}
			return strtr( $string, $ttr );
		} else  {
			return html_entity_decode( $string, $quotes, $charset );
		}
	}

}

/**
 * CB GLOBALS and initializations
 */

// ----- NO MORE CLASSES OR FUNCTIONS PASSED THIS POINT -----
// Post class declaration initialisations
// some version of PHP don't allow the instantiation of classes
// before they are defined

switch ( checkJversion() ) {
	case 2:
		global $mainframe;

		$mainframe			=&	JFactory::getApplication();
		$tmpDatabase		=&	JFactory::getDBO();
		$my					=&	JFactory::getUser();
		$acl				=&	JFactory::getACL();
		$myAid				=	null;
		$sefFunc			=	array( 'JRoute', '_' );
		$sefFuncHtmlEnt		=	false;
		$cmsUser			=	'JUser';
		$cmsUserNeedsDb		=	false;
		$cmsRedirectFunc	=	array( $mainframe, 'redirect' );
		$lang				=&	JFactory::getLanguage();
		$myLanguage			=	strtolower( preg_replace( '/^(\w+).*$/i', '\1', $lang->getName() ) );
		$myLanguageTag		=	$lang->getTag();
		$outputCharst		=	'UTF-8';
		$getVarFunction		=	array( 'JRequest', 'getVar' );
		$Jdocument			=&	JFactory::getDocument();

		if ( $Jdocument->getType() == 'html' ) {
			$getDocFunction	=	array( 'JFactory', 'getDocument' );
		} else {
			$getDocFunction	=	false;
		}

		$editor				=&	JFactory::getEditor();
		$editorDisplay		=	array(	'display' => array( 'call' => array( $editor , 'display' ), 'args' => 'noid' ),
								  		'save' => array( 'call' => array( $editor , 'save' ), 'args' => 'noid' ),
								  		'returns' => true
										);
		break;
	case 1:
		global $mainframe;		// 		$mainframe		=&	JFactory::getApplication();
		$tmpDatabase	=&	JFactory::getDBO();
		$my				=&	JFactory::getUser();
		$acl			=&	JFactory::getACL();
		$myAid			=	$my->get('aid', 0);
		$sefFunc		=	array( 'JRoute', '_' );
		$sefFuncHtmlEnt	=	false;
		$cmsUser		=	'JUser';
		$cmsUserNeedsDb	=	false;
		$cmsRedirectFunc =	array( $mainframe, 'redirect' );
		$lang			=&	JFactory::getLanguage();
		$myLanguage		=	$lang->getBackwardLang();
		$myLanguageTag	=	$lang->getTag();
		$outputCharst	=	'UTF-8';
		$getVarFunction	=	array( 'JRequest', 'getVar' );
		$Jdocument		=&	JFactory::getDocument();
		if ( $Jdocument->getType() == 'html' ) {
			$getDocFunction	=	array( 'JFactory', 'getDocument' );
		} else {
			$getDocFunction	=	false;
		}

		$editor			=&	JFactory::getEditor();
		//$editor->initialise();
		$editorDisplay	=	array( 'display' => array( 'call' => array( $editor , 'display' ),	'args' => 'noid' ),
								   'save'	 => array( 'call' => array( $editor , 'save' ),		'args' => 'noid' ),
								   'returns' => true );
		// no$editorDisplay	=	array( 'JEditor' , 'display' );
		break;
	case 0:
		global $mainframe, $database, $my, $acl;
		$tmpDatabase	=&	$database;
		$myAid			=	$my->gid;
		$sefFunc		=	'sefRelToAbs';
		$sefFuncHtmlEnt	=	true;
		$cmsUser		=	'mosUser';
		$cmsUserNeedsDb	=	true;
		$cmsRedirectFunc =	'mosRedirect';
		$myLanguage		=	$mainframe->getCfg( 'lang' );
		$myLanguageTag	=	null;
		$outputCharst	=	( defined( '_ISO' ) ? strtoupper( str_replace( "charset=", "", _ISO ) ) : 'ISO-8859-1' );
		$getVarFunction	=	null;
		$getDocFunction	=	null;
		$editorDisplay	=	array( 'display' => array( 'call' => 'editorArea',		  'args' => 'withid' ),
								   'save'	 => array( 'call' => 'getEditorContents', 'args' => 'withid' ),
								   'returns' => false );
		break;
	case -1:
	default:
		global $mainframe, $database, $my, $acl;
		$tmpDatabase	=&	$database;
		$myAid			=	$my->gid;
		$sefFunc		=	'sefRelToAbs';
		$sefFuncHtmlEnt	=	true;
		$cmsUser		=	'mosUser';
		$cmsUserNeedsDb	=	true;
		$cmsRedirectFunc =	'mosRedirect';
		$myLanguage		=	$mainframe->getCfg( 'locale' );
		$myLanguageTag	=	null;
		$outputCharst	=	( defined( '_ISO' ) ? strtoupper( str_replace( "charset=", "", _ISO ) ) : 'UTF-8' );
		$getVarFunction	=	null;
		$getDocFunction	=	null;
		$editorDisplay	=	array( 'display' => array( 'call' => 'editorArea',		  'args' => 'withid' ),
								   'save'	 => array( 'call' => 'getEditorContents', 'args' => 'withid' ),
								   'returns' => false );
		break;
}
switch ( checkJversion() ) {
	case 2:
	$aclParams			=	array(	'canEditUsers'			=>	array( 'com_user', 'core.edit', 'users', null ),
									'canBlockUsers'			=>	array( 'com_users', 'core.edit.state', 'users', null ),
									'canReceiveAdminEmails'	=>	array( 'com_users', 'core.admin', 'users', null ),
									'canEditOwnContent'		=>	array( 'com_content', 'core.edit', 'users', null, 'content', 'own' ),
									'canAddAllContent'	 	=>	array( 'com_content', 'core.create', 'users', null, 'content', 'all' ),
									'canEditAllContent' 	=>	array( 'com_content', 'core.edit', 'users', null, 'content', 'all' ),
									'canPublishContent'		=>	array( 'com_content', 'core.edit.state', 'users', null, 'content', 'all' ),
									'canInstallPlugins'		=>	array( 'com_installer', 'core.manage', 'users', null ),
									'canManageUsers'		=>	array( 'com_users', 'core.manage', 'users', null )
							);
	break;
	case 1:
	$aclParams			=	array(	'canEditUsers'			=>	array( 'com_user', 'edit', 'users', null ),
									'canBlockUsers'			=>	array( 'com_users', 'block user', 'users', null ),
									'canReceiveAdminEmails'	=>	array( 'com_users', 'email_events', 'users', null ),
									'canEditOwnContent'		=>	array( 'com_content', 'edit', 'users', null, 'content', 'own' ),
									'canAddAllContent'	 	=>	array( 'com_content', 'add', 'users', null, 'content', 'all' ),
									'canEditAllContent' 	=>	array( 'com_content', 'edit', 'users', null, 'content', 'all' ),
									'canPublishContent'		=>	array( 'com_content', 'publish', 'users', null, 'content', 'all' ),
									'canInstallPlugins'		=>	array( 'com_installer', 'installer', 'users', null ),
									'canManageUsers'		=>	array( 'com_users', 'manage', 'users', null )
							);
	break;
	default:
	$aclParams			=	array(	'canEditUsers'			=>	array( 'administration', 'manage', 'users', null, 'components', 'com_users' ),
									'canBlockUsers'			=>	array( 'administration', 'edit', 'users', null, 'user properties', 'block_user' ),
									'canReceiveAdminEmails'	=>	array( 'workflow', 'email_events', 'users', null ),
									'canEditOwnContent'		=>	array( 'action', 'edit', 'users', null, 'content', 'own' ),
									'canAddAllContent' 		=>	array( 'action', 'add', 'users', null, 'content', 'all' ),
									'canEditAllContent' 	=>	array( 'action', 'edit', 'users', null, 'content', 'all' ),
									'canPublishContent'		=>	array( 'action', 'publish', 'users', null, 'content', 'all' ),
									'canInstallPlugins'		=>	array( 'administration', 'install', 'users', null, 'components', 'all' ),
									'canManageUsers'		=>	array( 'administration', 'manage', 'users', null, 'components', 'com_users' )
							);
	break;
}

/**
 * CB framework
 * @global CBframework $_CB_framework
 */
global $_CB_framework;
$optionOfCb				=	'com_comprofiler';		// cbGetParam( $_REQUEST, 'option', 'com_comprofiler' )
$_CB_framework			=	new CBframework( $mainframe, $tmpDatabase, $acl, $aclParams, $sefFunc, $sefFuncHtmlEnt, $cmsUser, $cmsUserNeedsDb, $cmsRedirectFunc, $my->id, $my->username, $my->usertype, $myAid, $myLanguage, $myLanguageTag, array( 'option' => $optionOfCb ), $getVarFunction, $getDocFunction, $outputCharst, $editorDisplay );

cbimport( 'cb.acl' );

$_CB_framework->acl		=	new CBACL( $acl );

if ( checkJversion() == 2 ) {
	$_CB_framework->_myCmsGid	=	(int) $_CB_framework->acl->getBackwardsCompatibleGid( array_values( (array) $my->groups ) );
}
/**
 * CB text languages EXPERIMENTAL
 * @access private
 * @global CBText $_CB_framework
 */
global $_CB_TxtIntStore;
$_CB_TxtIntStore	=	new CBTxtStorage( $_CB_framework->outputCharset(), ( isset( $ueConfig['translations_debug'] ) ? (int) $ueConfig['translations_debug'] : 0 ) );
?>
