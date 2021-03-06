<?php
/**
* @package Janrain Engage SignIn
* @copyright Copyright (C) 2010 Thakkertech - All rights reserved.
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html, see license.txt
*/

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die('Restricted access');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$redirectType 			= modJanrainengageHelper::getType();
$redirectUrl			= modJanrainengageHelper::getReturnURL($params, $redirectType);
$GLOBALS["redirectUrl"] = $redirectUrl;

$db		=& JFactory::getDBO();
$user 	=& JFactory::getUser();
$my 	=& JFactory::getUser();

$rpx_imageurl = JURI::base().'modules/mod_janrainengage/images/rpxIcons.png';
$rpx_image_width = '';
$rpx_image_height = '';

$rpx_api_key 			= $params->get( 'rpx_api_key', "" );
$rpx_application_name	= $params->get( 'rpx_application_domain', "");
$rpx_show_type 			= $params->get( 'rpx_show_type', "popup" );
$rpx_imagepath 			= $params->get( 'rpx_imagepath', "" );
$rpx_image_width	 	= $params->get( 'rpx_image_width', "" );
$rpx_image_height		= $params->get( 'rpx_image_height', "" );
$appy_image_width_height= $params->get( 'appy_image_width_height', 0 );

$rpx_image_width = ( intval($rpx_image_width) > 0 && ( $appy_image_width_height == 1 ) ) ? 'width:'.$rpx_image_width.'px;' : '';
$rpx_image_height = ( intval($rpx_image_height) > 0 && ( $appy_image_width_height == 1 ) ) ? 'height:'.$rpx_image_height.'px;' :'';
$rpx_imagepath = trim($rpx_imagepath);
if( @GetImageSize($rpx_imagepath) )
{
	$rpx_imageurl = $rpx_imagepath;
}

$juri		=& JURI::getInstance();
$base		= $juri->toString( array('scheme', 'host', 'port'));
$token_url 	= $base.$_SERVER['REQUEST_URI'];

// Future-friendly json_encode
if( !function_exists('json_encode') )  
{
	require_once( JPATH_ROOT.DS.'modules'.DS.'mod_janrainengage'.DS.'JSON.php');
	function json_encode($data) 
	{
		$json = new Services_JSON();
		return( $json->encode($data) );
	}
}

// Future-friendly json_decode
if( !function_exists('json_decode') ) 
{
	require_once( JPATH_ROOT.DS.'modules'.DS.'mod_janrainengage'.DS.'JSON.php');
	function json_decode($data, $bool) 
	{
		if ($bool) 
		{
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		} 
		else 
		{
			$json = new Services_JSON();
		}
		return( $json->decode($data) );
	}
}

if(isset($_REQUEST['token'])) 
{ 
	$token 		= $_REQUEST['token'];
	$post_data	= array( 'token' => $_REQUEST['token'], 'apiKey' => $rpx_api_key, 'format' => 'json' ); 
	$post_url	= 'https://rpxnow.com/api/v2/auth_info/?token='.$token.'&apiKey='.$rpx_api_key.'&format=json';
	$curl 		= curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $post_url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$raw_json 	= curl_exec($curl);
	curl_close($curl);
		
	// parse the json response into an associative array
	$auth_info = json_decode($raw_json, true);	

	// process the auth_info response
	if ($auth_info['stat'] == 'ok') 
	{
		saveJanrainEngageUser($auth_info);
	} 
	else 
	{
		echo '<b>Error:</b> ' . $auth_info['err']['msg'];
	}
} 
else 
{
	$userParams	= &JComponentHelper::getParams( 'com_users' );
	
	if( $user->id ) 
	{
		$welcome = JText::_('Hi').' '.$user->get('name');
		$LogoutRedirect = 'index.php?option=com_user&task=logout&return='.$redirectUrl;
		$LogoutRedirect = JRoute::_($LogoutRedirect);
		?>
		<div class="janiainengage_main">
			<?php echo $welcome;?> 
			&nbsp;|&nbsp;
			<a href="<?php echo $LogoutRedirect;?>"><span style="text-decoration: underline;">Logout</span></a>
			<!--<input type="submit" onclick="document.location.href='< ?php echo $LogoutRedirect;? >'" value="< ?php echo JText::_('Log out');? >" class="button" name="Submit">-->
		</div>
		<?php
	}
	else 
	{
		if( $rpx_show_type == 'popup') 
		{
			$post_token_url = 'https://'.$rpx_application_name.'.rpxnow.com/openid/v2/signin?token_url='.$token_url;
		?>
			<a class="rpxnow janiainengage_a" onclick="return false;" href="<?php echo $post_token_url;?>">
			Sign In&nbsp;&nbsp;<img class="janiainengage_img" style="border:solid 0px #eeeeee;<?php echo $rpx_image_width.$rpx_image_height;?>" src="<?php echo $rpx_imageurl;?>" alt="<?php echo JText::_('SignIn Here');?>"/>
			</a>
		<script src="https://rpxnow.com/openid/v2/widget" type="text/javascript"></script>
		<script type="text/javascript">
		  RPXNOW.token_url = "<?php echo $token_url ?>";
		  RPXNOW.realm = "<?php echo $rpx_application_name; ?>.rpxnow.com";
		  RPXNOW.overlay = true;
		  RPXNOW.language_preference = 'en';
		</script>
		<?php
		}
		else 
		{
			$post_token_url = 'https://'.$rpx_application_name.'.rpxnow.com/openid/embed?token_url='.urlencode($token_url);
?>
<iframe class="janiainengage_iframe" src="<?php echo $post_token_url;?>" scrolling="no" frameBorder="no" style="width:400px;height:240px;"></iframe>
<?php 
		}
	}
}

function saveJanrainEngageUser($auth_info) 
{
	global $mainframe;
	jimport('joomla.user.helper');
	$db		=& JFactory::getDBO();
	$my 	=& JFactory::getUser();
	$uri 	=& JFactory::getURI();
	$host 	= $uri->getHost();

	// process the auth_info response
	$profileValues 	= $auth_info['profile'];
	$identifier 	= $profileValues['identifier'];	
	
	if( !isset($auth_info['profile']['email'] )) 
	{
		$nameDisp = str_replace(' ','_',$auth_info['profile']['displayName']);
		$auth_info['profile']['email'] = $nameDisp.'@'.$host;
	}
	
	$query = "SELECT `id` FROM #__users WHERE `email`='".$auth_info['profile']['email']."'";
	$db->setQuery($query);
	$userid = $db->loadResult();
	
	$newuser = true;
	if( isset($userid) ) 
	{
		$user =& JFactory::getUser($userid);
		if ($user->id == $userid) 
		{
            $newuser = false;
        }
	}
	if($newuser == true) 
	{
		//save the user
		$user 			= new JUser();
		$authorize 		=& JFactory::getACL();
		$date 			=& JFactory::getDate();
		$uriInfo 		= JFactory::getURI();
		$host 			= $uriInfo->getHost();
		$usersConfig	=& JComponentHelper::getParams( 'com_users' );
		$newUsertype	= $usersConfig->get( 'new_usertype' );
		
		$user->set('id', 0);
		$user->set('usertype', $newUsertype);
		$user->set('gid', $authorize->get_group_id('',$newUsertype, 'ARO'));
		$user->set('registerDate', $date->toMySQL());
		
		if(isset($auth_info['profile']['displayName'])) 
		{
			$displayName = $auth_info['profile']['displayName'];
		} 
		elseif(isset($auth_info['profile']['name']['displayName'])) 
		{
			$displayName = $auth_info['profile']['name']['displayName'];
		}
		
		if(isset($auth_info['profile']['preferredUsername'])) 
		{
			$preferredUsername = $auth_info['profile']['preferredUsername'];
		} 
		elseif(isset($auth_info['profile']['name']['preferredUsername'])) 
		{
			$preferredUsername = $auth_info['profile']['name']['preferredUsername'];
		}

		$user->set('name', $displayName);
		// if username already exists, just add an index to it
		$nameexists = true;
		$index 		= 0;
		$userName 	= $preferredUsername;
		while ($nameexists == true) 
		{
			if(JUserHelper::getUserId($userName) != 0) 
			{
				$index++;
				$userName = $preferredUsername.$index;
			} 
			else 
			{
				$nameexists = false;
			}
		}
		$user->set('username', $userName);
	  
		$sEmail = '';
		if(isset($auth_info['profile']['email'])) 
		{
			$sEmail = $auth_info['profile']['email'];
			$user->set('email', $auth_info['profile']['email']);
		} 
		elseif (isset($auth_info['profile']['name']['email'])) 
		{
		  	$sEmail = $auth_info['profile']['email'];
		  	$user->set('email', $auth_info['profile']['email']);
		} 
		
		$pwd = JUserHelper::genRandomPassword();
		$user->set('password', $pwd);
		
		if (!$user->save()) 
		{
			echo "ERROR: ";
			echo $user->getError();
		}
		
		// admin users gid
		$gid 		= 25;
		$query 		= "SELECT `email`, `name` FROM `#__users` WHERE `gid` = '".$gid."'";
		$db->setQuery( $query );		
		$adminRows 	= $db->loadObjectList();
	
		// send email notification to admins
		if( !empty($adminRows) ) 
		{
			foreach($adminRows as $adminRow) 
			{
				$sitename 	= $mainframe->getCfg( 'sitename' );
				$siteRoot   = JURI::base();
			
				$userName	= $user->get('username');
				$userID		= $user->get('id');
				$userTupe	= $user->get('usertype');
				$userEmail	= $user->get('email');
				$adminName 	= $adminRow->name;
				$adminEmail = $adminRow->email;
				
				$subject	= JText::_('New user registered via JAINARAIN ENGANGE at')." ".$sitename;
				$subject 	= html_entity_decode($subject, ENT_QUOTES);	
		
				$message 	= JText::_('Hello')." ".$adminName."\n";
				$message 	.= JText::_('New user registered via JAINARAIN ENGANGE at')." ".$siteRoot."\n\n";
				$message 	.= JText::_('User Detail:')."\n";
				$message 	.= JText::_('User ID :')." ".$userID."\n";
				$message 	.= JText::_('Usertype :')." ".$userTupe."\n";
				$message 	.= JText::_('Name :')." ".$displayName."\n";
				$message 	.= JText::_('Username :')." ".$userName."\n";
				$message 	.= JText::_('Email :')." ".$sEmail."\n";
				$message 	= html_entity_decode($message, ENT_QUOTES);
	
				JUtility::sendMail( $userName, $userEmail, $adminEmail,  $subject, $message );
			}
		}
			
		// check if the community builder tables are there
		$query 			= "SHOW TABLES LIKE '%__comprofiler'";
		$db->setQuery($query);
		$tableexists	= $db->loadResult();

		if( isset($tableexists) ) 
		{
			 $cbquery = "INSERT IGNORE INTO #__comprofiler(id,user_id,firstname,lastname) VALUES ('".$user->get('id')."','".$user->get('id')."','".$auth_info['profile']['name']['givenName']."','".$auth_info['profile']['name']['familyName']."')";
			$db->setQuery($cbquery);
			if (!$db->query()) 
			{
				JERROR::raiseError(500, $db->stderror());
			}
			else 
			{
				if($auth_info['profile']['photo']) 
				{
					global $_CB_database, $_CB_framework,   $ueConfig, $_PLUGINS ;
					if ( defined( 'JPATH_ADMINISTRATOR' ) ) 
					{
						include_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';
						require_once $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_comprofiler/comprofiler.html.php';						
					} 
					else 
					{						
						include_once $mainframe->getCfg( 'absolute_path' ). '/administrator/components/com_comprofiler/plugin.foundation.php';						
						require_once $mainframe->getPath( 'front_html' );						
					}
					$filename		=	urldecode(uniqid($user->get('id')."_"));
					
					// replace every space-character with a single "_"
					$filename		=	preg_replace( "/ /", "_",	 $filename );				
					// Get rid of extra underscores						
					$filename		=	preg_replace( "/_+/", "_",	 $filename );						
					$filename		=	preg_replace( "/(^_|_$)/", "", $filename );						
					$tag			=	preg_replace( "/^.*\\.([^\\.]*)$/", "\\1", $auth_info['profile']['photo'] );	
					$tag			=	strtolower( $tag );						
					$newFileName		=	$filename . ".jpg";					 
					$file		=	$_CB_framework->getCfg('absolute_path') . '/images/comprofiler/' . $newFileName;						
					copy( $auth_info['profile']['photo'], $file );						
					
					$db->setQuery("UPDATE #__comprofiler SET avatar='" .$newFileName . "', avatarapproved=1, lastupdatedate='".date('Y-m-d\TH:i:s')."' WHERE id=" . (int) $user->get('id'));						
					$db->query();
				}
			}
		}
					
		// check if the Jomsocial tables are there, then set avatar
		$query = "SHOW TABLES LIKE '%__community_users'";
		$db->setQuery($query);
		$Jomtableexists = $db->loadResult();

		if (isset($Jomtableexists) && $auth_info['profile']['photo']) 
		{
			jimport('joomla.filesystem.file');
			jimport('joomla.utilities.utility');
			require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'helpers'.DS.'image.php');
				
			$fileName	= JUtility::getHash( $auth_info['profile']['photo'] . time() );
			$fileName	= JString::substr( $fileName , 0 , 24 );
								   
			$avatarimage		= 'images/avatar/' . $fileName.'.jpg' ;
			$thumbavatar		= 'images/avatar/' . 'thumb_' . $fileName.'.jpg' ;
							

			$st = JPATH_ROOT;
			$jPath = split('\administrator',$st);
			 
			$storage	 = $jPath[0] . DS . 'images' . DS . 'avatar'. DS .   $fileName.'.jpg';
			$storageThumbnail	= $jPath[0] . DS .'images'.DS . 'avatar'. DS . 'thumb_' . $fileName.'.jpg' ;
			$destType = 'image/jpg';
			$imageMaxWidth	= 140; 
			   
			// Only resize when the width exceeds the max.
			if( !cImageResizePropotional( $auth_info['profile']['photo'] , $storage , $destType , $imageMaxWidth ) ) 
			{
				global $option,$mainframe;
				$msg = JText::sprintf( 'Image Upload Error '); 	
			}
	
			// Generate thumbnail
			if(!cImageCreateThumb( $auth_info['profile']['photo'] , $storageThumbnail , $destType  )) 
			{
				global $option,$mainframe;
				$msg = JText::sprintf( 'Image Upload Error '); 	
			}			
														 
			$query = "SELECT `userid` FROM `#__community_users` WHERE `userid`='" . $user->get('id') . "'";
			$db->setQuery( $query );
			if($db->loadResult()) 
			{				
				$query = "UPDATE `#__community_users` SET `avatar` = '" . $avatarimage . "', `thumb` = '" .$thumbavatar . "' WHERE `userid`='" . $user->get('id') . "'";
			}
			else 
			{
				$query = "INSERT INTO `#__community_users` SET `userid`='" . $user->get('id') . "', `avatar` = '" . $avatarimage . "', `thumb` = '" .$thumbavatar . "'";
			}
			$db->setQuery( $query );
			$db->query();		 
		}
	}

	// Get an ACL object
	$acl =& JFactory::getACL();

	// Get the user group from the ACL
	if ($user->get('tmp_user') == 1) 
	{
		$grp = new JObject;
		// This should be configurable at some point
		$grp->set('name', 'Registered');
	} 
	else 
	{
		$grp = $acl->getAroGroup($user->get('id'));
	}

	//Mark the user as logged in
	$user->set( 'guest', 0 );
	$user->set( 'aid', 1 );

	// Fudge Authors, Editors, Publishers and Super Administrators into the special access group
	if($acl->is_group_child_of($grp->name, 'Registered') || $acl->is_group_child_of($grp->name, 'Public Backend')) 
	{
		 $user->set('aid', 2);
	}

	//Set the usertype based on the ACL group name
	$user->set('usertype', $grp->name);

	// Register the needed session variables
	$session =& JFactory::getSession();
	$session->set('user', $user);

	// Get the session object
	$table =& JTable::getInstance('session');
	$table->load( $session->getId() );
	$table->guest           = $user->get('guest');
	$table->username        = $user->get('username');
	$table->userid          = intval($user->get('id'));
	$table->usertype        = $user->get('usertype');
	$table->gid             = intval($user->get('gid'));

	$table->update();

	// Hit the user last visit field
	$user->setLastVisit();
	 
	// redirect
	global $redirectUrl;
	$returnURL = $redirectUrl;
	$mainframe->redirect($returnURL); 

}
?>