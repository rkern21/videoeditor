<?php

// definitions
define( 'MODULE', 'mod_roktwittie');
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'JPATH_BASE', getBasePath(dirname($_SERVER['SCRIPT_FILENAME']), DS));

// includes
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	
jimport('joomla.database.database');
jimport('joomla.html.parameter');

require_once (dirname(__FILE__).DS.'helper.php');

// get config
require_once JPATH_BASE . DS . 'configuration.php';
$conf = new JConfig();

// initialise session
$session = JFactory::getSession();

// initialise database connection
$db = JDatabase::getInstance( array('driver' => $conf->dbtype, 'host' => $conf->host, 'user' => $conf->user, 'password' => $conf->password, 'database' => $conf->db, 'prefix' => $conf->dbprefix) );

// module id
$cid = JRequest::getVar( 'cid', 0, 'method', 'int' );

// get module configuration
$query = 'SELECT *'
	. ' FROM #__modules'
	. ' WHERE id = ' . $db->Quote($cid);
$db->setQuery( $query );

$row = $db->loadAssoc( );

// check for invalid module
if (!$row || $row['module'] != MODULE) {
	error('Invalid module');
}

// build parameters object
$params = new JParameter($row['params']);

switch (JRequest::getVar( 'task', '', '', 'cmd' )) {
	case 'redirect':
		// twitter OAuth object
		$connection =& modRokTwittieHelper::getOauth($params, '', '');
		
		// consumer keys weren't configured
		if (!$connection) {
			redirectToEdit('Consumer keys are not configured.');
		}
		
		// get temporary credentials
		$url = JURI::Root() . "api.php?task=callback&cid=" . $cid;
		$request_token = @$connection->getRequestToken($url);
		
		if (!is_array($request_token) || !isset($request_token['oauth_token']) || !isset($request_token['oauth_token_secret'])) {
			error();
		}

		// save temporary credentials to session
		$session->set('oauth_token', $token = $request_token['oauth_token']);
		$session->set('oauth_token_secret', $request_token['oauth_token_secret']);
		 
		// if last connection failed don't display authorization link
		switch ($connection->http_code) {
		  case 200:
			// build authorize URL and ...
			$url = $connection->getAuthorizeURL($token);
			// ... redirect user to Twitter
			header('Location: ' . $url); 
			break;
		  default:
			error();
		}
			
		break;

	case 'callback':
		// if the oauth_token is old redirect to the connect page
		if (isset($_REQUEST['oauth_token']) && $session->get('oauth_token') !== $_REQUEST['oauth_token']) {
		  $session->set('oauth_status', 'oldtoken');
		  error();
		}

		// create TwitteroAuth object with app key/secret and token key/secret from default phase
		$connection =& modRokTwittieHelper::getOauth($params, $session->get('oauth_token'), $session->get('oauth_token_secret'));

		// request access tokens from twitter
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		
		// check if token was successfully returned
		if (!is_array($access_token) || !isset($access_token['oauth_token']) || !isset($access_token['oauth_token_secret'])) {
			error();
		}
		
		// update params
		$params->set('oauth_token', $access_token['oauth_token']);
		$params->set('oauth_token_secret', $access_token['oauth_token_secret']);
		
		$row['params'] = $params->toString();
		
		// convert to object for updateObject
		$row = (object) $row;
		
		$db->updateObject('#__modules', $row, 'id', false);
		
		// remove no longer needed request tokens
		$session->clear('oauth_token');
		$session->clear('oauth_token_secret');

		// if HTTP response is 200 continue otherwise something went wrong
		if (200 == $connection->http_code) {
			// the user has been verified and the access tokens can be saved for future use
			redirectToEdit('RokTwittie has been successfully authenticated with Twitter.');
		} else {
			error();
		}
		break;
		
	default:
		error('Restricted access');
		break;
}

function error($message = 'Could not connect to Twitter. Refresh the page or try again later.')
{
	die($message);
}

function redirectToEdit($message = null)
{
	global $cid;
	
	$u =& JFactory::getURI();
	$url = $u->toString();
	header('Location: ' . getBasePath($url) . '/administrator/index.php?option=com_modules&client=0&task=edit&cid[]=' . urlencode($cid) . '&message=' . urlencode($message));
}

function getBasePath($url, $separator = '/')
{
	return substr($url, 0, strpos($url, $separator . 'modules' . $separator . MODULE));
}